<?php
namespace TextClassification\BsTextClassification\Controller;
use NlpTools\Analysis\Idf;
use NlpTools\Documents\TokensDocument;
use NlpTools\Documents\TrainingSet;
use NlpTools\Similarity\Euclidean;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\Helper;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\KNearestNeighbours;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\NaiveBayes;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\SemanticFingerprinting;
use TextClassification\BsTextClassification\Classes\AdditionalHelper\TfIdfFeatureFactory;
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
     * categoryFingerprintController
     *
     * @var \TextClassification\BsTextClassification\Controller\CategoryFingerprintController
     * @inject
     */
    protected $categoryFingerprintController = NULL;

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
        $terms = $this->englishTermsRepository->findAll();
        $terms = $this->help->filterTwentyCategories($terms);
       // $this->help->exportCategories($terms);
        /* foreach($terms as $term){
            $article = $term;
           $this->help->writeFile($article);
          /*$string = $term->getArticleID()->getContent();
           $newArray= $this->help->preprocessingData($string);
           $term->setTerms(implode(" ",$newArray));
           $this->updateAction($term);*/
        //}
        //$terms = $this->englishTermsRepository->findAll();
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
        $dataTerms = array_values($this->help->filterSpecificCategories($dataTerms));
        //$testID = 57;
        $knn = new KNearestNeighbours();
        $knn->startKnn($dataTerms);
        $testData = $knn->getTestData();
        $trainData = $knn->getTrainingsData();
        print_r("<pre>");
        print_r(count($dataTerms));
        print("<br>");
        print_r(count($testData));
        print_r("</pre>");
        //$this->help->exportMDS($knn);
        //$this->help->exportGeneralCategory($dataTerms);
        // $this->help->exportExactCatgeory($dataTerms);
        // $this->help->exportCategories($dataTerms);
        //$this->help->exportComparisonTwoFiles($knn);
        $result = $this->testKNN($testData,$dataTerms,$knn);
        $percentage = $result['correct'];
        $result['countTestDocs']=count($testData);
        $result['accuracy']=($percentage/count($testData))*100;
        $this->view->assign('data',$result );
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
        $result = $this->testNaiveBayes($testData,$dataTerms,$naive);
        $percentage = $result['correct'];
        $result['countTestDocs']=count($testData);
        $result['accuracy']=($percentage/count($testData))*100;
        $this->view->assign('data',$result );
    }
    /**
     * action fingerprinting
     *
     * @return void
     */
    public function fingerprintingAction()
    {
        $wholeDB = $this->englishTermsRepository->findAll();
        $percentage = 0;
        $testData = [];
        $dataTerms = $this->help->filterSpecificCategories($wholeDB);
        $fingerprinting = new SemanticFingerprinting();

          $contextMap = $this->categoryFingerprintRepository->findByUid(56);
           // $fingerprinting->startSemanticFingerprinting($dataTerms,$contextMap->getFingerprint(),false, 188);
         // $fingerprinting->startSemanticFingerprinting($dataTerms,false,false, false);



      /*  $stacks = [];
        //finde die Wort - Stacks pro category auf der 20er ContextMap
        for($i = 78; $i <88; $i++){
            $stack = $this->categoryFingerprintRepository->findByUid($i);
            $class = explode("_",$stack->getCategoryName())[0];
            $stacks[$class] = $stack->getFingerprint();
        }

        $fingerprinting->startSemanticFingerprinting($dataTerms,$contextMap->getFingerprint(),$stacks, 188);
        $testData = $fingerprinting->getTestData();
*/





       /* $map = $fingerprinting->getContextMap();
        $map = implode(" ",$map);
        $this->categoryFingerprintController->newAction('SecondTen',$map);

        $map2 = $fingerprinting->getCategoryFingerprints();
        print_r("<pre>");
        print_r($map2);
        print_r("</pre>");
      //  $map = implode(" ",$map);

        foreach($map2 as $class => $stacks){
            $stack = implode(" ",$stacks);
            $this->categoryFingerprintController->newAction($class.'_SecondTen',$stack);
        }*/

        /*print_r("<pre>");
        // print_r($probabilities);
        print("Data Amounts:<br>");
        print_r(count($dataTerms));
        print("<br>");
        print_r(count($testData));
        print_r("</pre>");*/


            $result = [];
       $thres = 10;
        print("<p>");
        for($i =1; $i < 18; $i++){
         $thres=10*$i;

         $fingerprinting->startSemanticFingerprinting($dataTerms,$contextMap->getFingerprint(),false, $thres);
         $testData = $fingerprinting->getTestData();


             $result = $this->testSemanticFingerprinting($testData,$dataTerms,$fingerprinting);
             print("<br>------ACCUR------------<br>");
             print_r(($result['correct']/count($testData))*100);
             print("<br>------------------<br>");


        }
        print("</p>");

        ##########################################################################################
       //$result = $this->testSemanticFingerprinting($testData,$dataTerms,$fingerprinting);

      // $percentage = $result['correct'];
        $result['countTestDocs']=count($testData);
        $result['accuracy']=($percentage/count($testData))*100;
        $this->view->assign('data',$result );
    }
    /**
     * action url
     * @return void
     */
    public function urlAction()
    {
        $url = $this->request->getArgument('url');
        $data = [];
        $resultNaive = [];
        $resultKnn = [];
        $resultSemantic = [];
        $knn = new KNearestNeighbours();
        $naive = new NaiveBayes();
        $fingerprinting = new SemanticFingerprinting();
        if(!$this->help->checkURL($url)){
            $data = "false";
        }else{
            $testTerms = $this->help->getTerms($url);
            $data['class'] =  array_keys($testTerms)[0];
            $dataTerms = $this->englishTermsRepository->findAll();
            $dataTerms = $this->help->filterTwentyCategories($dataTerms);
            $data['number'] = count($dataTerms);

             $naive->simpleStart($dataTerms);
             $knn->simpleStart($dataTerms,$testTerms);
             $testTermsWeighted = $knn->getTestData();


            //finde die 20er ContextMap
            $contextMap = $this->categoryFingerprintRepository->findByUid(56);
            $stacks = [];
            //finde die Wort - Stacks pro category auf der 20er ContextMap
            for($i = 57; $i <77; $i++){
                $stack = $this->categoryFingerprintRepository->findByUid($i);
                $class = explode("_",$stack->getCategoryName())[0];
                $stacks[$class] = $stack->getFingerprint();
            }

            $fingerprinting->simpleStart($dataTerms,$testTerms,$contextMap->getFingerprint(),$stacks);


         /* $map = $fingerprinting->getContextMap();
            print_r("<pre>");
            print_r($map);
            print_r("</pre>");
            $map = implode(" ",$map);


            $this->categoryFingerprintController->newAction("Science",$map);*/
           /* foreach($cats as $class => $array){
                $fp = implode(" ",$map);
               // $this->categoryFingerprintController->newAction($class,$fp);
            }*/

           $resultNaive = $this->simpleBayesTest($testTerms,$naive);
           $resultKnn = $this->simpleKnnTest($testTermsWeighted,$dataTerms,$knn);
           $resultSemantic = $this->simpleSemanticTest($testTerms,$fingerprinting);
        }


        $data['bayes'] = $resultNaive;
        $data['knn'] = $resultKnn;
        $data['sem'] = $resultSemantic;

        $this->view->assign('data',$data );
    }
    //////////////////TEST FUNCTIONS/////////////////////////////

    protected function simpleSemanticTest($testTerms,$fingerprinting){
        $package = [];
        $cat= array_keys($testTerms)[0];
        $content = $testTerms[$cat];
       /* print_r("<pre>");
        print_r($content);
        print_r("</pre>");*/
        $pack = $fingerprinting->classify(false,$content);
        $probabilities = $pack['prob'];
        $package['categories'] = array_slice($probabilities,0,5,true);
        return $package;
    }

    protected function simpleBayesTest($testTerms,$naive){
        $package = [];
        $cat= array_keys($testTerms)[0];
        $content = $testTerms[$cat];
        $probabilities =$naive->classifyDocument($content);
        $package['categories'] = array_slice($probabilities,0,5,true);
        return $package;
    }

    protected function simpleKnnTest($testTerms,$dataTerms,$knn){
        $package = [];
        $k = 15;
        $cat= array_keys($testTerms)[0];
        $content = $testTerms;
        $sim = new CosineSimilarity();
        $categories=[];
        $data = $knn->cosineSim($content,$sim);
        arsort($data);
        //get the top K neighbours
        $topK = array_slice($data,0,$k,true);
        //get categories of them (general or specific)
        foreach ($topK as $c =>$value) {
            $a=trim(strtolower(strstr($dataTerms[$c]->getArticleID()->getCategory(), ' ')));
            $categories[] = $a;
        }
        $weighting = [];
        $dist = array_slice($topK,0,$k);
        //summing up similarities for different classes
        foreach($dist as $i => $v){
            $weighting[$categories[$i]] = $weighting[$categories[$i]]+($dist[$i]);
        }
        //sort for biggest similarity
        arsort($weighting);
        $package['categories'] = array_slice($weighting,0,5,true);
        return $package;
    }
    protected function testSemanticFingerprinting($testData,$dataTerms,$fingerprinting)
    {
        $right = 0;
        $errors = [];
        $package = [];
        $probabilities =[];
        // $testData = array_slice($testData,0,1,true);
        foreach($testData as $key => $value){
            $id = $dataTerms[$key]->getArticleID()->getUid();
            $cat= trim(strtolower(strstr($testData[$key]->getArticleID()->getCategory(), ' ')));
            $pack = $fingerprinting->classify($testData[$key],false);
            $probabilities = $pack['prob'];

            $overlaps = $pack['over'];
            $predictedCat = current(array_keys($probabilities));
            //compare it with the selected category
            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;
            }else{
                if (!isset($errors[$predictedCat.'---'.$cat])) {
                    $errors[$predictedCat.'---'.$cat] = 0;
                }
                $errors[$predictedCat.'---'.$cat]++;
                /*  print_r("<br>");
                  print_r($key);
                  print_r("<br>");
                  print_r($id);
                  print_r("<br>");
                  print_r("<pre>");
                  print_r($overlaps);
                  print_r("</pre>");
                  print_r("<pre>");
                  print_r($probabilities);
                  print_r("</pre>");
                  print_r($predictedCat);
                  print("----");
                  print_r($cat);
                  print("<br>");*/
            }
        }
        arsort($errors);
      /*  print_r("<pre>");
        print_r($errors);
        print_r("</pre>");*/
        $package['categories'] = array_keys($probabilities);
        $package['countCats'] = sizeof($probabilities);
        $package['correct'] = $right;
        return $package;
    }
    protected function testNaiveBayes($testData,$dataTerms,$naive){
        $right = 0;
        $errors = [];
        $package = [];
        $probabilities = [];
        foreach($testData as $key => $value){
            $cat= trim(strtolower(strstr($testData[$key][0], ' ')));
            $id = $dataTerms[$key]->getArticleID()->getUid();
            $probabilities =$naive->classifyDocument($testData[$key][1]);
            $predictedCat = current(array_keys($probabilities));
            //compare it with the selected category
            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;
            }else{
                if (!isset($errors[$predictedCat.'---'.$cat])) {
                    $errors[$predictedCat.'---'.$cat] = 0;
                }
                $errors[$predictedCat.'---'.$cat]++;
                print_r("<br>");
                print_r($key);
                print_r("<br>");
                print_r($id);
                print_r("<br>");
                print_r("<pre>");
                print_r($probabilities);
                print_r("</pre>");
                print_r($predictedCat);
                print("----");
                print_r($cat);
                print("<br>");
            }
        }
        arsort($errors);
        print_r("<pre>");
        print_r($errors);
        print_r("</pre>");
        $package['categories'] = array_keys($probabilities);
        $package['countCats'] = sizeof($probabilities);
        $package['correct'] = $right;
        return $package;
    }
    protected function testKNN($testData,$dataTerms,$knn){
        $right = 0;
        $k = 15;
        $sim = new CosineSimilarity();
        $t = array_slice($testData,0,1,true);
        $errors = [];
        $package = [];
        $categoryNames = [];
        foreach($testData as $key => $test){
            //$key = 451;
            $id = $dataTerms[$key]->getArticleID()->getUid();
            $categories=[];
            //get specific Catgeory
            $cat= trim(strtolower(strstr($dataTerms[$key]->getArticleID()->getCategory(), ' ')));;
            $data = $knn->cosineSim($test,$sim);////neu!!!!
            //sort data big to low
            arsort($data);
            //get the top K neighbours
            $topK = array_slice($data,0,$k,true);
            //get categories of them (general or specific)
            foreach ($topK as $c =>$value) {
                //$a=explode(" ",$dataTerms[$c]->getArticleID()->getCategory());
                $a=trim(strtolower(strstr($dataTerms[$c]->getArticleID()->getCategory(), ' ')));
                $categories[] = $a;
            }
            $weighting = [];
            $dist = array_slice($topK,0,$k);
            //summing up similarities for different classes
            foreach($dist as $i => $v){
                $rank = $i+1;
                $weighting[$categories[$i]] = $weighting[$categories[$i]]+($dist[$i]);
                if(!isset($categoryNames[$categories[$i]])){
                    $categoryNames[$categories[$i]] = 0;
                }
                $categoryNames[$categories[$i]] ++;
            }
            //sort for biggest similarity
            arsort($weighting);
            /* $countCat = array_count_values($categories);
              arsort($countCat);
              print_r("<pre>");
              print_r($countCat);
              print_r("</pre>");*/
            //take the class with the most members
            //$predictedCat = current(array_keys($countCat));
            //get the highest similarity
            $predictedCat = current(array_keys($weighting));
            //compare it with the selected category
            if (strpos($cat, $predictedCat) !== false) {
                $right = $right +1;
            }else{
                if (!isset($errors[$predictedCat.'---'.$cat])) {
                    $errors[$predictedCat.'---'.$cat] = 0;
                }
                $errors[$predictedCat.'---'.$cat]++;
                print("<br>");
                print_r($key);
                print("<br>");
                print_r($id);
                print("<br>");
                print_r("<pre>");
                print_r($topK);
                print_r("</pre>");
                print_r("<pre>");
                print_r($categories);
                print_r("</pre>");
                print_r("<pre>");
                print_r($weighting);
                print_r("</pre>");
                print_r($predictedCat);
                print("----");
                print_r($cat);
                print("<br>");
            }
        }
        arsort($errors);
        print_r("<pre>");
        print_r($errors);
        print_r("</pre>");
        $package['categories'] = array_keys($categoryNames);
        $package['countCats'] = sizeof($categoryNames);
        $package['correct'] = $right;
        return $package;
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
                //$a=explode(" ",$dataTerms[$c]->getArticleID()->getCategory());
                $a=strtolower(strstr($dataTerms[$c]->getArticleID()->getCategory(), ' '));
                $categories[] = $a;
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
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
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