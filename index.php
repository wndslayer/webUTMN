<?php
$pageTitle = 'lesson 17';

function solveTask1($a, $b)
{
    if ($a >= 0 && $b >= 0) {
        return $a - $b;
    }
    if ($a < 0 && $b < 0) {
        return $a * $b;
    }
    return $a + $b;
}

$task1Cases = [
    [7, 3],
    [-7, -3],
    [7, -3],
];

$start = 4;
$task2Numbers = [];

switch ($start) {
    case 0:  $task2Numbers[] = 0;
    case 1:  $task2Numbers[] = 1;
    case 2:  $task2Numbers[] = 2;
    case 3:  $task2Numbers[] = 3;
    case 4:  $task2Numbers[] = 4;
    case 5:  $task2Numbers[] = 5;
    case 6:  $task2Numbers[] = 6;
    case 7:  $task2Numbers[] = 7;
    case 8:  $task2Numbers[] = 8;
    case 9:  $task2Numbers[] = 9;
    case 10: $task2Numbers[] = 10;
    case 11: $task2Numbers[] = 11;
    case 12: $task2Numbers[] = 12;
    case 13: $task2Numbers[] = 13;
    case 14: $task2Numbers[] = 14;
    case 15: $task2Numbers[] = 15;
}

function add($x, $y)
{
    return $x + $y;
}

function subtract($x, $y)
{
    return $x - $y;
}

function multiply($x, $y)
{
    return $x * $y;
}

function divide($x, $y)
{
    if ($y == 0) {
        return 'деление на ноль';
    }
    return $x / $y;
}

function mathOperation($arg1, $arg2, $operation)
{
    switch ($operation) {
        case 'add':
            return add($arg1, $arg2);
        case 'subtract':
            return subtract($arg1, $arg2);
        case 'multiply':
            return multiply($arg1, $arg2);
        case 'divide':
            return divide($arg1, $arg2);
        default:
            return 'неизвестная операция';
    }
}

$x = 12;
$y = 4;
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
            <a href="/" class="logo">lesson 17</a>
        </div>
    </header>

    <main class="container">
        <section class="task">
            <h2>Задание 1</h2>
            <?php foreach ($task1Cases as $case): ?>
                <p>a = <?= $case[0] ?>, b = <?= $case[1] ?> → <?= solveTask1($case[0], $case[1]) ?></p>
            <?php endforeach; ?>
        </section>

        <section class="task">
            <h2>Задание 2</h2>
            <p>a = <?= $start ?></p>
            <p><?= implode(', ', $task2Numbers) ?></p>
        </section>

        <section class="task">
            <h2>Задание 3</h2>
            <p>add(<?= $x ?>, <?= $y ?>) = <?= add($x, $y) ?></p>
            <p>subtract(<?= $x ?>, <?= $y ?>) = <?= subtract($x, $y) ?></p>
            <p>multiply(<?= $x ?>, <?= $y ?>) = <?= multiply($x, $y) ?></p>
            <p>divide(<?= $x ?>, <?= $y ?>) = <?= divide($x, $y) ?></p>
        </section>

        <section class="task">
            <h2>Задание 4</h2>
            <p>mathOperation(<?= $x ?>, <?= $y ?>, 'add') = <?= mathOperation($x, $y, 'add') ?></p>
            <p>mathOperation(<?= $x ?>, <?= $y ?>, 'subtract') = <?= mathOperation($x, $y, 'subtract') ?></p>
            <p>mathOperation(<?= $x ?>, <?= $y ?>, 'multiply') = <?= mathOperation($x, $y, 'multiply') ?></p>
            <p>mathOperation(<?= $x ?>, <?= $y ?>, 'divide') = <?= mathOperation($x, $y, 'divide') ?></p>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>
