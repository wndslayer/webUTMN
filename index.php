<?php

$config = require __DIR__ . '/config.php';
$db = $config['db'];

$dsn = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";
$pdo = new PDO($dsn, $db['username'], $db['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$rows = $pdo->query('SELECT id, parent_id, title, is_open FROM categories ORDER BY parent_id, sort_order, id')->fetchAll();

function buildTree(array $rows, $parentId = null)
{
    $tree = [];
    foreach ($rows as $row) {
        if ((int) $row['parent_id'] === (int) $parentId || ($row['parent_id'] === null && $parentId === null)) {
            $children = buildTree($rows, $row['id']);
            $tree[] = [
                'title'    => $row['title'],
                'open'     => (bool) $row['is_open'],
                'children' => $children,
            ];
        }
    }
    return $tree;
}

function renderTree(array $nodes)
{
    $html = '';
    foreach ($nodes as $node) {
        $hasChildren = !empty($node['children']);
        $isOpen = $node['open'] && $hasChildren;

        $classes = 'list-item' . ($isOpen ? ' list-item_open' : '');
        $parentAttr = $hasChildren ? ' data-parent' : '';

        $html .= '<div class="' . $classes . '"' . $parentAttr . '>';
        $html .=   '<div class="list-item__inner">';

        if ($hasChildren) {
            $html .= '<button class="list-item__toggle" type="button" data-toggle aria-label="toggle">';
            $html .=   '<img class="list-item__arrow" src="img/chevron-down.png" alt="">';
            $html .= '</button>';
        } else {
            $html .= '<span class="list-item__arrow-spacer"></span>';
        }

        $html .=   '<img class="list-item__folder" src="img/folder.png" alt="folder">';
        $html .=   '<span>' . htmlspecialchars($node['title'], ENT_QUOTES, 'UTF-8') . '</span>';
        $html .=   '</div>';

        if ($hasChildren) {
            $html .= '<div class="list-item__items">' . renderTree($node['children']) . '</div>';
        }

        $html .= '</div>';
    }
    return $html;
}

$tree = buildTree($rows);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Меню каталога</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="list-items" id="list-items">
        <?= renderTree($tree) ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
