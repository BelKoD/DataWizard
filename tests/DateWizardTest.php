<?php

namespace BelKoD\tests;

use BelKoD\DataWizard\DateWizard;
use PHPUnit\Framework\TestCase;

date_default_timezone_set('Europe/Moscow');

class DateWizardTest extends TestCase
{

	public function testTimeToStr()
	{
		$timestamp = strtotime('2022-07-25 14:30:00');
		$format = 'H:i d F Yг.';
		$case = 1;
		$upper = true;

		$expectedResult = '25 Июля 2022г.';
		$result = DateWizard::str($timestamp, $format, $case, $upper);

		$this->assertEquals($expectedResult, $result);
	}

	public function testTimeToStrWithLowercaseMonth()
	{
		$timestamp = strtotime('2022-07-25 14:30:00');
		$format = 'd F Yг.';
		$case = 1;
		$upper = false;

		$expectedResult = '25 июля 2022г.';
		$result = DateWizard::str($timestamp, $format, $case, $upper);

		$this->assertEquals($expectedResult, $result);
	}

	public function testTimeToStrWithInvalidTimestamp()
	{
		$timestamp = -1;
		$format = 'd F Yг.';
		$case = 0;
		$upper = true;

		$expectedResult = '';
		$result = DateWizard::str($timestamp, $format, $case, $upper);

		$this->assertEquals($expectedResult, $result);
	}

	public function testTimeToRome()
	{
		$timestamp = strtotime('2022-07-25 14:30:00');
		$template = '%1$s/%2$s/%3$s';

		$expectedResult = 'XXV/VII/MMXXII';
		$result = DateWizard::rome($timestamp, $template);

		$this->assertEquals($expectedResult, $result);
	}

	public function testPassedTime()
	{
		$timestamp = strtotime('2024-11-14 19:20:00');

		$expectedResult = 'сегодня в 14:05';
		$result = DateWizard::passed($timestamp);

		$this->assertEquals($expectedResult, $result);
	}
}
