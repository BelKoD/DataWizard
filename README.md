# DataWizard
Небольшие классы-помощники для преобразования чисел или временных меток в строчный формат.

Представленные в данных классах методы были в разное время найдены в Интернете и использовались в разных проектах. 
В дальнейшем собрал их в один макет для удобства использования. 

## Использование

```
$ composer require belkod/datawizard
```

## Примеры преобразование числа в строку

### Склонение числа

```
use BelKoD\DataWizard\NumericWizard;

$value = 2.5;
echo NumericWizard::morph($value, 'литр', 'литра', 'литров');
// выведет "литра"
```
Можно использовать заготовки склонений из файла `locale/ru/NumericLocale.php`.
```
use BelKoD\DataWizard\locale\ru\NumericLocale;
use BelKoD\DataWizard\NumericWizard;

$value = 2.5;
echo NumericWizard::morph($value, NumericLocale::NUMERICWIZARD_L[0], NumericLocale::NUMERICWIZARD_L[1], NumericLocale::NUMERICWIZARD_L[2]);
// выведет "литра"
```
Чтобы вывести склонение вместе с числом, можно использовать шаблон `%1$s %2$s`.
```
echo NumericWizard::morph($value, 'литр', 'литра', 'литров', '%1$s %2$s');
// выведет "2.5 литра"
```
Сокращенный вариант использования склонения:
```
// константа NumericLocale::NUMERICWIZARD_L = ["литр", "литра", "литров"]
echo NumericWizard::morph_arr($value, NumericLocale::NUMERICWIZARD_L, '%1$s %2$s');
// выведет "2.5 литра"
```

### Арабские числа в римские

```
use BelKoD\DataWizard\NumericWizard;

$value = 2023;
echo NumericWizard::roman($value);
// выведет "MMXXIII"
```
**Обращаю внимание, если исходное число отрицательное или равно 0, то будет возвращена пустая строка.**

### Целое число в строчный формат

```
use BelKoD\DataWizard\NumericWizard;

$value = 214503;
echo NumericWizard::str($value);
// выведет "двести четырнадцать тысяч пятьсот три"
```

### Цена в строчный формат

```
use BelKoD\DataWizard\NumericWizard;

$value = 214503.78;
echo NumericWizard::price($value, true);
// выведет "двести четырнадцать тысяч пятьсот три рубля 78 копеек"

echo NumericWizard::price($value, false);
// выведет "двести четырнадцать тысяч пятьсот три рубля семьдесят восемь копеек"
```
## Примеры преобразование временной метки в строку

### Месяц в строку

```
use BelKoD\DataWizard\DateWizard;
date_default_timezone_set('Europe/Moscow');

// временная метка
$timestamp = strtotime('2022-07-25 14:30:00');

// формат вывода "день месяц год"
$format = 'd F Yг.';

// склонение месяца
$case = 0; // январь
$case = 1; // января
$case = 2; // январе
$case = 3; // январе
$case = 4; // январю
$case = 5; // январём
$case = 6; // январе

// месяц в верхнем регистре
$upper = true;

echo DateWizard::str($timestamp, $format, $case, $upper);
// выведет "25 Июля 2022г."
```

```
// месяц в нижнем регистре
$upper = false;

echo DateWizard::str($timestamp, $format, $case, $upper);
// выведет "25 июля 2022г."
```

```
// формат вывода "день мес год"
$format = 'd M Yг.';
$timestamp = strtotime('2022-07-25 14:30:00');

echo DateWizard::str($timestamp, $format, 0, false);
// выведет "25 июл 2022г."
```

### Дата в римской записи

```
use BelKoD\DataWizard\DateWizard;
date_default_timezone_set('Europe/Moscow');

// временная метка
$timestamp = strtotime('2022-07-25 14:30:00');

// формат вывода "день/месяц/год"
$template = '%1$s/%2$s/%3$s';

echo DateWizard::roman($timestamp, $template);
// выведет "XXV/VII/MMXXII"
```

### Дата и время относительно текущей даты

```
use BelKoD\DataWizard\DateWizard;
date_default_timezone_set('Europe/Moscow');
// например, текущая дата 2023-07-25 14:30:00

// временная метка
$timestamp = strtotime('2022-07-25 14:30:00');
echo DateWizard::passed($timestamp);
// выведет "14:35 14.12.2023"

$timestamp = strtotime('2023-07-25 13:30:00');
echo DateWizard::passed($timestamp);
// выведет "сегодня в 13:30"

$timestamp = strtotime('2023-07-24 13:30:00');
echo DateWizard::passed($timestamp);
// выведет "вчера в 13:30"

$timestamp = strtotime('2023-07-26 13:30:00');
echo DateWizard::passed($timestamp);
// выведет "завтра в 13:30"

$timestamp = strtotime('2023-06-26 13:30:00');
echo DateWizard::passed($timestamp);
// выведет "26 Июня в 13:30"

$timestamp = strtotime('2023-07-25 14:00:00');
echo DateWizard::passed($timestamp);
// выведет "30 минут назад"
```
