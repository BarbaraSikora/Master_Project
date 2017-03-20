<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 19.12.2016
 * Time: 12:34
 */

namespace TextClassification\BsTextClassification\Classes\AdditionalHelper;
include('C:\xampp\htdocs\Master_Project\typo3conf\ext\bs_text_classification\Resources\Private\Libraries\php-nlp-tools\autoloader.php');

use NlpTools\Analysis\Idf;
use NlpTools\Documents\TokensDocument;
use NlpTools\Documents\TrainingSet;
use NlpTools\Similarity\CosineSimilarity;
use NlpTools\Similarity\Euclidean;
use NlpTools\Utils\Normalizers\English;
use TextClassification\BsTextClassification\Domain\Model\EnglishTerms;

class KNearestNeighbours
{

    protected $dataTerms = null;

    protected $trainingsData = null;
    protected $testData = null;
    protected $dataVectors = null;
    protected $training = null;
    protected $testing = null;

    /**
     * @return array
     */
    public function getDataVectors()
    {
        return $this->dataVectors;
    }

    /**
     * @return null
     */
    public function getTestData()
    {
        return $this->testData;
    }


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
    public function getDataTerms()
    {
        return $this->dataTerms;
    }



    public function startKnn($data){
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $this->training = ceil($count*0.80);
        $this->testing = floor($count*0.20);

        $this->dataVectors= $this->tfidf($this->dataTerms);

        $help->shuffle_assoc($this->dataVectors);

        $this->trainingsData = array_slice($this->dataVectors, 0,$this->training,true);
        $this->testData = array_slice($this->dataVectors, $this->training,$this->testing,true);



    }

    public function createTestWeighting($termObjects){
        return $testVectorSpace = $this->tfidf($termObjects);
    }


    function cosineSim($testTerms,$sim){
        $distances = [];
       /* print_r("<pre>");
        print_r($this->testData[$testID]);
        print_r("</pre>");*/
     foreach($this->trainingsData as $key => $value){
            //$distances[$key] = $sim->similarity($this->trainingsData[$testID],$this->trainingsData[$key]);
            $distances[$key] = $sim->similarity($testTerms,$this->trainingsData[$key]);
      }
        return $distances;
    }

    protected function prepareData($content){
        $help = new Helper();
        //remove numbers
        $content = preg_replace('/[0-9]+/', '', $content);
        //stemming
        $array =  explode(" ",$content);
        $array = $help->stopWordsReduction($array);
        $array = $help->stemTerms($array);
        foreach($array as $k => $v){
            if(strlen($v) <3 || strlen($v) > 20 ){
                unset($array[$k]);
            }
        }
        return $array;
    }


    function tfidf($termObjects){
        $trainSet = new TrainingSet();

        foreach($termObjects as $document){
            $content = $document->getTerms();
            $array = $this->prepareData($content);

            $trainSet->addDocument(
                "",
                new TokensDocument(
                    $array
                )
            );

        }

        $allValues = [];
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
        foreach($termObjects as $key => $d){
            $allValues[$key] = $ff->getFeatureArray("", $trainSet[$i]);
            $i++;
        }

        return $allValues;
    }

}