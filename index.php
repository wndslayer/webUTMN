<?php
$pageTitle = 'lesson19';

$photosDir = __DIR__ . '/photos';
$thumbsDir = $photosDir . '/thumbs';
$photosUrl = 'photos';
$thumbsUrl = 'photos/thumbs';

$maxFileSize = 5 * 1024 * 1024;
$thumbWidth = 300;
$originalMaxWidth = 1600;
$allowedTypes = [
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG  => 'png',
    IMAGETYPE_GIF  => 'gif',
    IMAGETYPE_WEBP => 'webp',
];

if (!is_dir($thumbsDir)) {
    mkdir($thumbsDir, 0755, true);
}

function resizeImage($sourcePath, $destPath, $maxWidth, $imageType)
{
    [$width, $height] = getimagesize($sourcePath);

    if ($width <= $maxWidth) {
        $newWidth = $width;
        $newHeight = $height;
    } else {
        $newWidth = $maxWidth;
        $newHeight = (int) round($height * $maxWidth / $width);
    }

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $src = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    $dst = imagecreatetruecolor($newWidth, $newHeight);

    if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst, $destPath, 88);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst, $destPath, 6);
            break;
        case IMAGETYPE_GIF:
            imagegif($dst, $destPath);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($dst, $destPath, 85);
            break;
    }

    imagedestroy($src);
    imagedestroy($dst);
    return true;
}

function getGalleryFiles($dir)
{
    if (!is_dir($dir)) {
        return [];
    }

    $files = scandir($dir);
    $result = [];
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        if (is_dir($dir . '/' . $file)) continue;

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $extensions, true)) {
            $result[] = $file;
        }
    }

    sort($result);
    return $result;
}

$uploadError = null;
$uploadSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];

    $errorMessages = [
        UPLOAD_ERR_INI_SIZE   => 'Файл превышает лимит сервера (upload_max_filesize).',
        UPLOAD_ERR_FORM_SIZE  => 'Файл превышает лимит, заданный формой.',
        UPLOAD_ERR_PARTIAL    => 'Файл загружен не полностью.',
        UPLOAD_ERR_NO_FILE    => 'Файл не выбран.',
        UPLOAD_ERR_NO_TMP_DIR => 'На сервере не настроена временная папка.',
        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
        UPLOAD_ERR_EXTENSION  => 'Загрузка остановлена расширением PHP.',
    ];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadError = $errorMessages[$file['error']] ?? 'Неизвестная ошибка загрузки.';
    } elseif ($file['size'] > $maxFileSize) {
        $uploadError = 'Файл слишком большой. Максимум ' . ($maxFileSize / 1024 / 1024) . ' МБ.';
    } else {
        $info = getimagesize($file['tmp_name']);
        if ($info === false || !isset($allowedTypes[$info[2]])) {
            $uploadError = 'Допустимые форматы: JPG, PNG, GIF, WEBP.';
        } else {
            $imageType = $info[2];
            $ext = $allowedTypes[$imageType];
            $fileName = uniqid('img_', true) . '.' . $ext;
            $destPath = $photosDir . '/' . $fileName;
            $thumbPath = $thumbsDir . '/' . $fileName;

            if (resizeImage($file['tmp_name'], $destPath, $originalMaxWidth, $imageType)
                && resizeImage($file['tmp_name'], $thumbPath, $thumbWidth, $imageType)) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?uploaded=1');
                exit;
            } else {
                $uploadError = 'Не удалось обработать изображение.';
            }
        }
    }
}

if (isset($_GET['uploaded']) && !$uploadError) {
    $uploadSuccess = true;
}

$photos = getGalleryFiles($photosDir);
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
            <a href="/" class="logo">lesson 19</a>
        </div>
    </header>

    <main class="container">
        <section class="task">
            <h2>Загрузить изображение</h2>

            <?php if ($uploadError): ?>
                <p class="alert alert-error"><?= $uploadError ?></p>
            <?php endif; ?>

            <?php if ($uploadSuccess): ?>
                <p class="alert alert-ok">Изображение загружено.</p>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" required>
                <button type="submit">Загрузить</button>
            </form>
            <p class="muted">JPG, PNG, GIF или WEBP, до 5 МБ.</p>
        </section>

        <section class="task">
            <h2>Галерея</h2>
            <?php if (empty($photos)): ?>
                <p class="muted">Пока пусто. Загрузите первое изображение через форму выше.</p>
            <?php else: ?>
                <div class="gallery">
                    <?php foreach ($photos as $photo): ?>
                        <a href="<?= $photosUrl . '/' . $photo ?>" target="_blank" rel="noopener">
                            <img src="<?= $thumbsUrl . '/' . $photo ?>" alt="<?= $photo ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>
