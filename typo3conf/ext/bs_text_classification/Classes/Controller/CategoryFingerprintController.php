<?php
namespace TextClassification\BsTextClassification\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Barbara Sikora <barbara-sikora@gmx.at>, FH Hagenberg
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * CategoryFingerprintController
 */
class CategoryFingerprintController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    /**
     * categoryFingerprintRepository
     *
     * @var \TextClassification\BsTextClassification\Domain\Repository\CategoryFingerprintRepository
     * @inject
     */
    protected $categoryFingerprintRepository = NULL;

    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $categoryFingerprints = $this->categoryFingerprintRepository->findAll();
        $this->view->assign('categoryFingerprints', $categoryFingerprints);
    }
    
    /**
     * action show
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint
     * @return void
     */
    public function showAction(\TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint)
    {
        $this->view->assign('categoryFingerprint', $categoryFingerprint);
    }
    
    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
        
    }
    
    /**
     * action create
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $newCategoryFingerprint
     * @return void
     */
    public function createAction(\TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $newCategoryFingerprint)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->categoryFingerprintRepository->add($newCategoryFingerprint);
        $this->redirect('list');
    }
    
    /**
     * action edit
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint
     * @ignorevalidation $categoryFingerprint
     * @return void
     */
    public function editAction(\TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint)
    {
        $this->view->assign('categoryFingerprint', $categoryFingerprint);
    }
    
    /**
     * action update
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint
     * @return void
     */
    public function updateAction(\TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->categoryFingerprintRepository->update($categoryFingerprint);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint
     * @return void
     */
    public function deleteAction(\TextClassification\BsTextClassification\Domain\Model\CategoryFingerprint $categoryFingerprint)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->categoryFingerprintRepository->remove($categoryFingerprint);
        $this->redirect('list');
    }

}