<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | AutoMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Outfit:wght@700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>

<body>
    <div class="page-404">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?= baseUrl() ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-home"></i> Back to Home
        </a>
    </div>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>

</html>