<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа 18</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
        }
        .task {
            background: #f9f9f9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .result {
            background: #e8f4f8;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .menu {
            background: #2c3e50;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }
        .menu li {
            position: relative;
        }
        .menu a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .menu li:hover > a {
            background: #667eea;
            border-radius: 5px;
        }
        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #34495e;
            border-radius: 5px;
            min-width: 180px;
            flex-direction: column;
            gap: 0;
        }
        .menu li:hover .submenu {
            display: flex;
        }
        .submenu li a {
            padding: 8px 15px;
        }
        .submenu li a:hover {
            background: #667eea;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Лабораторная работа №18</h1>

    <!-- ========================================== -->
    <!-- ЗАДАНИЕ 1: do...while от 0 до 10 -->
    <!-- ========================================== -->
    <div class="task">
        <h2>1. Цикл do...while (чётные/нечётные)</h2>
        <div class="result">
            <?php
            $i = 0;
            do {
                if ($i == 0) {
                    echo "$i – это ноль.<br>";
                } elseif ($i % 2 == 0) {
                    echo "$i – чётное число.<br>";
                } else {
                    echo "$i – нечётное число.<br>";
                }
                $i++;
            } while ($i <= 10);
            ?>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ЗАДАНИЕ 2: Массив областей и городов -->
    <!-- ========================================== -->
    <div class="task">
        <h2>2. Области и города</h2>
        <div class="result">
            <?php
            $regions = [
                'Московская область' => ['Москва', 'Зеленоград', 'Клин', 'Химки', 'Подольск'],
                'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт', 'Гатчина'],
                'Рязанская область' => ['Рязань', 'Касимов', 'Скопин', 'Сасово', 'Ряжск'],
                'Нижегородская область' => ['Нижний Новгород', 'Арзамас', 'Дзержинск', 'Бор', 'Кстово']
            ];
            
            foreach ($regions as $region => $cities) {
                echo "<strong>$region:</strong><br>";
                echo implode(', ', $cities) . ".<br><br>";
            }
            ?>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ЗАДАНИЕ 2*: Города на букву "К" -->
    <!-- ========================================== -->
    <div class="task">
        <h2>2*. Города, начинающиеся с буквы "К"</h2>
        <div class="result">
            <?php
            foreach ($regions as $region => $cities) {
                $filteredCities = array_filter($cities, function($city) {
                    return mb_substr($city, 0, 1) === 'К';
                });
                if (!empty($filteredCities)) {
                    echo "<strong>$region:</strong><br>";
                    echo implode(', ', $filteredCities) . ".<br><br>";
                }
            }
            ?>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ЗАДАНИЕ 3: Транслитерация -->
    <!-- ========================================== -->
    <div class="task">
        <h2>3. Транслитерация строки</h2>
        <div class="result">
            <?php
            $translitMap = [
                'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
                'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
                'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
                'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
                'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
                'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
                'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
            ];
            
            function transliterate($string, $map) {
                $string = mb_strtolower($string);
                $result = '';
                for ($i = 0; $i < mb_strlen($string); $i++) {
                    $char = mb_substr($string, $i, 1);
                    $result .= isset($map[$char]) ? $map[$char] : $char;
                }
                return $result;
            }
            
            $testString = 'Привет, мир! Это лабораторная работа номер 18.';
            echo "<strong>Исходная строка:</strong><br>$testString<br><br>";
            echo "<strong>Транслитерация:</strong><br>" . transliterate($testString, $translitMap);
            ?>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ЗАДАНИЕ 4: Динамическое меню (PHP) -->
    <!-- ========================================== -->
    <div class="task">
        <h2>4. Динамическое меню с подменю</h2>
        <div class="result">
            <div class="menu">
                <?php
                $menuItems = [
                    'Главная' => '#',
                    'О нас' => '#',
                    'Услуги' => [
                        'Веб-разработка' => '#',
                        'Дизайн' => '#',
                        'SEO-продвижение' => '#'
                    ],
                    'Контакты' => '#',
                    'Блог' => [
                        'Новости' => '#',
                        'Статьи' => '#',
                        'Обзоры' => '#'
                    ]
                ];
                
                function renderMenu($items) {
                    echo '<ul>';
                    foreach ($items as $key => $value) {
                        if (is_array($value)) {
                            echo '<li><a href="#">' . $key . '</a>';
                            echo '<div class="submenu">';
                            renderMenu($value);
                            echo '</div></li>';
                        } else {
                            echo '<li><a href="' . $value . '">' . $key . '</a></li>';
                        }
                    }
                    echo '</ul>';
                }
                
                renderMenu($menuItems);
                ?>
            </div>
            <p style="margin-top: 15px; font-size: 14px; color: #666;">
                ← Наведи на пункты "Услуги" и "Блог", чтобы увидеть подменю
            </p>
        </div>
    </div>

</div>
</body>
</html>
