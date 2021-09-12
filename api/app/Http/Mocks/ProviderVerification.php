<?php

namespace App\Http\Mocks;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ProviderVerification
{
    /**
     * @var string
     */
    private string $receipt;

    /**
     * @var ProviderVerify
     */
    private ProviderVerify $providerVerify;

    /**
     * @var string
     */
    private string $lastTwoCharacters;

    /**
     * @param string $receipt
     */
    public function __construct(string $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @param ProviderVerify $providerVerify
     * @return JsonResponse
     */
    public function verify(ProviderVerify $providerVerify): JsonResponse
    {
        $this->providerVerify = $providerVerify;
        $this->lastTwoCharacters = substr($this->receipt, -2);

        if (!is_numeric($this->lastTwoCharacters)) {
            return response()->json([
                'status' => false,
                'message' => trans('subscription.last_two_characters_are_not_numeric')
            ], 422);
        }

        if ($this->exceedRateLimit()) {
            return response()->json([
                'status' => false,
                'message' => trans('rate_limit.exceed')
            ], 429);
        }

        return $this->checkReceipt();
    }

    /**
     * @return bool
     */
    protected function exceedRateLimit(): bool
    {
        if ($this->lastTwoCharacters % 6 === 0) {
            return true;
        }

        return false;
    }

    /**
     * @return JsonResponse
     */
    protected function checkReceipt(): JsonResponse
    {
        $lastCharacter = substr($this->receipt, -1);
        if ($lastCharacter % 2 === 0) {
            return response()->json([
                'status' => false,
                'message' => trans('subscription.last_character_should_be_odd_number')
            ], 422);
        }

        Http::fake([
            $this->providerVerify->endpoint => Http::response([
                'status' => true,
                'message' => trans('subscription.subscribed'),
                'expire_date' => Carbon::now()
                    ->addMonths()
                    ->setTimezone('America/New_York')
                    ->format('Y-m-d H:i:s T')
            ]),
        ]);

        return response()->json(Http::post($this->providerVerify->endpoint, [
            'receipt' => $this->receipt
        ])->json());
    }
}
