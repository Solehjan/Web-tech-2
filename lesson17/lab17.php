<?php
// Лабораторная работа 17
$pageTitle = "Лабораторная работа 17";
$a = 5;
$b = -3;

// Задание 1: проверка знаков
if ($a >= 0 && $b >= 0) {
    $result1 = $a - $b;
    $text1 = "разность";
} elseif ($a < 0 && $b < 0) {
    $result1 = $a * $b;
    $text1 = "произведение";
} else {
    $result1 = $a + $b;
    $text1 = "сумма";
}

// Задание 2: switch от 0 до 15
$num = rand(0, 15);
$output = "";
switch ($num) {
    case 0: $output .= "0, ";
    case 1: $output .= "1, ";
    case 2: $output .= "2, ";
    case 3: $output .= "3, ";
    case 4: $output .= "4, ";
    case 5: $output .= "5, ";
    case 6: $output .= "6, ";
    case 7: $output .= "7, ";
    case 8: $output .= "8, ";
    case 9: $output .= "9, ";
    case 10: $output .= "10, ";
    case 11: $output .= "11, ";
    case 12: $output .= "12, ";
    case 13: $output .= "13, ";
    case 14: $output .= "14, ";
    case 15: $output .= "15";
}
?>
<!DOCTYPE html>
<html>
<head><title><?= $pageTitle ?></title><meta charset="UTF-8"></head>
<body>
<h1>Лабораторная 17</h1>
<p>a=$a, b=$b → <?= $text1 ?> = <?= $result1 ?></p>
<p>Числа от <?= $num ?> до 15: <?= $output ?></p>
</body>
</html>
