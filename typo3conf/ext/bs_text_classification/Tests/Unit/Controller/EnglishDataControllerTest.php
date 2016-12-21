<?php
namespace TextClassification\BsTextClassification\Tests\Unit\Controller;
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
 * Test case for class TextClassification\BsTextClassification\Controller\EnglishDataController.
 *
 * @author Barbara Sikora <barbara-sikora@gmx.at>
 */
class EnglishDataControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \TextClassification\BsTextClassification\Controller\EnglishDataController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('TextClassification\\BsTextClassification\\Controller\\EnglishDataController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllEnglishDatasFromRepositoryAndAssignsThemToView()
	{

		$allEnglishDatas = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$englishDataRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishDataRepository', array('findAll'), array(), '', FALSE);
		$englishDataRepository->expects($this->once())->method('findAll')->will($this->returnValue($allEnglishDatas));
		$this->inject($this->subject, 'englishDataRepository', $englishDataRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('englishDatas', $allEnglishDatas);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenEnglishDataToEnglishDataRepository()
	{
		$englishData = new \TextClassification\BsTextClassification\Domain\Model\EnglishData();

		$englishDataRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishDataRepository', array('add'), array(), '', FALSE);
		$englishDataRepository->expects($this->once())->method('add')->with($englishData);
		$this->inject($this->subject, 'englishDataRepository', $englishDataRepository);

		$this->subject->createAction($englishData);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenEnglishDataToView()
	{
		$englishData = new \TextClassification\BsTextClassification\Domain\Model\EnglishData();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('englishData', $englishData);

		$this->subject->editAction($englishData);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenEnglishDataInEnglishDataRepository()
	{
		$englishData = new \TextClassification\BsTextClassification\Domain\Model\EnglishData();

		$englishDataRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishDataRepository', array('update'), array(), '', FALSE);
		$englishDataRepository->expects($this->once())->method('update')->with($englishData);
		$this->inject($this->subject, 'englishDataRepository', $englishDataRepository);

		$this->subject->updateAction($englishData);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenEnglishDataFromEnglishDataRepository()
	{
		$englishData = new \TextClassification\BsTextClassification\Domain\Model\EnglishData();

		$englishDataRepository = $this->getMock('TextClassification\\BsTextClassification\\Domain\\Repository\\EnglishDataRepository', array('remove'), array(), '', FALSE);
		$englishDataRepository->expects($this->once())->method('remove')->with($englishData);
		$this->inject($this->subject, 'englishDataRepository', $englishDataRepository);

		$this->subject->deleteAction($englishData);
	}
}
