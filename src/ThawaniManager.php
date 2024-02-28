<?php

namespace Ialtoobi\Thawani;

use Exception;
use Illuminate\Support\Facades\Http;

class ThawaniManager
{
    /**
     * Create a checkout session. After the session has been created,
     * use the session_id to redirect the user to the Thawani payment hosted page.
     *
     * url format: https://uatcheckout.thawani.om/pay/{session_id}?key=publishable_key
     *
     * @param array $data
     * @return array
     */
    public function createCheckoutSession(array $data)
    {
        $response = $this->makeRequest('post', '/checkout/session', $data);

        if (isset($response['success']) && $response['success'] === true && $response['code'] === 2004) {

            //return $response['data']['session_id'];
            $session_id = $response['data']['session_id'];
            $redirect_url = $this->createCheckoutURL($session_id);

            return [
                'session_id' => $session_id,
                'redirect_url' => $redirect_url,
            ];
        }

        $this->handleError($response);
    }

    /**
     * Get checkout session by session_id.
     *
     * https://uatcheckout.thawani.om/api/v1/checkout/session/{session_id}
     *
     * @param string $session_id
     * @return array
     */
    public function retrieveCheckoutSession(string $session_id)
    {
        return $this->makeRequest('get', "/checkout/session/{$session_id}");
    }

    /**
     * Get a list of sessions.
     *
     * https://uatcheckout.thawani.om/api/v1/checkout/session?limit=10&skip=20
     *
     * @param string limit & skip
     * @return array
     */
    public function getAllCheckoutSession()
    {
        return $this->makeRequest('get', "/checkout/session/");
    }

    /**
     * Cancel Checkout Session, by providing session_id.
     *
     * https://uatcheckout.thawani.om/api/v1/checkout/{session_id}/cancel
     *
     * @param string $session_id
     * @return array
     */
    public function cancelCheckoutSession(string $session_id)
    {
        return $this->makeRequest('post', "/checkout/{$session_id}/cancel");
    }

    /**
     * Create customers to allow them to save cards for later payment
     * since some Thawani services mandate the use of customer_id.
     *
     * https://uatcheckout.thawani.om/api/v1/customers
     *
     * @param array $data
     * @return array
     */
    public function createCustomer(array $data)
    {
        return $this->makeRequest('post', '/customers', $data);
    }

    /**
     * Get Customer by customer_id.
     *
     * https://uatcheckout.thawani.om/api/v1/customers/{customer_id}
     *
     * @param string $customer_id
     * @return array
     */
    public function retrieveCustomer(string $customer_id)
    {
        return $this->makeRequest('get', "/customers/{$customer_id}/");
    }

    /**
     * Remove customer by customer_id.
     *
     * https://uatcheckout.thawani.om/api/v1/customers/{customer_id}
     *
     * @param string $customer_id
     * @return array
     */
    public function deleteCustomer(string $customer_id)
    {
        return $this->makeRequest('delete', "/customers/{$customer_id}/");
    }

    /**
     * List Customer's Payment Method.
     * Get payment methods related to customer using customer_id
     *
     * https://uatcheckout.thawani.om/api/v1/payment_methods
     *
     * @param string $customer_id
     * @return array
     */
    public function customerPaymentMethod(string $customer_id)
    {
        // Get the base URL and secret key based on the environment
        $baseUrl = self::getBaseUrl();
        $secretKey = self::getSecretKey();

        // Make the GET request to fetch the session
        $response = Http::baseUrl($baseUrl)
            ->withHeaders(['thawani-api-key' => $secretKey])
            ->asJson()
            ->get("/payment_methods?customer_id={$customer_id}");

        // Check if the request was successful
        if ($response->successful()) {
            return $response->json();
        }

        // If not successful, throw an exception with the error description and code
        $this->handleError($response);

        //return $this->makeRequest('get', "/payment_methods?customer_id={$customer_id}");
    }

    /**
     * Remove payment method by card_id.
     *
     * https://uatcheckout.thawani.om/api/v1/payment_methods/{card_id}
     *
     * @param string $card_id
     * @return array
     */
    public function deletePaymentMethod(string $card_id)
    {
        return $this->makeRequest('delete', "/payment_methods/{$card_id}");
    }

    /**
     * Create Payment Intent. Payment intent used once the card of your customer saved,
     * and you want to charge your customer off-session.
     * Once Payment intent has been created use the payment_intent.id
     * to confirm the payment intent and proceed with processing the payment.
     *
     * https://uatcheckout.thawani.om/api/v1/payment_intents
     *
     * @param array $data
     * @return array
     */
    public function createPaymentIntent(array $data)
    {
        $response = $this->makeRequest('post', '/payment_intents', $data);

        if (isset($response['success']) && $response['success'] === true && $response['code'] === 2001) {
            return $response['data']['id'];
        }

        $this->handleError($response);
    }

    /**
     * Payment Intent confirmation. payment_method_id should be provided here
     * if it didn't happen at creating Payment Intent.
     *
     * https://uatcheckout.thawani.om/api/v1/payment_intents/{payment_intent_id}/confirm
     *
     * @param string $payment_intent_id
     * @return array
     */
    public function confirmPaymentIntent(string $payment_intent_id)
    {
        $response = $this->makeRequest('post', "/payment_intents/{$payment_intent_id}/confirm");

        if (isset($response['success']) && $response['success'] === true && $response['code'] === 2000) {

            $payment_id = $response['data']['id'];
            $redirect_url = $response['data']['next_action']['url'];

            return [
                'payment_id' => $payment_id,
                'redirect_url' => $redirect_url,
            ];
        }

        $this->handleError($response);

        //return $this->makeRequest('post', "/payment_intents/{$payment_intent_id}/confirm");
    }

