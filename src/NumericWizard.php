<?php
namespace BelKoD\DataWizard;

use BelKoD\DataWizard\locale\ru\NumericLocale;

abstract class NumericWizard
{
    /**
     * Возвращает склонение числа, построенное на шаблоне.
     *
     * @param float             $source Число, на основе которого строится описание.
     * @param string            $form1  Описание в единственном числе, например: 1 работа.
     * @param string            $form2  Описание во множественном числе, например: 2 работы.
     * @param string            $form3  Описание во множественном числе, например: 5 работ.
     * @param string|null       $template Шаблон, по которому объединяется число и описание.
     *                              Шаблон строится на основании функции sprintf().
     *                              Первым аргументом идет число, вторым - описание.
     *                              Используемый шаблон: '%2$s'.
     *                              Пример шаблона для объединения: '%1$d %2$s'.
     *
     * @return string
     *
     * @see sprintf
     *
     * @example echo NumericWizard::plural(2.5, 'работа', 'работы', 'работ'); // выведет "работы"
     * @example echo NumericWizard::plural(2.5, 'работа', 'работы', 'работ', '%1$s %2$s'); // выведет "2.5 работы"
     * @example echo NumericWizard::plural(3, NumericLocale::NUMERICWIZARD_L[0], NumericLocale::NUMERICWIZARD_L[1],
     *          NumericLocale::NUMERICWIZARD_L[2], '%1$s %2$s'); // выведет "3 литра"
     */
    public static function morph(float $source, string $form1, string $form2, string $form3, string $template = '%2$s'): string
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

	/**
	 *  Возвращает склонение числа, построенное на шаблоне.
	 *
	 * @param   float  $source          Число, на основе которого строится описание.
	 * @param   array  $form            Массив склонений ['работа', 'работы', 'работ'].
	 * @param   string|null  $template  Шаблон, по которому объединяется число и описание.
	 *                              Шаблон строится на основании функции sprintf().
	 *                              Первым аргументом идет число, вторым - описание.
	 *                              Используемый шаблон: '%2$s'.
	 *                              Пример шаблона для объединения: '%1$d %2$s'.
	 *
	 * @return string
	 */
	public static function morph_arr(float $source, array $form, string $template = '%2$s'): string
	{
		return self::morph($source, $form[0], $form[1], $form[2], $template);
	}

	/**
	 * Возвращает склонение числа, построенное на шаблоне. Устаревший метод.
	 *
	 * @param   float   $source
	 * @param   string  $form1
	 * @param   string  $form2
	 * @param   string  $form3
	 * @param   string|null  $template
	 *
	 * @return string
	 *
	 * @deprecated Use NumericWizard::morph
	 * @see        NumericWizard::morph
	 */
	public static function plural(float $source, string $form1, string $form2, string $form3, string $template = '%2$s'): string
	{
		return self::morph($source, $form1, $form2, $form3, $template);
	}

	/**
	 * Преобразует целое число в римскую запись.
	 *
	 * @param int       $source Число для преобразования
	 * @return string           Римская запись числа
	 *
	 * @example echo NumericWizard::rome(2022); // выведет "MMXXII"
	 */
	public static function rome(int $source): string
	{
		if($source <= 0)
			return '';

		$thousands = (int)($source/1000);
		$source -= $thousands*1000;
		$result = str_repeat("M",$thousands);
		$table = [
			900=>"CM",
			500=>"D",
			400=>"CD",
			100=>"C",
			90=>"XC",
			50=>"L",
			40=>"XL",
			10=>"X",
			9=>"IX",
			5=>"V",
			4=>"IV",
			1=>"I"
		];
		while($source) {
			foreach($table as $part => $fragment) if($part <= $source) break;
			$amount = (int)($source / $part);
			$source -= $part * $amount;
			$result .= str_repeat($fragment, $amount);
		}
		return $result ?: '';
	}


