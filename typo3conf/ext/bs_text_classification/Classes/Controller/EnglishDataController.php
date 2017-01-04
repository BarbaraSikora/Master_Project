<?php
namespace TextClassification\BsTextClassification\Controller;

use TextClassification\BsTextClassification\Domain\Model\EnglishData;
use TextClassification\BsTextClassification\Domain\Repository\EnglishDataRepository;
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
 * EnglishDataController
 */
class EnglishDataController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * help
     *
     * @var \TextClassification\BsTextClassification\Classes\AdditionalHelper\Helper
     * @inject
     */
    protected $help = null;

    /**
     * englishDataRepository
     *
     * @var \TextClassification\BsTextClassification\Domain\Repository\EnglishDataRepository
     * @inject
     */
    protected $englishDataRepository = NULL;

    /**
     * termsController
     *
     * @var \TextClassification\BsTextClassification\Controller\EnglishTermsController
     * @inject
     */
    protected $termsController = NULL;

    /**
     * action data
     *
     * @return void
     */
    public function dataAction()
    {
        //get Data from guardian
        $url_ENG = 'https://www.theguardian.com/international';
        // get LINKS of Categories
        $text = $this->help->getData($url_ENG);
        $classname = 'top-navigation__action';
        $doc = new \DOMDocument();
        $doc->loadHTML($text);
        $nodelist = $this->help->getNodeList("//nav//ul//a[contains(@class, '{$classname}')]/@href", $doc);
        $categoryLinks = array();
        foreach ($nodelist as $node) {
            $categoryLinks[] = "{$node->nodeValue}";
        }

              for ($i = 12; $i <13; $i++) {
                    //get Article-Links of each category
                    $url_ENG = $categoryLinks[$i];
                    $firstCategory = explode('/', $url_ENG);
                    $firstCategory = $firstCategory[count($firstCategory)-1];
                    if($firstCategory == "commentisfree"){
                        $firstCategory = "opinion";
                    }
                    $links = array();
                    $links = $this->help->getAllLinks($url_ENG . '?page=7', $links, $doc);

                      foreach ($links as $link) {
                            $text = $this->help->getData($link);

                            $doc->loadHTML($text);
                            $meta = get_meta_tags($link);
                            $title = $this->help->getEverythingBetweenTags($text, 'title');
                            $split = preg_split('/\\|+/', $title);
                            $dataCategory = $firstCategory." ".$split[count($split) - 2];
                            $dataTitle = $title;
                            $dataDescription = $meta['description'];
                            $dataContent = implode(' ', $this->help->pregMatchAll($text, 'p', 'p'));
                            $attr = 'datePublished';
                            $dataDate = $this->help->getNodeList("//div//p/time[contains(@itemprop, '{$attr}')]/@datetime", $doc);
                            $date = $dataDate[0]->nodeValue;
                            $date = str_replace('T', ' ', $date);
                            $date = str_replace('+0000', '', $date);

                            // preprocess Data tags weg, stopwords weg leezeichen weg, stemming
                            $dataContent = strip_tags($dataContent);
                            $dataContentTerms = $this->help->preprocessingData($dataContent);

                            $data = array(
                                'datePublished' => $date,
                                'dataCategory' => $dataCategory,
                                'dataTitle' => $dataTitle,
                                'dataDescription' => $dataDescription,
                                'dataContent' => $dataContent
                            );

                            $terms = array(
                                'terms' => implode(" ",$dataContentTerms)
                            );
                            $this->newAction($data,$terms);

                     }
         }
        print "<pre>";
        print_r($links);
        print "</pre>";

       // $this->redirect('list');
    }

    /**
     * action list
     *
     * @param TextClassification\BsTextClassification\Domain\Model\EnglishData
     * @return void
     */
    public function listAction()
    {
        $datas = $this->englishDataRepository->findAll();
        $this->view->assign('datas', $datas);
    }

    /**
     * action new
     *
     * @param TextClassification\BsTextClassification\Domain\Model\EnglishData
     * @return void
     */
    public function newAction($d,$terms)
    {
        $data = new EnglishData();
        $data->setTitle($d['dataTitle']);
        $data->setDatePublished($d['datePublished']);
        $data->setDescription($d['dataDescription']);
        $data->setCategory($d['dataCategory']);
        $data->setContent($d['dataContent']);
        $this->createAction($data);
        $this->termsController->newAction($terms,$data);
    }

    /**
     * action create
     *
     * @param TextClassification\BsTextClassification\Domain\Model\EnglishData
     * @return void
     */
    public function createAction(\TextClassification\BsTextClassification\Domain\Model\EnglishData $newEnglishData)
    {
        //$this->addFlashMessage('The object was created.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->englishDataRepository->add($newEnglishData);
    }

    /**
     * action edit
     *
     * @param TextClassification\BsTextClassification\Domain\Model\EnglishData
     * @ignorevalidation $englishData
     * @return void
     */
    public function editAction(\TextClassification\BsTextClassification\Domain\Model\EnglishData $englishData)
    {
        $this->view->assign('data', $englishData);
    }

    /**
     * action update
     *
     * @param TextClassification\BsTextClassification\Domain\Model\EnglishData
     * @return void
     */
    public function updateAction(\TextClassification\BsTextClassification\Domain\Model\EnglishData $englishData)
    {
        $this->englishDataRepository->update($englishData);
       // $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param TextClassification\BsTextClassification\Domain\Model\EnglishData
     * @return void
     */
    public function deleteAction(\TextClassification\BsTextClassification\Domain\Model\EnglishData $englishData)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->englishDataRepository->remove($englishData);
        $this->redirect('list');
    }

}