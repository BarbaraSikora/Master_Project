<?php
namespace TextClassification\BsTextClassification\Controller;

use NlpTools\Similarity\Euclidean;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\KNearestNeighbours;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\NaiveBayes;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\SemanticFingerprinting;
use TextClassification\BsTextClassification\Domain\Model\EnglishTerms;
use TextClassification\BsTextClassification\Domain\Repository\EnglishTermsRepository;
use NlpTools\Similarity\CosineSimilarity;
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
     * help
     *
     * @var \TextClassification\BsTextClassification\Classes\AdditionalHelper\Helper
     * @inject
     */
    protected $help = null;

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
       /* foreach($terms as $term){
            $article = $term;
            $this->help->writeFile($article);
          /*  $string = $term->getArticleID()->getContent();
             $newArray= $this->help->preprocessingData($string);
             $term->setTerms(implode(" ",$newArray));
             $this->updateAction($term);*/
       // }
       // $terms = $this->englishTermsRepository->findAll();
        $this->view->assign('datas',count($terms));
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
        $dataTerms = $this->englishTermsRepository->findAll();
        $percentage = 0;
       //$dataTerms = $this->help->filterSpecificCategories($dataTerms);


        //$testID = 57;
        $knn = new KNearestNeighbours();
        $knn->startKnn($dataTerms);
        $testData = $knn->getTestData();


        print_r("<pre>");
        print_r(count($dataTerms));
        print("<br>");
        print_r(count($testData));
        print_r("</pre>");

       // $this->help->exportMDS($knn);
        //$this->help->exportGeneralCategory($dataTerms);
       // $this->help->exportExactCatgeory($dataTerms);
       // $this->help->exportCategories($dataTerms);


     $percentage = $this->testKNN($testData,$dataTerms,$knn);

        $this->view->assign('data',$percentage );


    }

    /**
     * action naive bayes
     *
     * @return void
     */
    public function bayesAction()
    {
        $dataTerms = $this->englishTermsRepository->findAll();
        $percentage = 0;
        $dataTerms = $this->help->filterSpecificCategories($dataTerms);
        $naive = new NaiveBayes();
        $naive->startNaiveBayes($dataTerms);
        $testData = $naive->getTestData();

        print_r("<pre>");
        print_r(count($dataTerms));
        print("<br>");
        print_r(count($testData));
        print_r("</pre>");


      /* $cat=strtolower($testData[609][0]);
       $probabilities =$naive->guess($testData[609][1]);
        print("<pre>");
        print_r($probabilities);
        print("</pre>");
        print_r($cat);
        print("<br>");*/

       $percentage = $this->testNaiveBayes($testData,$dataTerms,$naive);

        $this->view->assign('data',$percentage );

    }

    /**
     * action fingerprinting
     *
     * @return void
     */
    public function fingerprintingAction()
    {
        $dataTerms = $this->englishTermsRepository->findAll();
        $percentage = 100000;
        $dataTerms = $this->help->filterSpecificCategories($dataTerms);
        $fingerprinting = new SemanticFingerprinting();
        $fingerprinting->startSemanticFingerprinting($dataTerms);
        $testData = $fingerprinting->getTestData();

     //$cat = $fingerprinting->classify($testData[31]);//31,10,161,72,84 world,world, football,world,world

         print_r("<pre>");
        //print_r($cat);
        print("<br>");
        print_r(count($dataTerms));
        print("<br>");
        print_r(count($testData));
        print_r("</pre>");



        $percentage = $this->testSemanticFingerprinting($testData,$dataTerms,$fingerprinting);

        $this->view->assign('data',$percentage );


    }







    //////////////////TEST FUNCTIONS/////////////////////////////

    protected function testSemanticFingerprinting($testData,$dataTerms,$fingerprinting)
    {
        $right = 0;

        foreach($testData as $key => $value){
            print_r("<br>");
            print_r($key);
            print_r("<br>");
            $cat= trim(strtolower(strstr($testData[$key]->getArticleID()->getCategory(), ' ')));
            $probabilities = $fingerprinting->classify($testData[$key]);
            arsort($probabilities);

            print_r("<pre>");
            print_r($probabilities);
            print_r("</pre>");


            $predictedCat = current(array_keys($probabilities));


            print_r($predictedCat);
            print("----");
            print_r($cat);
            print("<br>");

            //compare it with the selected category
            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;

            }
        }

        return $right;

    }



    protected function testNaiveBayes($testData,$dataTerms,$naive){
        $right = 0;

        foreach($testData as $key => $value){
            $cat=strtolower($testData[$key][0]);
            $probabilities =$naive->classifyDocument($testData[$key][1]);
            print_r("<pre>");
            print_r($probabilities);
            print_r("</pre>");

            $predictedCat = current(array_keys($probabilities));

            print_r($predictedCat);
            print("----");
            print_r($cat);
            print("<br>");

            //compare it with the selected category
            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;

            }
        }

        return $right;
    }


    protected function testKNN($testData,$dataTerms,$knn){
        $right = 0;
        $k =15;
        $sim = new CosineSimilarity();
        $t = array_slice($testData,0,1,true);

        foreach($testData as $key => $test){
            $id = $dataTerms[$key]->getArticleID()->getUid();
            print("<br>");
            print_r($id);
            print("<br>");

            $categories=[];
            //get specific Catgeory
            $cat=strtolower($dataTerms[$key]->getArticleID()->getCategory());
            $data = $knn->cosineSim($key,$sim);
            //sort data big to low
            arsort($data);
            //get the top K neighbours
            $topK = array_slice($data,0,$k,true);

            print_r("<pre>");
            print_r($topK);
            print_r("</pre>");

            //get categories of them (general or specific)
            foreach ($topK as $c =>$value) {
                $a=explode(" ",$dataTerms[$c]->getArticleID()->getCategory());
                //$a=trim(strtolower(strstr($dataTerms[$c]->getArticleID()->getCategory(), ' ')));
                $categories[] = $a[0];
            }


            $weighting = [];
            $dist = array_slice($topK,0,$k);
            //summing up similarities for different classes
            foreach($dist as $i => $v){
                $weighting[$categories[$i]] = $weighting[$categories[$i]]+$dist[$i];
            }
            print_r("<pre>");
            print_r($categories);
            print_r("</pre>");

            //sort for biggest similarity
            arsort($weighting);

           //$countCat = array_count_values($categories);
           // arsort($countCat);

            //take the class with the most members
            //$predictedCat = current(array_keys($countCat));
            //get the highest similarity
            $predictedCat = current(array_keys($weighting));


            print_r("<pre>");
           // print_r($countCat);
            print_r($weighting);
            print_r("</pre>");

            print_r($predictedCat);
            print("----");
            print_r($cat);
            print("<br>");

            //compare it with the selected category
            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;

            }
        }

        return $right;
    }

    protected function testWeightedKNN($testData,$dataTerms,$knn){
        $right = 0;
        $k = 7;
        $sim = new CosineSimilarity();

        foreach($testData as $key => $test){
            $categories=[];
            $cat=strtolower($dataTerms[$key]->getArticleID()->getCategory());
            $data = $knn->cosineSim($key,$sim);
            arsort($data);

            $topK = array_slice($data,0,$k,true);

            foreach ($topK as $c =>$value) {
                $a=explode(" ",$dataTerms[$c]->getArticleID()->getCategory());
               // $a=strtolower(strstr($dataTerms[$c]->getArticleID()->getCategory(), ' '));
                $categories[] = $a[0];
            }

            $weighting = [];
            $dist = array_slice($topK,0,$k);
            foreach($dist as $i => $v){
                $weighting[$categories[$i]] = $weighting[$categories[$i]]+$dist[$i];
            }

            $countCat = array_count_values($categories);
            arsort($countCat);
            foreach($weighting as $i => $v){
                $weighting[$i] = $weighting[$i]/$countCat[$i];
            }
            arsort($weighting);
            $predictedCat = current(array_keys($weighting));

            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;

            }
        }

        return $right;
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
        //$this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->englishTermsRepository->update($englishTerms);
        //$this->redirect('list');
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