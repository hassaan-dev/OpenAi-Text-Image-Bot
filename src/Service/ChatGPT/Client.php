<?php

namespace App\Service\ChatGPT;

use App\Helper\Curl;
use App\Helper\Debug;

class Client
{
    private $apiKey;
    private $curl;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->curl = new Curl();
    }

    public function getTextResponse($userInput)
    {
        $url = 'https://api.openai.com/v1/engines/gpt-3.5-turbo-instruct/completions';

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ];

        $data = [
            'prompt' => $userInput,
            'temperature' => 0.7,
            'max_tokens' => 150,
        ];

        $response = $this->curl->post($url, $data, $headers);
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

        $response = $this->curl->post($url, $data, $headers);

        $responseData = json_decode($response, true);

        return $responseData['data'][0]['url'] ?? '--';
    }
}