    /**
     * Get Payment Intent by payment_intent_id.
     *
     * https://uatcheckout.thawani.om/api/v1/payment_intents/{payment_intent_id}
     *
     * @param string $payment_intent_id
     * @return array
     */
    public function retrievePaymentIntent(string $payment_intent_id)
    {
        return $this->makeRequest('get', "/payment_intents/{$payment_intent_id}");
    }

    /**
     * Cancel Payment Intent, by providing payment_intent_id.
     *
     * https://uatcheckout.thawani.om/api/v1/payment_intents/{payment_intent_id}/cancel
     *
     * @param string $payment_intent_id
     * @return array
     */
    public function cancelPaymentIntent(string $payment_intent_id)
    {
        return $this->makeRequest('post', "/payment_intents/{$payment_intent_id}/cancel");
    }

    /**
     * Performs an HTTP request to the Thawani API.
     *
     * This method constructs and executes an HTTP request using the specified method,
     * URI, and data payload. It automatically includes necessary headers such as the
     * Thawani API key. On successful response, it returns the JSON-decoded content.
     * If the request fails, it delegates error handling to `handleError`.
     *
     * @param  string $method The HTTP method (e.g., 'get', 'post').
     * @param  string $uri The URI to request, relative to the base API URL.
     * @param  array $data The data to send with the request (for POST, PUT requests).
     * @return array The JSON-decoded response data.
     * @throws Exception if the request fails or the API returns an error.
     */
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

        // if ($response->failed()) {
        //     return [
        //         'url' => $baseUrl . $uri,
        //         'response' => $response->json(),
        //         'status' => $response->status(),
        //     ];
        //     throw new Exception("API Request Failed: " . $response->body());
        // }

        $this->handleError($response->json());
    }

    /**
     * Handles errors returned by the Thawani API.
     *
     * This method is invoked when `makeRequest` encounters an unsuccessful response.
     * It throws an exception with the error description and code provided by the API.
     * If no specific error information is available, it throws a generic error message.
     *
     * @param array $response The JSON-decoded response array from the API.
     * @throws Exception with the error message and code.
     */
    protected function handleError($response)
    {
        throw new Exception($response['description'] ?? 'Unknown error', $response['code'] ?? 0);
    }

    /**
     * Retrieves Thawani API configuration settings.
     *
     * This method fetches the configuration settings for the Thawani API based on the
     * current environment mode (e.g., 'test', 'live'). It returns configuration details
     * such as the base URL and secret key.
     *
     * The configuration settings for the current mode.
     */
    protected function getThawaniConfig()
    {
        $mode = config('thawani.mode');
        return config("thawani.{$mode}");
    }

    /**
     * Retrieves the base URL for Thawani API requests.
     *
     * This method extracts and returns the base URL from the Thawani configuration
     * settings. It determines the correct base URL based on the current environment mode.
     *
     * @return string The base URL for the Thawani API.
     */
    protected function getBaseUrl(): string
    {
        return $this->getThawaniConfig()['base_url'];
    }


    /**
     * Retrieves the secret key for authenticating Thawani API requests.
     *
     * @return string The secret key for the Thawani API.
     */
    protected function getSecretKey(): string
    {
        $config = $this->getThawaniConfig();
        return $config['secret_key'];
    }

    /**
     * Retrieves the publishable key required for redirecting to the Thawani payment page.
     *
     * https://uatcheckout.thawani.om/pay/{session_id}?key=publishable_key
     *
     * @return string The publishable key for the Thawani API, necessary for payment page redirection.
     * @throws Exception If the publishable key is not found in the configuration, indicating that the Thawani API integration may not be correctly set up for the current operational mode ('test' or 'live').
     */
    protected function getPublishableKey(): string
    {
        $config = $this->getThawaniConfig();

        if (!isset($config['publishable_key'])) {
            throw new Exception("Publishable key not configured for Thawani '{$config['mode']}' mode.");
        }
        return $config['publishable_key'];
    }

    /**
     * Generates the URL for redirecting to the Thawani payment page.
     *
     * This method constructs the URL required to redirect users to the Thawani payment page,
     * incorporating the session ID and the publishable key into the URL. The session ID is
     * used to identify the specific payment session, and the publishable key authenticates
     * the request client-side without exposing sensitive information.
     *
     * @param string $session_id The unique identifier for the payment session.
     * @return string The fully constructed URL for initiating the payment process on Thawani.
     */
    public function createCheckoutURL($session_id): string
    {
        // Retrieve the publishable key from the configuration.
        $publishableKey = $this->getPublishableKey();

        // Retrieve the checkout base URL from the configuration (thawani.php).
        $checkoutBaseUrl = $this->getThawaniConfig()['checkout_base_url'];

        // Construct and return the full URL for redirecting to the Thawani payment page.
        $redirectUrl = "{$checkoutBaseUrl}/{$session_id}?key={$publishableKey}";

        return $redirectUrl;
    }
}
