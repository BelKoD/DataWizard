<?php

namespace BelKoD\DataWizard\locale\ru;

abstract class DateLocale
{
	const DATEWIZARD_MONTH1 = 'Январ,ь,я,е,ю,ём,е';
	const DATEWIZARD_MONTH2 = 'Феврал,ь,я,е,ю,ём,е';
	const DATEWIZARD_MONTH3 = 'Март, ,а,е,у,ом,е';
	const DATEWIZARD_MONTH4 = 'Апрел,ь,я,е,ю,ем,е';
	const DATEWIZARD_MONTH5 = 'Ма,й,я,е,ю,ем,е';
	const DATEWIZARD_MONTH6 = 'Июн,ь,я,е,ю,ем,е';
	const DATEWIZARD_MONTH7 = 'Июл,ь,я,е,ю,ем,е';
	const DATEWIZARD_MONTH8 = 'Август, ,а,е,у,ом,е';
	const DATEWIZARD_MONTH9 = 'Сентябр,ь,я,е,ю,ём,е';
	const DATEWIZARD_MONTH10 = 'Октябр,ь,я,е,ю,ём,е';
	const DATEWIZARD_MONTH11 = 'Ноябр,ь,я,е,ю,ём,е';
	const DATEWIZARD_MONTH12 = 'Декабр,ь,я,е,ю,ём,е';
	const DATEWIZARD_TIMETOSTR_FORMAT = 'd F Yг.';
	const DATEWIZARD_PASSEDTIME_OVER = 'd F в H:i';
	const DATEWIZARD_PASSEDTIME_INYEAR = 'd F в H:i';
	const DATEWIZARD_PASSEDTIME_TODAY = 'сегодня в %1$s';
	const DATEWIZARD_PASSEDTIME_TOMORROW = 'завтра в %1$s';
	const DATEWIZARD_PASSEDTIME_YESTERDAY = 'вчера в %1$s';
	const DATEWIZARD_PASSEDTIME_LAST = '%1$d %2$s назад';
	const DATEWIZARD_PASSEDTIME_DATEFORMAT = '%1$s %2$s.%3$s.%4$s';
	/**
	 * @var string[] Содержит 'Единственное число 1 минута', 'Множественное число 2 минуты', 'Множественное число 5 минут'
	 */
	const DATEWIZARD_MINUTES = ['минута', 'минуты', 'минут'];
}