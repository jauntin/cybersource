<?php

namespace Jauntin\CyberSource\Api;

enum CardNetworks: string
{
    case VISA = 'VISA';
    case MASTERCARD = 'MASTERCARD';
    case AMEX = 'AMEX';
    case DINERS = 'DINERS';
    case DISCOVER = 'DISCOVER';
    case JCB = 'JCB';
}
