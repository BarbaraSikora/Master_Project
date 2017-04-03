<?php
namespace TextClassification\BsTextClassification\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Barbara Sikora <barbara-sikora@gmx.at>, FH Hagenberg
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
 * Test case for class TextClassification\BsTextClassification\Controller\EnglishTermsController.
 *
 * @author Barbara Sikora <barbara-sikora@gmx.at>
 */
class EnglishTermsControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \TextClassification\BsTextClassification\Controller\EnglishTermsController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('TextClassification\\BsTextClassification\\Controller\\EnglishTermsController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllEnglishTermssFromRepositoryAndAssignsThemToView()
	{

		$allEnglishTermss = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$englishTermsRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishTermsRepository', array('findAll'), array(), '', FALSE);
		$englishTermsRepository->expects($this->once())->method('findAll')->will($this->returnValue($allEnglishTermss));
		$this->inject($this->subject, 'englishTermsRepository', $englishTermsRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('englishTermss', $allEnglishTermss);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenEnglishTermsToEnglishTermsRepository()
	{
		$englishTerms = new \TextClassification\BsTextClassification\Domain\Model\EnglishTerms();

		$englishTermsRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishTermsRepository', array('add'), array(), '', FALSE);
		$englishTermsRepository->expects($this->once())->method('add')->with($englishTerms);
		$this->inject($this->subject, 'englishTermsRepository', $englishTermsRepository);

		$this->subject->createAction($englishTerms);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenEnglishTermsToView()
	{
		$englishTerms = new \TextClassification\BsTextClassification\Domain\Model\EnglishTerms();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('englishTerms', $englishTerms);

		$this->subject->editAction($englishTerms);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenEnglishTermsInEnglishTermsRepository()
	{
		$englishTerms = new \TextClassification\BsTextClassification\Domain\Model\EnglishTerms();

		$englishTermsRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishTermsRepository', array('update'), array(), '', FALSE);
		$englishTermsRepository->expects($this->once())->method('update')->with($englishTerms);
		$this->inject($this->subject, 'englishTermsRepository', $englishTermsRepository);

		$this->subject->updateAction($englishTerms);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenEnglishTermsFromEnglishTermsRepository()
	{
		$englishTerms = new \TextClassification\BsTextClassification\Domain\Model\EnglishTerms();

		$englishTermsRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishTermsRepository', array('remove'), array(), '', FALSE);
		$englishTermsRepository->expects($this->once())->method('remove')->with($englishTerms);
		$this->inject($this->subject, 'englishTermsRepository', $englishTermsRepository);

		$this->subject->deleteAction($englishTerms);
	}
}
