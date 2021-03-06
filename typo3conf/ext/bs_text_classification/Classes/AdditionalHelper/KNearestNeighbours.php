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

    public function resetValues(){
         $this->dataTerms = null;
        $this->trainingsData = null;
        $this->testData = null;
        $this->dataVectors = null;
        $this->training = null;
        $this->testing = null;

    }


    /**
     * @return null
     */
    public function getDataTerms()
    {
        return $this->dataTerms;
    }

    public function simpleStart($data,$testTerms){
        $this->dataTerms = $data;
        $count = count($data);


        $this->testData = $this->tfidf($this->dataTerms,$testTerms);
        $this->trainingsData = array_slice($this->dataVectors, 0,$count,true);
    }


    public function startKnn($data){
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $this->training = ceil($count*0.80);// HERE!!!
        $this->testing = floor($count*0.20);

         $this->tfidf($this->dataTerms,false);

        $help->shuffle_assoc($this->dataVectors);

        $this->trainingsData = array_slice($this->dataVectors, 0,$this->training,true);
        $this->testData = array_slice($this->dataVectors, $this->training,$this->testing,true);



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


    function euclidSim($testTerms,$sim){
        $distances = [];
        foreach($this->trainingsData as $key => $value){
            $distances[$key] = $sim->dist($testTerms,$this->trainingsData[$key]);
        }
        return $distances;
    }




    protected function prepareData($content){
        $help = new Helper();
        //remove numbers
        $content = preg_replace('/[0-9]+/', '', $content);
        //stemming
        $array =  explode(" ",$content);
        $array = $help->stopWordsReduction($array); // HERE!!!
        $array = $help->stemTerms($array);
        foreach($array as $k => $v){
            if(strlen($v) <3 || strlen($v) > 20 ){
                unset($array[$k]);
            }
        }
        return $array;
    }


    function tfidf($termObjects,$testTerms){
        $trainSet = new TrainingSet();

        foreach($termObjects as $document){
            $content = $document->getTerms();
            $array = $this->prepareData($content);

            //check if empty places in array because of unset

            $trainSet->addDocument(
                "",
                new TokensDocument(
                    $array
                )
            );

        }

        if($testTerms){
            $trainSet->addDocument(
                "",
                new TokensDocument(
                    $testTerms[array_keys($testTerms)[0]]
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

        $this->dataVectors = $allValues;
        if($testTerms){
            return $ff->getFeatureArray("", $trainSet[$i])  ;
        }else{
            return 1;
        }

    }

}