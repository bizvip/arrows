<?php

/******************************************************************************
 * Copyright (c) 2024. Archer++                                               *
 ******************************************************************************/

declare(strict_types=1);

namespace App\Utils\PaymentGateway;

interface PaymentGatewayInterface
{
    public function init(): bool;

    public function deposit();

    public function withawal();
}