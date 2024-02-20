<?php

/******************************************************************************
 * Copyright (c) 2024. Archer++                                               *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\PaymentGateway;

use Hyperf\Config\Annotation\Value;
use UniPayment\Client\UniPaymentClient;

use function Hyperf\Support\make;

/**
 * https://console.unipayment.io
 */
final class UniPayment implements PaymentGatewayInterface
{
    #[Value('payment.uni_payment.client_id')]
    private readonly string $clientId;

    #[Value('payment.uni_payment.client_secret')]
    private string $clientSecret;

    #[Value('payment.uni_payment.app_id')]
    private string $appId;

    private UniPaymentClient $uniPay;

    public function __construct()
    {
        //todo  判断该支付网关是否开启
    }

    public function init(): bool
    {
        try {
            $this->uniPay = make(UniPaymentClient::class);
            $this->uniPay->getConfig()->setClientId($this->clientId);
            $this->uniPay->getConfig()->setClientSecret($this->clientSecret);
            $this->uniPay->getConfig()->setIsSandbox(true);

            return true;
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }

    public function deposit()
    {
        // TODO: Implement payIn() method.
    }

    public function withawal()
    {
        // TODO: Implement payOut() method.
    }

    private function createInvoice()
    {
        $invoiceRequest = new \UniPayment\Client\Model\CreateInvoiceRequest();
        $invoiceRequest->setAppId($this->appId);
        $invoiceRequest->setPriceAmount((float)"10.05");
        $invoiceRequest->setPriceCurrency("USD");
        $invoiceRequest->setNotifyUrl("https://example.com/notify");
        $invoiceRequest->setRedirectUrl("https://example.com/redirect");
        $invoiceRequest->setOrderId("#123456");
        $invoiceRequest->setTitle("MacBook Air");
        $invoiceRequest->setDescription("MacBookAir (256#)");

        $create_invoice_response = $this->uniPay->createInvoice($invoiceRequest);
        print_r($create_invoice_response);
    }
}
