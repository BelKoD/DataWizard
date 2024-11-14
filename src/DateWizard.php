<?php
namespace BelKoD\DataWizard;

use BelKoD\DataWizard\locale\ru\DateLocale;

abstract class DateWizard
{
	private static $months = [
		DateLocale::DATEWIZARD_MONTH1,
		DateLocale::DATEWIZARD_MONTH2,
		DateLocale::DATEWIZARD_MONTH3,
		DateLocale::DATEWIZARD_MONTH4,
		DateLocale::DATEWIZARD_MONTH5,
		DateLocale::DATEWIZARD_MONTH6,
		DateLocale::DATEWIZARD_MONTH7,
		DateLocale::DATEWIZARD_MONTH8,
		DateLocale::DATEWIZARD_MONTH9,
		DateLocale::DATEWIZARD_MONTH10,
		DateLocale::DATEWIZARD_MONTH11,
		DateLocale::DATEWIZARD_MONTH12
	];

	/**
	 * Данный метод не используется и в будущих версиях может быть удалён. Используйте time_to_str.
	 * @see DateWizard::str()
	 * @param   int     $timestamp
	 * @param   string  $format
	 * @param   int     $case
	 * @param   bool    $upper
	 * @deprecated use DateWizard::str()
	 *
	 * @return string
	 */
	public static function rdate(int $timestamp, string $format = 'd F Yг.', int $case = 0, bool $upper = true): string
	{
		return self::str($timestamp, $format, $case, $upper);
	}
    /**
     * Преобразует временную метку в строку с заданным шаблоном.
     *
     * @param int $timestamp    Временная метка
     * @param string $format    Шаблон, по которому формируется дата
     * @param int $case         Индекс склонения месяца
     * @param bool $upper       True - название месяца с прописной буквы (Июнь), False - со строчной буквы (июнь)
     * @return string
     */
    public static function str(int $timestamp, string $format = DateLocale::DATEWIZARD_TIMETOSTR_FORMAT, int $case = 0, bool $upper = true): string
    {
        if($timestamp <= 0)
            return '';

        $loc = [];
        foreach (self::$months as $monthLocale) {
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
     * Преобразует временную метку в строку, где день, месяц и год представлены в римской записи.
     *
     * @param int $timestamp    Временная метка
     * @param string $template  Шаблон, по которому формируется строка (по умолчанию '%1$s/%2$s/%3$s', что соответствует формату "день/месяц/год")
     *
     * @return string           Возвращает строку, где день, месяц и год представлены в римской записи, разделенные символами, указанными в шаблоне.
     *
     * @example echo DateWizard::rome(1643723400, '%1$s-%2$s-%3$s'); // выведет "I-II-MMXXIII"
     * @uses NumericWizard::rome()
     */
	public static function rome(int $timestamp, string $template = '%1$s/%2$s/%3$s'): string
	{
		if($timestamp <= 0)
			return '';
		if(empty($template))
			$template = '%1$s/%2$s/%3$s';

		$year = NumericWizard::rome(date('Y', $timestamp));
		$month = NumericWizard::rome(date('m', $timestamp));
		$day = NumericWizard::rome(date('d', $timestamp));
		return sprintf($template, $day, $month, $year);
	}

	/**
	 * Показывает сколько прошло времени от заданного значения $timestamp.
	 *
	 * Если прошло 0...59 минут: X минут назад,
	 * Если сегодня: сегодня в 17:40,
	 * Если вчера: вчера в 17:40,
	 * Если завтра: завтра в 17:40,
	 * Если этот год: 21 Апреля в 17:40,
	 * В остальных случаях: 17:40 21.04.2014
	 *
	 * @param   int     $timestamp    Временная метка
	 * @param   string  $time_format  Шаблон, по которому формируется время (по умолчанию 'H:i')
	 * @param   string  $date_format  Шаблон, по которому формируется дата (по умолчанию '%1$s %2$s.%3$s.%4$s')
	 *                                Первый параметр это время, второй - день, третий - месяц, четвертый - год.
	 *
	 * @return string
	 * @uses NumericWizard::plural()
	 * @uses DateWizard::str()
	 */
    public static function passed(int $timestamp, string $time_format = 'H:i', string $date_format = DateLocale::DATEWIZARD_PASSEDTIME_DATEFORMAT): string
    {
	    if($timestamp <= 0)
		    return '';

        $time = time();
        $tm = date($time_format, $timestamp);
        $d = date('d', $timestamp);
        $m = date('m', $timestamp);
        $y = date('Y', $timestamp);
        $last = round(($time - $timestamp)/60);

        if( $last > 0 && $last < 59 )
			return NumericWizard::morph($last,
	        DateLocale::DATEWIZARD_MINUTES[0], DateLocale::DATEWIZARD_MINUTES[1], DateLocale::DATEWIZARD_MINUTES[2],
	        DateLocale::DATEWIZARD_PASSEDTIME_LAST);
        elseif($d.$m.$y == date('dmY',$time))
	        return sprintf(DateLocale::DATEWIZARD_PASSEDTIME_TODAY, $tm);
        elseif($d.$m.$y == date('dmY', strtotime('-1 day')))
	        return sprintf(DateLocale::DATEWIZARD_PASSEDTIME_YESTERDAY, $tm);
        elseif($d.$m.$y == date('dmY', strtotime('+1 day')))
	        return sprintf(DateLocale::DATEWIZARD_PASSEDTIME_TOMORROW, $tm);
        elseif($y == date('Y',$time))
	        return self::str($timestamp, DateLocale::DATEWIZARD_PASSEDTIME_INYEAR, 1, true);
        else return sprintf($date_format, $tm, $d, $m, $y);
    }
}