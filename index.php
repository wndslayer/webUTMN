<?php
$pageTitle = 'Урок 16 — PHP, переменные и функции';
$pageHeading = 'Текущее время на сервере';
$currentYear = date('Y');

function pluralize($number, array $forms)
{
    $number = abs((int) $number) % 100;
    $lastDigit = $number % 10;

    if ($number > 10 && $number < 20) {
        return $forms[2];
    }
    if ($lastDigit > 1 && $lastDigit < 5) {
        return $forms[1];
    }
    if ($lastDigit === 1) {
        return $forms[0];
    }
    return $forms[2];
}

function getCurrentTimeFormatted()
{
    $hours = (int) date('G');
    $minutes = (int) date('i');

    $hoursWord = pluralize($hours, ['час', 'часа', 'часов']);
    $minutesWord = pluralize($minutes, ['минута', 'минуты', 'минут']);

    return $hours . ' ' . $hoursWord . ' ' . $minutes . ' ' . $minutesWord;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="/" class="logo">lesson 16</a>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <h1><?= $pageHeading ?></h1>
            <p class="time"><?= getCurrentTimeFormatted() ?></p>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= $currentYear ?></p>
        </div>
    </footer>
</body>
</html>
