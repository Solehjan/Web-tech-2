<?php
// ==============================================
// ЗАДАНИЕ 4* и 5*: Логирование запросов
// ==============================================
function logRequest() {
    $logDir = __DIR__ . '/logs/';
    $mainLog = $logDir . 'log.txt';
    
    // Создаём папку для логов, если её нет
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    // Подготавливаем данные для записи
    $logData = date('Y-m-d H:i:s') . ' - ' . $_SERVER['REMOTE_ADDR'] . ' - ' . $_SERVER['REQUEST_URI'] . PHP_EOL;
    
    // Проверяем, существует ли основной лог-файл
    if (file_exists($mainLog)) {
        // Считаем количество строк в файле
        $lines = file($mainLog, FILE_IGNORE_NEW_LINES);
        $lineCount = count($lines);
        
        // Если 10 или более записей — архивируем
        if ($lineCount >= 10) {
            // Находим следующий номер архива
            $archiveNum = 1;
            while (file_exists($logDir . 'log' . $archiveNum . '.txt')) {
                $archiveNum++;
            }
            // Переименовываем log.txt в logN.txt
            rename($mainLog, $logDir . 'log' . $archiveNum . '.txt');
        }
    }
    
    // Записываем новую запись в log.txt
    file_put_contents($mainLog, $logData, FILE_APPEND | LOCK_EX);
}

// Вызываем логирование при каждом запросе
logRequest();

// ==============================================
// ЗАДАНИЕ 3: Обработка загрузки файла
// ==============================================
$uploadMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $uploadDir = __DIR__ . '/uploads/';
    $thumbsDir = __DIR__ . '/thumbs/';
    
    // Создаём папки, если их нет
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (!is_dir($thumbsDir)) {
        mkdir($thumbsDir, 0777, true);
    }
    
    $file = $_FILES['image'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Проверка на допустимые типы
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($fileType, $allowedTypes)) {
        $uploadMessage = '❌ Ошибка: разрешены только JPG, PNG, GIF, WEBP';
    } elseif ($fileSize > 5 * 1024 * 1024) { // 5 MB
        $uploadMessage = '❌ Ошибка: размер файла не должен превышать 5 МБ';
    } else {
        // Генерируем уникальное имя файла
        $newFileName = time() . '_' . uniqid() . '.' . $fileType;
        $uploadPath = $uploadDir . $newFileName;
        $thumbPath = $thumbsDir . $newFileName;
        
        // Перемещаем загруженный файл
        if (move_uploaded_file($fileTmp, $uploadPath)) {
            // Создаём миниатюру (ресайз)
            createThumbnail($uploadPath, $thumbPath, 200, 150);
            $uploadMessage = '✅ Файл успешно загружен!';
            
            // Перезагружаем страницу, чтобы увидеть новое изображение
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $uploadMessage = '❌ Ошибка при загрузке файла';
        }
    }
}

// Функция создания миниатюры
function createThumbnail($sourcePath, $destPath, $width, $height) {
    list($origWidth, $origHeight, $type) = getimagesize($sourcePath);
    
    // Определяем соотношение сторон
    $ratio = $origWidth / $origHeight;
    if ($width / $height > $ratio) {
        $width = $height * $ratio;
    } else {
        $height = $width / $ratio;
    }
    
    // Создаём пустое изображение для миниатюры
    $thumb = imagecreatetruecolor($width, $height);
    
    // Загружаем исходное изображение в зависимости от типа
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
            imagejpeg($thumb, $destPath, 85);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
            imagepng($thumb, $destPath, 8);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
            imagegif($thumb, $destPath);
            break;
        case IMAGETYPE_WEBP:
            if (function_exists('imagecreatefromwebp')) {
                $source = imagecreatefromwebp($sourcePath);
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
                imagewebp($thumb, $destPath, 85);
            }
            break;
    }
    
    imagedestroy($source);
    imagedestroy($thumb);
}

// ==============================================
// ЗАДАНИЕ 1 и 2: Функция построения галереи
// ==============================================
function buildGallery($thumbsDir, $uploadsDir) {
    $images = [];
    
    // Сканируем папку с миниатюрами
    if (is_dir($thumbsDir)) {
        $files = scandir($thumbsDir);
        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($extension, $allowed)) {
                $images[] = $file;
            }
        }
    }
    
    if (empty($images)) {
        echo '<p style="text-align:center;">В галерее пока нет изображений. Загрузите первое!</p>';
        return;
    }
    
    // Сортируем изображения (новые сверху)
    rsort($images);
    
    echo '<div class="gallery">';
    foreach ($images as $image) {
        $thumbPath = 'thumbs/' . $image;
        $fullPath = 'uploads/' . $image;
        echo '<div class="gallery-item">';
        echo '<a href="' . $fullPath . '" target="_blank">';
        echo '<img src="' . $thumbPath . '" alt="Галерея" width="200" style="height:150px; object-fit:cover;">';
        echo '</a>';
        echo '</div>';
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Фотогалерея</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        /* Стили для формы загрузки */
        .upload-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .upload-form input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-right: 10px;
        }
        
        .upload-form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .upload-form button:hover {
            background: #218838;
        }
        
        .message {
            text-align: center;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Стили для галереи */
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        
        .gallery-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .gallery-item:hover {
            transform: scale(1.03);
        }
        
        .gallery-item img {
            border-radius: 8px;
            display: block;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📸 Фотогалерея</h1>
    
    <!-- ЗАДАНИЕ 3: Форма загрузки нового изображения -->
    <div class="upload-form">
        <h3>📤 Загрузить новое изображение</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
            <button type="submit">Загрузить</button>
        </form>
        <?php if ($uploadMessage): ?>
            <div class="message <?php echo strpos($uploadMessage, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo $uploadMessage; ?>
            </div>
        <?php endif; ?>
        <p style="font-size: 12px; color: #666; margin-top: 10px;">
            Максимальный размер: 5 МБ. Поддерживаемые форматы: JPG, PNG, GIF, WEBP
        </p>
    </div>
    
    <!-- ЗАДАНИЕ 1 и 2: Галерея -->
    <?php buildGallery(__DIR__ . '/thumbs/', __DIR__ . '/uploads/'); ?>
    
    <div class="footer">
        © Фотогалерея | Всего изображений: 
        <?php 
            $files = glob(__DIR__ . '/thumbs/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            echo count($files);
        ?>
    </div>
</div>
</body>
</html>
