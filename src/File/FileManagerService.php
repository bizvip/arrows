<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace Arrows\File;

use App\System\Mapper\SystemUploadFileMapper;
use App\Utils\Str;
use Hyperf\Config\Annotation\Value;
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Support\Filesystem\Filesystem;

final class FileManagerService
{
    #[Inject]
    private Filesystem $filesystem;

    #[Value('file.storage.local.publish_dir_name')]
    private string $publishDirName;

    #[Value('file.storage.local.frontend_root')]
    private string $frontendRoot;

    #[Value('file.storage.local.public_root')]
    private string $publicRoot;

    #[Value('file.storage.local.upload_dir')]
    private string $uploadDir;

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function createFrontendLink(): bool
    {
        return $this->link(
            path: $this->publicRoot.DIRECTORY_SEPARATOR.$this->uploadDir, link: $this->frontendRoot.DIRECTORY_SEPARATOR.$this->uploadDir,
        );
    }

    public function getOriginalPath(string $uri = null, bool $filename = false): string
    {
        $dir = null === $uri ? sprintf("%s%s%s", $this->publicRoot, DIRECTORY_SEPARATOR, $this->uploadDir) : $this->publicRoot.dirname($uri);

        return false === $filename ? $dir : $dir.DIRECTORY_SEPARATOR.basename($uri);
    }

    public function publish(string $uploadUrl, string $path): bool
    {
        $publishPath = $this->getPublishPath().DIRECTORY_SEPARATOR.Str::getNameWithoutExt($path).'.js';

        if ($this->filesystem->copy(path: $path, target: $publishPath)) {
            $ufm = ApplicationContext::getContainer()->get(SystemUploadFileMapper::class);
            $ufm->updateByCondition(['url' => $uploadUrl], ['publish_name' => $publishPath]);
        }

        // 加入队列 广播给所有前端
        // return ApplicationContext::getContainer()->get(Producer::class)
        //     ->produce(
        //         producerMessage: new PublishProducer(
        //             [
        //                 'func'        => 'sync',
        //                 'publishName' => basename($publishPath),
        //                 'path'        => pathinfo($uploadUrl),
        //             ]
        //         ),
        //     );

        $frontDir = $this->frontendRoot.pathinfo($uploadUrl, PATHINFO_DIRNAME);
        if (!is_dir($frontDir)) {
            $this->filesystem->makeDirectory(path: $frontDir, recursive: true);
        }

        $frontPath = $frontDir.DIRECTORY_SEPARATOR.basename($publishPath);

        return $this->filesystem->copy($publishPath, $frontPath);
    }

    // public function syncUploadFileToPublish(string $uploadUrl): bool
    // {
    //     try {
    //         $this->linkFrontendPathCheck(uri: $uploadUrl, withFile: true);
    //         $originalFileFullPath = $this->getOriginalPath(uri: $uploadUrl, filename: true);
    //
    //         $publishOriginPath = $this->getPublishDirFromUrl($uploadUrl, true);
    //
    //         if (!$this->filesystem->isFile($originalFileFullPath)) {
    //             throw new BusinessException(message: sprintf('目标原文件 %s 已经被删除，请重新上传', $originalFileFullPath));
    //         }
    //
    //         // 将新文件复制到本地存储位置 分布存储以后不需要这一步了
    //         $publishNewNamePath = Str::replaceImageExtToJS($publishOriginPath);
    //         // $this->fs->copy(path: $originalFile, target: $newPublishFilePath);
    //
    //         // 上传新旧文件到oss 重名会覆盖
    //         $saveToOss = make(FileManager::class)->oss()->put(contents: $originalFileFullPath, path: $publishNewNamePath);
    //
    //         if (!$saveToOss) {
    //             $saveToOss = make(FileManager::class)->oss()->put(contents: $originalFileFullPath, path: $publishNewNamePath);
    //
    //             if (!$saveToOss) {
    //                 throw new BusinessException(message: '上传文件到远程存储出错');
    //             }
    //         }
    //
    //         $this->container->get(SystemUploadFileMapper::class)->updateByCondition(['url' => $uploadUrl], ['oss_url' => $publishNewNamePath]);
    //     } catch (\Throwable $e) {
    //         if ($e instanceof BusinessException) {
    //             throw new BusinessException(message: $e->getMessage());
    //         }
    //
    //         Logger::error($e);
    //         return false;
    //     }

    // }

    public function copyLocalToOss(string $uri): bool { return false; }

    public function copyOssToLocal(): bool { return false; }

    public function delFromOss(): bool { return false; }

    public function delFromLocal(): bool { return false; }

    public function link(string $path, string $link): bool
    {
        if (is_link($link)) {
            $p = readlink($link);
            if ($p === $path) {
                return true;
            }
            unlink($link);
        }
        if (file_exists($link)) {
            $this->filesystem->delete($link);
        }
        if (is_dir($link)) {
            $this->filesystem->deleteDirectories($link);
        }
        return symlink($path, $link);
    }

    public function getResourcesPath(bool $withMonth = false, bool $withSeparator = false): string
    {
        $path = $this->publicRoot.DIRECTORY_SEPARATOR.'resources';
        if ($withMonth) {
            $path .= DIRECTORY_SEPARATOR.date('ym');
        }
        if ($withSeparator) {
            $path .= DIRECTORY_SEPARATOR;
        }
        if (!file_exists($path)) {
            $this->filesystem->makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    public function getPublishPath(bool $withMonth = false): string
    {
        $path = $this->publicRoot.DIRECTORY_SEPARATOR.$this->publishDirName;
        if ($withMonth) {
            $path .= DIRECTORY_SEPARATOR.date('ym');
        }
        if (!file_exists($path)) {
            $this->filesystem->makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    public function getFrontendPath(): string
    {
        return $this->frontendRoot;
    }

    public function sendMessageToQueue() { }
}
