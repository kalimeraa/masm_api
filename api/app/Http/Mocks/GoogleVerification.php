<?php

namespace App\Http\Mocks;


class GoogleVerification implements ProviderVerify
{
    public $endpoint = 'https://api.google.com/android/verify';
}
