<?php
namespace BelKoD\tests;

use BelKoD\DataWizard\locale\ru\NumericLocale;
use NumberFormatter;
use PHPUnit\Framework\TestCase;
use BelKoD\DataWizard\NumericWizard;

class NumericWizardTest extends TestCase
{
	public function testMorph()
	{
		$source = 2.5;
		$form1 = NumericLocale::NUMERICWIZARD_L[0];
		$form2 = NumericLocale::NUMERICWIZARD_L[1];
		$form3 = NumericLocale::NUMERICWIZARD_L[2];

		$expectedResult = '3 литра';
		$result = NumericWizard::morph($source, $form1, $form2, $form3, '%1$s %2$s');

		$this->assertEquals($expectedResult, $result);
	}

	public function testRome()
	{
		$source = 2023;

		$expectedResult = 'MMXXIII';

		$result = NumericWizard::rome($source);

		$this->assertEquals($expectedResult, $result);
	}

	public function testNumToStr()
	{
		$source = 214503;

		$expectedResult = 'двести четырнадцать тысяч пятьсот три';

		$result = NumericWizard::str($source);


		$this->assertEquals($expectedResult, $result);

	}

	public function testPriceToStr()
	{
		$source = 214503.78;

		$expectedResult = 'двести четырнадцать тысяч пятьсот три рубля 78 копеек';

		$result = NumericWizard::price($source, true);

		$this->assertEquals($expectedResult, $result);
	}
}
