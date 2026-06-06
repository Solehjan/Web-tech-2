<?php
// Блок переменных в начале страницы
$pageTitle = "Моя первая PHP страница";
$heading = "Добро пожаловать на сайт!";
$currentYear = date("Y");

// Функция для форматирования времени с правильными склонениями
function formatTimeWithDeclension() {
    $hour = date("G"); // 0-23
    $minute = date("i"); // 0-59
    
    // Склонение для часов
    $hourStr = $hour . " ";
    $hourRemainder = $hour % 10;
    $hourRemainder100 = $hour % 100;
    
    if ($hourRemainder100 >= 11 && $hourRemainder100 <= 14) {
        $hourStr .= "часов";
    } else {
        if ($hourRemainder == 1) {
            $hourStr .= "час";
        } elseif ($hourRemainder >= 2 && $hourRemainder <= 4) {
            $hourStr .= "часа";
        } else {
            $hourStr .= "часов";
        }
    }
    
    // Склонение для минут
    $minuteStr = $minute . " ";
    $minuteRemainder = $minute % 10;
    $minuteRemainder100 = $minute % 100;
    
    if ($minuteRemainder100 >= 11 && $minuteRemainder100 <= 14) {
        $minuteStr .= "минут";
    } else {
        if ($minuteRemainder == 1) {
            $minuteStr .= "минута";
        } elseif ($minuteRemainder >= 2 && $minuteRemainder <= 4) {
            $minuteStr .= "минуты";
        } else {
            $minuteStr .= "минут";
        }
    }
    
    return $hourStr . " " . $minuteStr;
}

// Получаем отформатированное время
$currentTimeFormatted = formatTimeWithDeclension();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .time {
            font-size: 24px;
            color: #0066cc;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $heading; ?></h1>
        
        <div class="time">
            ⏰ Текущее время: <?php echo $currentTimeFormatted; ?>
        </div>
        
        <div class="footer">
            © <?php echo $currentYear; ?> Мой первый PHP-сайт
        </div>
    </div>
</body>
</html>