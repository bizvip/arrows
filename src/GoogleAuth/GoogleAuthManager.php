<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace Arrows\GoogleAuth;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Hyperf\Di\Annotation\Inject;
use PragmaRX\Google2FA\Google2FA;

/**
 * composer require pragmarx/google2fa
 * composer require endroid/qr-code
 */
final class GoogleAuthManager
{
    #[Inject]
    private Google2FA $google2FA;

    private Logo $logo;

    private Label $label;

    public function setLogo(string $path = BASE_PATH.'/public/logo.png', int $width = 50, bool $punchOutBackground = true): self
    {
        $this->logo = Logo::create($path)
            ->setResizeToWidth($width)
            ->setPunchoutBackground($punchOutBackground);
        return $this;
    }

    public function setLabel(string $text = '严禁使用非工作机扫描', Color $color = null): self
    {
        if (!$color) {
            $color = new Color(0, 0, 0);
        }
        $this->label = Label::create($text)->setTextColor($color);
        return $this;
    }

    public function check(string $otpCode, string $secret): bool
    {
        return (bool)$this->google2FA->verifyKey($secret, $otpCode);
    }

    public function genSecretKey(int $len = 16, string $prefix = ''): string
    {
        return $this->google2FA->generateSecretKey($len, $prefix);
    }

    private function getLogo(): Logo
    {
        return $this->logo;
    }

    private function getLabel(): Label
    {
        return $this->label;
    }

    public function genQrCodeBase64(string $secretKey, string $company = null, string $account = null, int $size = 300, int $margin = 1): string
    {
        $qrData = $this->google2FA->getQRCodeUrl(
            company: $company,
            holder : $account,
            secret : $secretKey
        );

        $writer = new PngWriter();
        $logo   = $this->setLogo()->getLogo();
        $label  = $this->setLabel()->getLabel();

        $qrCode = QrCode::create($qrData)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize($size)
            ->setMargin($margin)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        return $writer->write($qrCode, $logo, $label)->getDataUri();
    }
}