	/**
	 * Преобразует число в строковое представление.
	 *
	 * @param   int  $value Число для преобразования в строку
	 *
	 * @return string
	 *
	 * @example echo NumericWizard::str(214503); // выведет "двести четырнадцать тысяч пятьсот три"
	 */
	public static function str(int $value): string
	{
		/**
		 * $result = str_pad($value, 12, '0', STR_PAD_LEFT);
		 * Например, если $summa = 214503, то $result = '000000214503'
		 * Далее эта числовая строка бьется на триады.
		 * '000' - миллиарды, '000' - миллионы, '214' - тысячи, '503'
		 * Ниже используем эти триады для формирования строки.
		 *
		 * Результат будет выглядеть так: "двести четырнадцать тысяч пятьсот три".
		 */

		$int_val = str_pad($value, 12, '0', STR_PAD_LEFT);
		$out = [];

		if(intval($int_val) > 0)
		{
			/**
			 * Используем NumberFormatter, если в PHP включен модуль ext-intl.
			 */
			if(class_exists('NumberFormatter'))
			{
				$formatter = new \NumberFormatter(NumericLocale::LOCALE, \NumberFormatter::SPELLOUT);
				$out[] = $formatter->format(intval($int_val));
			}
			else
			{
				/**
				 * Текстовое сопоставление степеней тысячи и склонения единиц
				 */
				$units = [
					[NumericLocale::NUMERICWIZARD_UNIT2, NumericLocale::NUMERICWIZARD_TEN1],
					[NumericLocale::NUMERICWIZARD_UNIT1, NumericLocale::NUMERICWIZARD_TEN1],
					[NumericLocale::NUMERICWIZARD_UNIT0, NumericLocale::NUMERICWIZARD_TEN0],
				];
				/**
				 * Разбиваем на триады
				 */
				foreach (str_split($int_val, 3) as $unitKey => $triad)
				{
					/**
					 * Триады типа "000" пропускаем
					 */
					if (!intval($triad))
					{
						continue;
					}
					/**
					 * Определяем степень тысячи и склонение.
					 */
					$gender = $units[$unitKey] ?? $units[sizeof($units) - 1];
					/**
					 * Разделяем триаду на составные части
					 * "214" -> "2", "1", "4"
					 */
					list($i1, $i2, $i3) = array_map('intval', str_split($triad, 1));
					/**
					 * Выводим сотни (100-900). Если сотен̆ нет, то ничего не выводим
					 * В нашем случае это "двести", т.к. первое число в триаде - 2.
					 */
					$out[] = NumericLocale::NUMERICWIZARD_HUNDREDS[$i1];
					if ($i2 > 1)
					{
						/**
						 * Если второе число в триаде больше 1, то выводим "двадцать",
						 * "тридцать" и т.д. в зависимости от числа.
						 * И добавляем склонение единиц.
						 * Например, "тридцать четыре"
						 */
						$out[] = NumericLocale::NUMERICWIZARD_TENS[$i2] . ' ' . $gender[1][$i3];
					}
					else
					{
						/**
						 * В нашей триаде "214" второе число - 1 и третье число - 4.
						 * Т.к. 1 > 0, то выводим "четырнадцать" на основании третьего числа (4).
						 * Если бы второе число было 0, то выводим единицы - "четыре".
						 */
						$out[] = $i2 > 0 ? NumericLocale::NUMERICWIZARD_TWENTY[$i3] : $gender[1][$i3];
					}
					/**
					 * Определяем степень тысячи и формируем ее название.
					 * В нашем случае это "тысяч", т.к. "214 тысяч".
					 */
					if ($unitKey < sizeof($units)) $out[] = self::morph_arr($triad, $gender[0]);
				}
			}
		}
		else
		{
			$out[] = NumericLocale::NUMERICWIZARD_ZERO;
		}


		/**
		 * Объединяем все вместе.
		 * Исходные данные:
		 * <pre>array(7) {
		 * [0]=>
		 * string(12) "двести"
		 * [1]=>
		 * string(24) "четырнадцать"
		 * [2]=>
		 * string(10) "тысяч"
		 * [3]=>
		 * string(14) "пятьсот"
		 * [4]=>
		 * string(6) "три"
		 * }</pre>
		 */
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}

	/**
	 * Преобразует цену в строковое представление.
	 *
	 * @param   float  $summa   Число для преобразования в строку
	 * @param   bool   $digital Десятичная часть числа цифрами или прописью, True - цифрами, False - прописью
	 *
	 * @return string

	 * @example echo NumericWizard::price(214503.78); // выведет "двести четырнадцать тысяч пятьсот три рубля 78 копеек"
	 * @example echo NumericWizard::price(214503.78, false); // выведет "двести четырнадцать тысяч пятьсот три рубля семьдесят восемь копеек"
	 */
	public static function price(float $summa, bool $digital = true): string
	{
		$summa = preg_replace('/^([0-9]+(?:\.[0-9]{1,2})?).*/', '$1', $summa);
		list($int_val, $dec_val) = explode('.', sprintf("%015.2f", $summa));

		$out = [];
		$out[] = self::str(intval($int_val));
		/**
		 * Добавляем склонение денежных единиц, например "рубль", "рубля", "рублей"
		 */
		$out[] = self::morph(intval($int_val), NumericLocale::NUMERICWIZARD_MONEY1[0], NumericLocale::NUMERICWIZARD_MONEY1[1],
			NumericLocale::NUMERICWIZARD_MONEY1[2], '%2$s');
		if($digital)
			$out[] = $dec_val;
		else
			$out[] = self::str(intval($dec_val));
		/**
		 * Добавляем склонение денежных единиц, например "копейка", "копейки", "копеек"
		 */
		$out[] = self::morph($dec_val, NumericLocale::NUMERICWIZARD_MONEY0[0], NumericLocale::NUMERICWIZARD_MONEY0[1],
			NumericLocale::NUMERICWIZARD_MONEY0[2], '%2$s');

		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}
}