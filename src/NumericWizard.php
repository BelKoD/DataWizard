<?php

namespace BelKoD\DataWizard;

abstract class NumericWizard
{
    /**
     * Возвращает склонение числа, построенное на шаблоне.
     *
     * @param int       $source Число, на основе которого строится описание.
     * @param string    $form1 Описание в единственном числе, например: 1 работа.
     * @param string    $form2 Описание во множественном числе, например: 2 работы.
     * @param string    $form3 Описание во множественном числе, например: 5 работ.
     * @param string|null      $template Шаблон, по которому объединяется число и описание.
     *                          Шаблон строится на основании функции sprintf().
     *                          Первым аргументом идет число, вторым - описание.
     *                          Используемый шаблон: '%2$s'.
     *                          Пример шаблона для объединения: '%1$d %2$s'.
     * @see sprintf
     * @return string
     */
    public static function plural(int $source, string $form1, string $form2, string $form3, string $template = null): string
    {
        if(empty($template))
            $template = '%2$s';
        $n = abs($source) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return sprintf($template, $source, $form3);
        if ($n1 > 1 && $n1 < 5) return sprintf($template, $source, $form2);
        if ($n1 == 1) return sprintf($template, $source, $form1);
        return sprintf($template, $source, $form3);
    }
}