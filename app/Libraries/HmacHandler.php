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
                'message'   =>  'Missing security headers'
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

    /**
     * 3. ENCODER DATA
     */
    public function encodeData(string $data): string
    {
        $encoded    =   base64_encode($data);
        $signature  =   hash_hmac('sha256', $encoded, $this->secretKey);

        return $encoded . '.' . $signature;
    }

    /**
     * 4. DECODER DATA
     */
    public function decodeData(string $token): ?string
    {
        $parts  =   explode('.', $token, 2);

        if (count($parts) !== 2) return null;

        [$encoded, $signature]  =   $parts;

        $expectedSignature  =   hash_hmac('sha256', $encoded, $this->secretKey);
        if (!hash_equals($expectedSignature, $signature)) return null;

        $decoded    =   base64_decode($encoded, true);

        return $decoded === false ? null : $decoded;
    }
}