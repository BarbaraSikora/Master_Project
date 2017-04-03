<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 09.01.2017
 * Time: 18:14
 */

namespace TextClassification\BsTextClassification\Classes\AdditionalHelper;
use NlpTools\Analysis\Idf;
use NlpTools\Documents\TokensDocument;
use NlpTools\Documents\TrainingSet;

include('C:\xampp\htdocs\Master_Project\typo3conf\ext\bs_text_classification\Resources\Private\Libraries\php-nlp-tools\autoloader.php');


class NaiveBayes
{

    protected $classes = null;
    protected $documents = null;
    protected $terms = null;
    protected $data = null;
    protected $trainingsData = null;
    protected $testData = null;
    protected $classTermsArray = null;


    /**
     * @return null
     */
    public function getTrainingsData()
    {
        return $this->trainingsData;
    }

    /**
     * @return null
     */
    public function getTestData()
    {
        return $this->testData;
    }

    public function simpleStart($data)
    {
        $count = count($data);
        $this->prepareData($data);
        $this->trainingsData = array_slice($this->classTermsArray, 0,$count,true);

        foreach($this->trainingsData as $key => $doc){
            $this->trainClassifier($this->trainingsData[$key][0],$this->trainingsData[$key][1]);
        }
    }


    public function startNaiveBayes($data)
    {
        $count = count($data);
        $help = new Helper();
        $trainingNumb = ceil($count*0.80);
        $testingNumb = floor($count*0.20);

        $this->prepareData($data);

      $help->shuffle_assoc($this->classTermsArray);

        $this->trainingsData = array_slice($this->classTermsArray, 0,$trainingNumb,true);
        $this->testData = array_slice($this->classTermsArray, $trainingNumb,$testingNumb,true);
       // $this->tfidf();
       foreach($this->trainingsData as $key => $doc){
            $this->trainClassifier($this->trainingsData[$key][0],$this->trainingsData[$key][1]);
        }

    }

    protected function prepareData($data)
    {

        $help = new Helper();
        foreach ($data as $key => $document) {
            $this->classTermsArray[$key][0] = $document->getArticleID()->getCategory();
            $content = $document->getTerms();
            //remove numbers
            $content = preg_replace('/[0-9]+/', '', $content);
            //stemming
            $array = explode(" ", $content);
            //reduces extra stop words
            $array = $help->stopWordsReduction($array);
            $array = $help->stemTerms($array);
            foreach($array as $k => $v){
                if(strlen($v) <3 || strlen($v) > 20 ){
                    unset($array[$k]);
                }
            }

            $this->classTermsArray[$key][1] = $array;
        }




    }

    protected function trainClassifier($class, $termArray){
       // $cat = explode(" ",$class);
        $cat=trim(strtolower(strstr($class, ' ')));
        $class = $cat;

        if (!isset($this->classes[$class])) {
            $this->classes[$class] = 0;
            $this->data[$class] = [];
            $this->documents[$class] = 0;
        }
        foreach ($termArray as $term) {
      //  foreach ($termArray as $term => $tfidf) {
            if (!isset($this->terms[$term])) {
                $this->terms[$term] = 0;
            }
            if (!isset($this->data[$class][$term])) {
                $this->data[$class][$term] = 0;
            }
         /*  $this->classes[$class] = $this->classes[$class]+$tfidf;
            $this->terms[$term]= $this->terms[$term]+$tfidf;
            $this->data[$class][$term]=$this->data[$class][$term]+$tfidf;*/
                $this->classes[$class]++;
               $this->terms[$term]++;
               $this->data[$class][$term]++;
           }
           $this->documents[$class]++;
       }


       public function classifyDocument($testDocument)
       {
           //total number of documents
        $totalDocCount = array_sum($this->documents);
        $scores = array();

        foreach ($this->classes as $class => $classCount) {
          $log = 0;
            //how many documents have this class
            $docCount = $this->documents[$class];
              $inversedDocCount = $totalDocCount - $docCount;
            //if trainingsData has only one category
            if ($inversedDocCount === 0) {
                continue;
            }

            foreach ($testDocument as $term) {
                  //how often appears this word in all documents in allen classen
                  $totalTokenCount = 0;
                    if(isset($this->terms[$term])){
                        $totalTokenCount = $this->terms[$term];
                    }

                  if ($totalTokenCount === 0) {
                    continue;
                }else{
                    //how often appears this word in this class
                    $tokenCount = 0;
                    if(isset($this->data[$class][$term])){
                        $tokenCount = $this->data[$class][$term];
                    }

                      $inversedTokenCount = $totalTokenCount - $tokenCount;
                     $wordProbability = $tokenCount / $classCount;
                      // $wordProbability = $tokenCount / $docCount;
                    $inversedWordProbability = $inversedTokenCount / (array_sum($this->classes)-$classCount);
                      // $inversedWordProbability = $inversedTokenCount / $inversedDocCount;
                      $probability = $wordProbability / ($wordProbability + $inversedWordProbability);
                      // wahrscheinlichkeit dass das wort in dieser klasse ist (mal der whs der klasse) dividiert durch
                      //die whs dass das wort überhaupt in irgendeiner klasse vorkommt

                    if ($probability === 0.0) {
                        $probability = 0.01;
                    } elseif ($probability === 1.0) {
                        $probability = 0.99;
                    }

                }

                //aufsummiern der wahrscheinlichkeiten
                $log += (log(1 - $probability) - log($probability));
               //$log +=  log($probability);

            }

            //invert log to get the probility back in in the 0 to 1 range
            $scores[$class] = 1 / (1 + exp($log));
          //$scores[$class] = exp($log);

          /*  print($class);
            print("<br>");
            print_r($log);
            print("<br>");
            print_r($scores[$class]);
            print("<br>");*/
        }
        arsort($scores, SORT_NUMERIC);
       return $scores;
    }


   function tfidf(){
        $trainSet = new TrainingSet();

        foreach($this->trainingsData as $key => $document){
            $content = $document[1];
           $trainSet->addDocument(
                "",
                new TokensDocument(
                //explode(" ",$content)
                    $content
                )
            );

        }

        $idf = new Idf($trainSet);

        $ff = new TfIdfFeatureFactory(
            $idf,
            array(
                function ($c, $d) {
                    return $d->getDocumentData();
                }
            )
        );
        $i = 0;
        foreach($this->trainingsData as $key => $d){
            $this->trainingsData[$key][1] = $ff->getFeatureArray("", $trainSet[$i]);
            $i++;
        }

    }


}