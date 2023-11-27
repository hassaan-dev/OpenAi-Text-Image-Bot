<?php

namespace App\Helper;

use Exception;

class Curl
{
    /**
     * @throws Exception
     */
    public function get($url, $headers = [])
    {
        return $this->request('GET', $url, [], $headers);
    }

    /**
     * @throws Exception
     */
    public function post($url, $data = [], $headers = [], $encodeAsJson = false)
    {
        return $this->request('POST', $url, $data, $headers, $encodeAsJson);
    }

    /**
     * @throws Exception
     */
    public function patch($url, $data = [], $headers = [], $encodeAsJson = false)
    {
        return $this->request('PATCH', $url, $data, $headers, $encodeAsJson);
    }

    /**
     * @throws Exception
     */
    public function put($url, $data = [], $headers = [], $encodeAsJson = false)
    {
        return $this->request('PUT', $url, $data, $headers, $encodeAsJson);
    }

    /**
     * @throws Exception
     */
    public function delete($url, $headers = [])
    {
        return $this->request('DELETE', $url, [], $headers);
    }

    /**
     * @throws Exception
     */
    private function request($method, $url, $data = [], $headers = [], $encodeAsJson = false)
    {
        $ch = curl_init();

        // Set common options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Set method-specific options
        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            if (!empty($data)) {
                $postData = $encodeAsJson ? json_encode($data) : http_build_query($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }
        }

        // Set headers
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            throw new \Exception(curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        return $response;
    }
}