<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class PaymentRequest
{
    public string $totalAmount;

    public string $currency;

    public string $referenceNumber;

    public string $creditCardToken;

    public string $transientTokenJwt;

    public string $companyName;

    public string $address1;

    public string $zip;

    public string $city;

    public string $state;

    public string $country;

    public string $firstName;

    public string $lastName;

    public string $email;
}
