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
 * Test case for class TextClassification\BsTextClassification\Controller\CategoryFingerprintController.
 *
 * @author Barbara Sikora <barbara-sikora@gmx.at>
 */
class CategoryFingerprintControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \TextClassification\BsTextClassification\Controller\CategoryFingerprintController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('TextClassification\\BsTextClassification\\Controller\\CategoryFingerprintController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllCategoryFingerprintsFromRepositoryAndAssignsThemToView()
	{

		$allCategoryFingerprints = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$categoryFingerprintRepository = $this->getMock('', array('findAll'), array(), '', FALSE);
		$categoryFingerprintRepository->expects($this->once())->method('findAll')->will($this->returnValue($allCategoryFingerprints));
		$this->inject($this->subject, 'categoryFingerprintRepository', $categoryFingerprintRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('categoryFingerprints', $allCategoryFingerprints);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenCategoryFingerprintToView()
	{
		$categoryFingerprint = new \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('categoryFingerprint', $categoryFingerprint);

		$this->subject->showAction($categoryFingerprint);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenCategoryFingerprintToCategoryFingerprintRepository()
	{
		$categoryFingerprint = new \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint();

		$categoryFingerprintRepository = $this->getMock('', array('add'), array(), '', FALSE);
		$categoryFingerprintRepository->expects($this->once())->method('add')->with($categoryFingerprint);
		$this->inject($this->subject, 'categoryFingerprintRepository', $categoryFingerprintRepository);

		$this->subject->createAction($categoryFingerprint);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenCategoryFingerprintToView()
	{
		$categoryFingerprint = new \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('categoryFingerprint', $categoryFingerprint);

		$this->subject->editAction($categoryFingerprint);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenCategoryFingerprintInCategoryFingerprintRepository()
	{
		$categoryFingerprint = new \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint();

		$categoryFingerprintRepository = $this->getMock('', array('update'), array(), '', FALSE);
		$categoryFingerprintRepository->expects($this->once())->method('update')->with($categoryFingerprint);
		$this->inject($this->subject, 'categoryFingerprintRepository', $categoryFingerprintRepository);

		$this->subject->updateAction($categoryFingerprint);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenCategoryFingerprintFromCategoryFingerprintRepository()
	{
		$categoryFingerprint = new \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint();

		$categoryFingerprintRepository = $this->getMock('', array('remove'), array(), '', FALSE);
		$categoryFingerprintRepository->expects($this->once())->method('remove')->with($categoryFingerprint);
		$this->inject($this->subject, 'categoryFingerprintRepository', $categoryFingerprintRepository);

		$this->subject->deleteAction($categoryFingerprint);
	}
}
