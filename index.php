<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .btn-container {
            text-align: center;
        }

        .btn-lg {
            font-size: 1.5rem;
            padding: 15px 30px;
            margin: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
    <title>AI Bot by Hassaan</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-container">
                <h1 class="mb-4">Welcome to AI Bot</h1>
                <p class="lead">Explore the features by clicking the buttons below</p>

                <button class="btn btn-primary btn-lg" onclick="navigateTo('ai-text-bot.php')">
                    <i class="bi bi-robot"></i> AI Text Bot
                </button>

                <button class="btn btn-success btn-lg" onclick="navigateTo('ai-image-generator.php')">
                    <i class="bi bi-file-image-fill"></i> AI Image Generator
                </button>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function navigateTo(page) {
        window.location.href = page;
    }
</script>
</body>
</html>