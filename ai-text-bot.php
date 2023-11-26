<?php

require_once 'vendor/autoload.php';

use App\Service\ChatGPT\Client;

$config = require_once 'config/config.php';
$apiKey = $config['apiKey'];

$chatGPTService = new Client($apiKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = $_POST['user_input'] ?? '';

    // Process the user input
    $response = $chatGPTService->getTextResponse($userInput);
    if ($response == '--') {
        http_response_code(400);
        $response = 'Sorry, I couldn\'t understand that.';
    }

    // Output the response
    echo json_encode(['response' => $response]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Text Bot</title>

    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for styling enhancements -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        #chat-log {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            min-height: 200px;
        }

        .message {
            display: flex;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            max-width: 100%;
        }

        .user-message {
            align-self: flex-end;
            max-width: 70%;
        }

        .bot-message {
            align-self: flex-start;
            max-width: 70%;
        }

        #user-input {
            display: flex;
        }

        #user-input-text {
            flex: 1;
            margin-right: 10px;
        }

        #user-input-text[disabled] {
            cursor: not-allowed;
        }

        #loading-message {
            display: none;
            margin-top: 10px;
            font-size: 14px;
        }

        #start-over-button {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000; /* Ensure it's above other elements */
        }

        .sticky-btn {
            position: fixed;
            left: 10px;
            top: 10px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<div class="container col-8 mt-5" id="chat-container">
    <h1 class="mb-4">AI Text Bot</h1>

    <div id="chat-log" class="bg-light"></div>

    <div id="loading-message" style="display: none;">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-light">
        <div id="user-input" class="my-2">
            <div class="input-group">
                <input type="text" id="user-input-text" class="form-control form-control-lg"
                       placeholder="Type your message..."
                       onkeydown="handleKeyPress(event)">
                <button class="btn btn-primary btn-lg" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>
</div>

<button id="start-over-button" class="btn btn-warning" onclick="startOver()">Start Over</button>
<a class="btn btn-primary btn-sm sticky-btn" href="index.php">Back to Main</a>

<!-- Bootstrap JS and Popper.js via CDN (required for Bootstrap components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function sendMessage() {
        const userInput = document.getElementById('user-input-text');
        const userInputText = userInput.value;
        const chatLog = document.getElementById('chat-log');

        // Add user message to the chat log
        chatLog.innerHTML += `<div class="d-flex justify-content-end"><div class="message user-message bg-primary text-white mb-2 p-2 rounded">${userInputText}</div></div>`;

        // Add a placeholder message to the chat log
        chatLog.innerHTML += `<div class="d-flex justify-content-start placeholder-glow"><div class="placeholder message bot-message mb-2 p-2 rounded w-75"><span class="text-light p-2"><strong>Bot is typing...</strong></span></div></div>`;
        chatLog.scrollTop = chatLog.scrollHeight; // Scroll to the bottom of the chat log

        // Disable the input field and send button
        userInput.disabled = true;
        document.querySelector('button').disabled = true;

        // Make an AJAX request to the server
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'ai-text-bot.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {

                // Remove the placeholder message
                const placeholderMessage = chatLog.querySelector('.placeholder-glow');
                if (placeholderMessage) {
                    chatLog.removeChild(placeholderMessage);
                }

                // Enable the input field and send button
                userInput.disabled = false;
                document.querySelector('button').disabled = false;

                if (xhr.status === 200) {
                    // Successful response
                    const response = JSON.parse(xhr.responseText);

                    // preparing chat log message
                    const messageContent = document.createElement('div');
                    const classList = ['message', 'bot-message', 'bg-dark', 'text-light', 'mb-2', 'p-2', 'rounded', 'w-100'];
                    messageContent.classList.add(...classList);

                    if (isCode(response.response)) {
                        // If the response is code-like, display as a code block
                        const codeBlock = document.createElement('pre');
                        const codeElement = document.createElement('code');
                        codeElement.innerText = response.response;
                        codeBlock.appendChild(codeElement);
                        messageContent.appendChild(codeBlock);
                    } else {
                        messageContent.innerText = response.response;
                    }

                    chatLog.innerHTML += `
                    <div class="d-flex justify-content-start">
                        ${messageContent.outerHTML}
                    </div>`;

                    // Empty user input field after receiving a response
                    userInput.value = '';

                    // Focus on the user input field after receiving a response
                    userInput.focus();
                } else {
                    // Handle error response
                    const errorMessage = 'Error communicating with the server. Please try again.';
                    chatLog.innerHTML += `<div class="d-flex justify-content-start"><div class="message bg-danger text-light mb-2 p-2 rounded">${errorMessage}</div></div>`;
                }
                // scroll to last chat log
                chatLog.scrollTop = chatLog.scrollHeight;
            }
        };
        xhr.send(`user_input=${encodeURIComponent(userInputText)}`);
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            // Prevent the default behavior of the Enter key (e.g., adding a new line)
            event.preventDefault();

            // Trigger the sendMessage function
            sendMessage();
        }
    }

    function startOver() {
        window.location.reload();
    }

    // Function to check if the response is code-like
    function isCode(response) {
        // Simple check for code-like content using regular expressions
        const codePatterns = [
            /^\s*function\s*\([^\)]*\)\s*{/i,  // JavaScript function
            /^\s*class\s+[A-Za-z_][A-Za-z0-9_]*\s*{/i,  // JavaScript class
            /<[^>]+>/,  // HTML tags
            /^\s*#/  // Python comments (you can add more patterns as needed)
        ];

        return codePatterns.some(pattern => pattern.test(response));
    }

    function nl2br(str, replaceMode, isXhtml) {
        const breakTag = (isXhtml) ? '<br />' : '<br>';
        const replaceStr = (replaceMode) ? '$1' + breakTag : '$1' + breakTag + '$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
    }
</script>
</body>
</html>