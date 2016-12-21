<?php
namespace TextClassification\BsTextClassification\Controller;

use TextClassification\BsTextClassification\Classes\AdditionalHelper\KNearestNeighbours;
use TextClassification\BsTextClassification\Domain\Model\EnglishTerms;
use TextClassification\BsTextClassification\Domain\Repository\EnglishTermsRepository;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Barbara Sikora <barbara-sikora@gmx.at>, FH Hagenberg
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
 * EnglishTermsController
 */
class EnglishTermsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * englishTermsRepository
     * 
     * @var \TextClassification\BsTextClassification\Domain\Repository\EnglishTermsRepository
     * @inject
     */
    protected $englishTermsRepository = NULL;

    
    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $terms = $this->englishTermsRepository->findAll();
        $this->view->assign('datas', count($terms));
    }

    /**
     * action show
     *
     * @return void
     */
    public function showAction()
    {
        $terms = $this->englishTermsRepository->findAll();
        $this->view->assign('datas', $terms);
    }


    /**
     * action knn
     *
     * @return void
     */
    public function knnAction()
    {
        $val = 0;
        $data = $this->englishTermsRepository->findAll();
        $knn = new KNearestNeighbours($data);
        $val = $knn->getDataVectors();
        $data = "inProgress";

        print "<pre>";
       // print_r($val[1]);
        print "</pre>";
        $this->view->assign('data', $val[0]);


    }
    
    /**
     * action new
     * 
     * @param $terms
     * @return void
     */
    public function newAction($terms,$englishData)
    {
        $data = new EnglishTerms();
        $data->setTerms($terms['terms']);
        $data->setArticleID($englishData);
        $this->createAction($data);
    }
    
    /**
     * action create
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\EnglishTerms $newEnglishTerms
     * @return void
     */
    public function createAction(\TextClassification\BsTextClassification\Domain\Model\EnglishTerms $newEnglishTerms)
    {
        // $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->englishTermsRepository->add($newEnglishTerms);

    }
    
    /**
     * action edit
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\EnglishTerms $englishTerms
     * @ignorevalidation $englishTerms
     * @return void
     */
    public function editAction(\TextClassification\BsTextClassification\Domain\Model\EnglishTerms $englishTerms)
    {
        $this->view->assign('englishTerms', $englishTerms);
    }
    
    /**
     * action update
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\EnglishTerms $englishTerms
     * @return void
     */
    public function updateAction(\TextClassification\BsTextClassification\Domain\Model\EnglishTerms $englishTerms)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->englishTermsRepository->update($englishTerms);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\EnglishTerms $englishTerms
     * @return void
     */
    public function deleteAction(\TextClassification\BsTextClassification\Domain\Model\EnglishTerms $englishTerms)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->englishTermsRepository->remove($englishTerms);
        $this->redirect('list');
    }

}