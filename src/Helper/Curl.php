<?php

namespace App\Helper;

class Curl
{
    public function get($url, $headers = [])
    {
        return $this->request('GET', $url, [], $headers);
    }

    public function post($url, $data = [], $headers = [])
    {
        return $this->request('POST', $url, $data, $headers);
    }

    private function request($method, $url, $data = [], $headers = [])
    {
        $ch = curl_init();

        // Set common options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Set method-specific options
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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