<?php
namespace TextClassification\BsTextClassification\Controller;

use TextClassification\BsTextClassification\Domain\Model\Data;
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
 * DataController
 */
class DataController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * dataRepository
     * 
     * @var \TextClassification\BsTextClassification\Domain\Repository\DataRepository
     * @inject
     */
    protected $dataRepository = NULL;
    protected $help = NULL;

    /**
     * action data
     *
     * @return void
     */
    public function dataAction()
    {
        $help = new \TextClassification\BsTextClassification\Classes\AdditionalHelper\Helper();
        //get Data from guardian
        $url_ENG = "https://www.theguardian.com/world/2016/dec/10/bomb-outside-istanbul-football-stadium-causes-multiple-casualties";

        $text = $help->getData($url_ENG);

        //filter data

        $meta = get_meta_tags($url_ENG);
        $title = $help->getEverythingBetweenTags($text,"title");
        $split = preg_split('/\|+/', $title);

        $dataCategory = $split[1];
        $dataTitle = $title;
        $dataDescription = $meta['description'];
        $dataContent = implode(" ",$help->pregMatchAll($text,'p','p'));

        // preprocess Data tags weg, stopwords weg leezeichen weg, stemming

        $dataContent = $help->preprocessingData($dataContent);
        $dataTitle = $help->preprocessingData($dataTitle);
        $dataDescription = $help->preprocessingData($dataDescription);

        $data =[
            "dataCategory" => $dataCategory,
            "dataTitle" => implode(" ",$dataTitle),
            "dataDescription" => implode(" ",$dataDescription),
            "dataContent" => implode(" ",$dataContent)
        ];


        $this->newAction($data);

      /*  print "<pre>";
        print_r($dataContent);
        print "</pre>";

        $this->view->assign('output', $data );*/
    }



    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
       // $datas = $this->dataRepository->findAll();
        $this->view->assign('datas', "UHHH LISTE");
    }
    
    /**
     * action new
     * 
     * @return void
     */
    public function newAction($d)
    {
        $data = new Data();
        $data->setTitle($d['dataTitle']);
        $data->setDescription($d['dataDescription']);
        $data->setCategory($d['dataCategory']);
        $data->setContent($d['dataContent']);

        $this->createAction($data);
    }
    
    /**
     * action create
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\Data $newData
     * @return void
     */
    public function createAction(\TextClassification\BsTextClassification\Domain\Model\Data $newData)
    {
        $this->redirect('list');
        /*$this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->dataRepository->add($newData);
        $this->redirect('list');*/
    }
    
    /**
     * action edit
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\Data $data
     * @ignorevalidation $data
     * @return void
     */
    public function editAction(\TextClassification\BsTextClassification\Domain\Model\Data $data)
    {
        $this->view->assign('data', $data);
    }
    
    /**
     * action update
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\Data $data
     * @return void
     */
    public function updateAction(\TextClassification\BsTextClassification\Domain\Model\Data $data)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->dataRepository->update($data);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\Data $data
     * @return void
     */
    public function deleteAction(\TextClassification\BsTextClassification\Domain\Model\Data $data)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->dataRepository->remove($data);
        $this->redirect('list');
    }

}