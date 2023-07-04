<?php

namespace BelKoD\DataWizard;

abstract class DateWizard
{
    /**
     * Преобразует временную метку в строку с заданным шаблоном.
     *
     * @param int $timestamp    Временная метка
     * @param string $format    Шаблон, по которому формируется дата
     * @param int $case         Индекс склонения месяца
     * @param bool $upper       True - название месяца с прописной буквы (Июнь), False - со строчной буквы (июнь)
     * @return string
     */
    public static function rdate(int $timestamp, string $format = 'd F Yг.', int $case = 0, bool $upper = true): string
    {
        if($timestamp == 0)
            return '';

        static $months = [
            'Январ,ь,я,е,ю,ём,е',
            'Феврал,ь,я,е,ю,ём,е',
            'Март, ,а,е,у,ом,е',
            'Апрел,ь,я,е,ю,ем,е',
            'Ма,й,я,е,ю,ем,е',
            'Июн,ь,я,е,ю,ем,е',
            'Июл,ь,я,е,ю,ем,е',
            'Август, ,а,е,у,ом,е',
            'Сентябр,ь,я,е,ю,ём,е',
            'Октябр,ь,я,е,ю,ём,е',
            'Ноябр,ь,я,е,ю,ём,е',
            'Декабр,ь,я,е,ю,ём,е'
        ];

        $loc = [];
        foreach ($months as $monthLocale) {
            $cases = explode(',', $monthLocale);
            $base = array_shift($cases);
            $cases = array_map('trim', $cases);

            $loc[] = [
                'base' => $base,
                'cases' => $cases,
            ];
        }

        $m = (int)date('n', $timestamp) - 1;

        $F = $loc[$m]['base'] . $loc[$m]['cases'][$case];
        $F = $upper ? $F : mb_strtolower($F, 'UTF-8');
        $format = strtr($format, array(
            'F' => $F,
            'M' => mb_substr($F, 0, 3),
        ));

        return date($format, $timestamp);
    }

    /**
     * Показывает сколько прошло времени от заданного значения $timestamp.
     *
     * Если прошло 0...55 минут: X минут назад,
     * Если сегодня: сегодня в 17:40,
     * Если вчера: вчера в 17:40,
     * Если этот год: 21 Апреля в 17:40,
     * Если прошлый и менее год: 17:40 21.04.2014
     *
     * @param int $timestamp    Временная метка
     * @uses plural()
     * @uses rdate()
     * @return string
     */
    public static function passed_time(int $timestamp): string
    {
        $time = time();
        $tm = date('H:i:s', $timestamp);
        $d = date('d', $timestamp);
        $m = date('m', $timestamp);
        $y = date('Y', $timestamp);
        $last = round(($time - $timestamp)/60);
        if( $last < 55 ) return $last.' '.self::plural($last, 'минута', 'минуты', 'минут').' назад';
        elseif($d.$m.$y == date('dmY',$time)) return "сегодня в $tm";
        elseif($d.$m.$y == date('dmY', strtotime('-1 day'))) return "вчера в $tm";
        elseif($y == date('Y',$time)) return self::rdate($timestamp, 'd F', 1, true).' в '.$tm;
        else return $tm.' '.$d.'.'.$m.'.'.$y;
    }

    /**
     * Возвращает склонение числа.
     *
     * @param int       $source Число, на основе которого строится описание.
     * @param string    $form1 Описание в единственном числе, например: 1 работа.
     * @param string    $form2 Описание во множественном числе, например: 2 работы.
     * @param string    $form3 Описание во множественном числе, например: 5 работ.
     * @return string
     */
    protected static function plural(int $source, string $form1, string $form2, string $form3): string
    {
        $n = abs($source) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form3;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form3;
    }
}