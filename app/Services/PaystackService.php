<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $baseUrl;

    protected string $secretKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.paystack.base_url'), '/');
        $this->secretKey = (string) config('services.paystack.secret_key');
    }

    public function ready(): bool
    {
        return filled($this->secretKey);
    }

    protected function client()
    {
        $client = Http::baseUrl($this->baseUrl)
            ->withToken($this->secretKey)
            ->acceptJson()
            ->timeout(30);

        if (config('services.paystack.verify_ssl', true) === false) {
            $client->withoutVerifying();
        }

        return $client;
    }

    /**
     * Initialize a Paystack transaction and return the authorization URL.
     *
     * @return array{status: bool, message: string, reference?: string, authorization_url?: string, access_code?: string}
     */
    public function initializeTransaction(array $data): array
    {
        $res = $this->client()->post('/transaction/initialize', $data);

        $body = $res->json();

        if (! $res->successful() || ! ($body['status'] ?? false)) {
            Log::error('Paystack initialize failed', [
                'status' => $res->status(),
                'response' => $body,
            ]);

            return [
                'status' => false,
                'message' => $body['message'] ?? 'Could not initialize payment. Please try again.',
            ];
        }

        return [
            'status' => true,
            'message' => $body['message'] ?? 'Payment initialized.',
            'reference' => $body['data']['reference'] ?? null,
            'authorization_url' => $body['data']['authorization_url'] ?? null,
            'access_code' => $body['data']['access_code'] ?? null,
        ];
    }

    /**
     * Verify a transaction and return the transmuted data.
     *
     * @return array{status: bool, message: string, data?: array}
     */
    public function verifyTransaction(string $reference): array
    {
        $res = $this->client()->get("/transaction/verify/{$reference}");

        $body = $res->json();

        if (! $res->successful() || ! ($body['status'] ?? false)) {
            Log::error('Paystack verify failed', [
                'reference' => $reference,
                'status' => $res->status(),
                'response' => $body,
            ]);

            return [
                'status' => false,
                'message' => $body['message'] ?? 'Could not verify the payment.',
            ];
        }

        return [
            'status' => true,
            'message' => $body['message'] ?? 'Payment verified.',
            'data' => $body['data'] ?? [],
        ];
    }

    public function verifySignature(string $payload, string $signature): bool
    {
        return hash_equals(
            hash_hmac('sha512', $payload, $this->secretKey),
            $signature
        );
    }

    public function publicKey(): ?string
    {
        return config('services.paystack.public_key');
    }
}