<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= h(config('app.title')) ?></title>
<link rel="icon" type="image/png" href="logo.png?v=<?= (int)@filemtime(BASE_PATH . '/logo.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Lexend:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/app.css?v=<?= (int)@filemtime(BASE_PATH . '/assets/css/app.css') ?>">
</head>
<body class="<?= me() ? 'app' : 'auth' ?>">