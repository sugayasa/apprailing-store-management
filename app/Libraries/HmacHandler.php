<?php

namespace App\Libraries;

class HmacHandler
{
    protected string $secretKey;
    protected string $apiKey;

    public function __construct()
    {
        $this->secretKey    =   HMAC_SECRET_KEY_RICH_GROUP;
        $this->apiKey       =   HMAC_API_KEY_RICH_GROUP;
    }

    /**
     * 1. GENERATOR
     */
    public function generateHeaders($payload): array
    {
        $bodyString =   is_array($payload) ? json_encode($payload, JSON_UNESCAPED_SLASHES) : $payload;
        $timestamp  =   time();
        $signature  =   hash_hmac('sha256', $timestamp . $bodyString, $this->secretKey);

        return [
            'Content-Type'  =>  'application/json',
            'X-API-KEY'     =>  $this->apiKey,
            'X-TIMESTAMP'   =>  (string)$timestamp,
            'X-SIGNATURE'   =>  $signature,
        ];
    }

    /**
     * 2. VALIDATOR
     */
    public function validateRequest(\CodeIgniter\HTTP\IncomingRequest $request): array
    {
        $apiKey     =   $request->header('X-API-KEY')?->getValue();
        $timestamp  =   $request->header('X-TIMESTAMP')?->getValue();
        $signature  =   $request->header('X-SIGNATURE')?->getValue();
        $body       =   $request->getBody();

        // Security check
        if (!$apiKey || !$timestamp || !$signature) {
            return [
                'isValid'   =>  false,
                'message'   =>    'Missing security headers'
            ];
        }

        // Check API Key
        if ($apiKey !== $this->apiKey) {
            return [
                'isValid'   =>  false,
                'message'   =>  'Unauthorized API Key'
            ];
        }

        // X seconds tolerance for timestamp
        if (abs(time() - (int)$timestamp) > HMAC_MAX_TIME_DIFF_RICH_GROUP) {
            return [
                'isValid'   =>  false,
                'message'   =>  'Request expired'
            ];
        }

        // Finally, validate the signature
        $expectedSignature   =   hash_hmac('sha256', $timestamp . $body, $this->secretKey);
        if (!hash_equals($expectedSignature, $signature)) {
            return [
                'isValid'   =>  false,
                'message'   =>  'Invalid signature'
            ];
        }

        return [
            'isValid'   =>  true,
            'message'   =>  'Valid request'
        ];
    }
}