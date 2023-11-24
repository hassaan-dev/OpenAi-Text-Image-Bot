<?php

namespace App\Service\ChatGPT;

use App\Helper\Debug;

class Client
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getTextResponse($userInput)
    {
        $url = 'https://api.openai.com/v1/engines/text-davinci-003/completions';

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ];

        $data = [
            'prompt' => $userInput,
            'temperature' => 0.7,
            'max_tokens' => 150,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if ($response === false) {
            die(curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        return $responseData['choices'][0]['text'] ?? '--';
    }

    public function getImageResponse($userInput)
    {
        $url = 'https://api.openai.com/v1/images/generations';

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ];

        $data = [
            'model' => 'dall-e-3',
            'prompt' => $userInput,
            "n" => 1,
//            "size" => "256x256" // dall-e-2 available options: 256x256, 512x512, or 1024x1024
            "size" => "1024x1024" // dall-e-3 available options: 1024x1024, 1792x1024, or 1024x1792
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if ($response === false) {
            die(curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        return $responseData['data'][0]['url'] ?? '--';
    }
}