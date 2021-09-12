<?php

namespace App\Http\Mocks;

class AppleVerification implements ProviderVerify
{
    public $endpoint = 'https://api.apple.com/apple/verify';
}
