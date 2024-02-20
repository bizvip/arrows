<?php

/******************************************************************************
 * Copyright (c) 2024. Archer++                                               *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\PaymentGateway;

interface PaymentGatewayInterface
{
    public function init(): bool;

    public function deposit();

    public function withawal();
}