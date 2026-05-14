<?php
$pageTitle = 'Урок 18';

function printNumbers()
{
    $result = [];
    $i = 0;
    do {
        if ($i === 0) {
            $result[] = "$i — это ноль.";
        } elseif ($i % 2 === 0) {
            $result[] = "$i — чётное число.";
        } else {
            $result[] = "$i — нечётное число.";
        }
        $i++;
    } while ($i <= 10);

    return $result;
}

$regions = [
    'Московская область' => ['Москва', 'Зеленоград', 'Клин'],
    'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт'],
    'Тюменская область' => ['Тюмень', 'Тобольск', 'Ишим', 'Ялуторовск'],
    'Свердловская область' => ['Екатеринбург', 'Нижний Тагил', 'Каменск-Уральский'],
];

$translitMap = [
    'а' => 'a',  'б' => 'b',  'в' => 'v',  'г' => 'g',  'д' => 'd',
    'е' => 'e',  'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',  'и' => 'i',
    'й' => 'y',  'к' => 'k',  'л' => 'l',  'м' => 'm',  'н' => 'n',
    'о' => 'o',  'п' => 'p',  'р' => 'r',  'с' => 's',  'т' => 't',
    'у' => 'u',  'ф' => 'f',  'х' => 'h',  'ц' => 'ts', 'ч' => 'ch',
    'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y',  'ь' => '',
    'э' => 'e',  'ю' => 'yu', 'я' => 'ya',
];

function transliterate($text, $map)
{
    $result = '';
    $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($chars as $char) {
        $lower = mb_strtolower($char);
        if (isset($map[$lower])) {
            $latin = $map[$lower];
            if ($char !== $lower) {
                $latin = mb_strtoupper(mb_substr($latin, 0, 1)) . mb_substr($latin, 1);
            }
            $result .= $latin;
        } else {
            $result .= $char;
        }
    }

    return $result;
}

$translitExamples = [
    'Привет, мир!',
    'Тюменский государственный университет',
    'Щука, ёж и язь',
];

$menu = [
    ['title' => 'Главная', 'url' => '#'],
    [
        'title' => 'Каталог',
        'url' => '#',
        'children' => [
            ['title' => 'Книги', 'url' => '#'],
            ['title' => 'Фильмы', 'url' => '#'],
            [
                'title' => 'Музыка',
                'url' => '#',
                'children' => [
                    ['title' => 'Рок', 'url' => '#'],
                    ['title' => 'Джаз', 'url' => '#'],
                ],
            ],
        ],
    ],
    [
        'title' => 'О компании',
        'url' => '#',
        'children' => [
            ['title' => 'История', 'url' => '#'],
            ['title' => 'Команда', 'url' => '#'],
        ],
    ],
    ['title' => 'Контакты', 'url' => '#'],
];

function renderMenu($items)
{
    echo '<ul>';
    foreach ($items as $item) {
        echo '<li>';
        echo '<a href="' . $item['url'] . '">' . $item['title'] . '</a>';
        if (!empty($item['children'])) {
            renderMenu($item['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
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
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="/" class="logo">lesson 18</a>
        </div>
    </header>

    <main class="container">
        <section class="task">
            <h2>Задание 1</h2>
            <?php foreach (printNumbers() as $line): ?>
                <p><?= $line ?></p>
            <?php endforeach; ?>
        </section>

        <section class="task">
            <h2>Задание 2</h2>
            <?php foreach ($regions as $region => $cities): ?>
                <p><?= $region ?>:</p>
                <p><?= implode(', ', $cities) ?>.</p>
            <?php endforeach; ?>
        </section>

        <section class="task">
            <h2>Задание 3</h2>
            <?php foreach ($translitExamples as $text): ?>
                <p><?= $text ?> → <?= transliterate($text, $translitMap) ?></p>
            <?php endforeach; ?>
        </section>

        <section class="task">
            <h2>Задание 4</h2>
            <nav class="site-menu">
                <?php renderMenu($menu); ?>
            </nav>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>
