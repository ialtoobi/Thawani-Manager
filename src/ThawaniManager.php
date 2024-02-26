<?php

namespace Ialtoobi\Thawani;

use Exception;
use Illuminate\Support\Facades\Http;

class ThawaniManager
{
    public function createCheckoutSession(array $data)
    {
        $response = $this->makeRequest('post', '/checkout/session', $data);

        if (isset($response['success']) && $response['success'] === true && $response['code'] === 2004) {
            return $response['data']['session_id'];
        }

        $this->handleError($response);
    }

    public function getCheckoutSession(string $sessionId)
    {
        return $this->makeRequest('get', "/checkout/session/{$sessionId}");
    }

    public function getAllCheckoutSession()
    {
        return $this->makeRequest('get', "/checkout/session/");
    }

    public function cancelCheckoutSession(string $sessionId)
    {
        return $this->makeRequest('post', "/checkout/{$sessionId}/cancel");
    }

    public function createCustomer(array $data)
    {
        return $this->makeRequest('post', '/customers', $data);
    }

    public function retrieveCustomer(string $customerId)
    {
        return $this->makeRequest('get', "/customers/{$customerId}/");
    }

    public function customerPaymentMethod(string $customerId)
    {
        return $this->makeRequest('get', "/payment_methods?customer_id={$customerId}");
    }

    public function deletePaymentMethod(string $cardId)
    {
        return $this->makeRequest('delete', "/payment_methods/{$cardId}");
    }

    public function createPaymentIntent(array $data)
    {
        $response = $this->makeRequest('post', '/payment_intents', $data);

        if (isset($response['success']) && $response['success'] === true && $response['code'] === 2001) {
            return $response['data']['id'];
        }

        $this->handleError($response);
    }

    public function confirmPaymentIntent(string $paymentIntentId)
    {
        return $this->makeRequest('post', "/payment_intents/{$paymentIntentId}/confirm");
    }

    public function retrievePaymentIntent(string $paymentIntentId)
    {
        return $this->makeRequest('get', "/payment_intents/{$paymentIntentId}");
    }

    public function cancelPaymentIntent(string $paymentIntentId)
    {
        return $this->makeRequest('post', "/payment_intents/{$paymentIntentId}/cancel");
    }

    protected function makeRequest(string $method, string $uri, array $data = [])
    {
        $baseUrl = $this->getBaseUrl();
        $secretKey = $this->getSecretKey();

        $response = Http::baseUrl($baseUrl)
            ->withHeaders(['thawani-api-key' => $secretKey])
            ->asJson()
            ->{$method}($uri, $data);

        if ($response->successful()) {
            return $response->json();
        }

        $this->handleError($response->json());
    }

    protected function handleError($response)
    {
        throw new Exception($response['description'] ?? 'Unknown error', $response['code'] ?? 0);
    }

    protected function getThawaniConfig()
    {
        $mode = config('thawani.mode');
        return config("thawani.{$mode}");
    }

    protected function getBaseUrl(): string
    {
        return $this->getThawaniConfig()['base_url'];
    }

    protected function getSecretKey(): string
    {
        return $this->getThawaniConfig()['secret_key'];
    }
}
