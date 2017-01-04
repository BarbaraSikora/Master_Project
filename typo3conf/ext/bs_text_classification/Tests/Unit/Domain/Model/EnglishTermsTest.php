<?php

namespace TextClassification\BsTextClassification\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Barbara Sikora <barbara-sikora@gmx.at>, FH Hagenberg
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \TextClassification\BsTextClassification\Domain\Model\EnglishTerms.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Barbara Sikora <barbara-sikora@gmx.at>
 */
class EnglishTermsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \TextClassification\BsTextClassification\Domain\Model\EnglishTerms
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \TextClassification\BsTextClassification\Domain\Model\EnglishTerms();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTermsReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getTerms()
		);
	}

	/**
	 * @test
	 */
	public function setTermsForStringSetsTerms()
	{
		$this->subject->setTerms('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'terms',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getArticleIDReturnsInitialValueForEnglishData()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getArticleID()
		);
	}

	/**
	 * @test
	 */
	public function setArticleIDForEnglishDataSetsArticleID()
	{
		$articleIDFixture = new \TextClassification\BsTextClassification\Domain\Model\EnglishData();
		$this->subject->setArticleID($articleIDFixture);

		$this->assertAttributeEquals(
			$articleIDFixture,
			'articleID',
			$this->subject
		);
	}
}