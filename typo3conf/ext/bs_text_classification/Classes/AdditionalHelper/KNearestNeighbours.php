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

    public function startKnn($data){
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $this->training = ceil($count*0.67);
        $this->testing = floor($count*0.33);

        $this->dataVectors= $this->tfidf();
        $help->shuffle_assoc($this->dataVectors);

        $this->trainingsData = array_slice($this->dataVectors, 0,$this->training,true);
        $this->testData = array_slice($this->dataVectors, $this->training,$this->testing,true);

      /*  print_r("<pre>");
        print_r($this->testData);
        print_r("</pre>");*/
    }

     function norm(array $vector) {
        return sqrt($this->dotProduct($vector, $vector));
    }

     function dotProduct(array $a, array $b) {
        $dotProduct = 0;
        // to speed up the process, use keys with non-empty values
        $keysA = array_keys(array_filter($a));
        $keysB = array_keys(array_filter($b));
        $uniqueKeys = array_unique(array_merge($keysA, $keysB));
        foreach ($uniqueKeys as $key) {
            if (!empty($a[$key]) && !empty($b[$key]))
                $dotProduct += ($a[$key] * $b[$key]);
        }
        return $dotProduct;
    }

    public function cosinus(array $a, array $b) {
        $normA = $this->norm($a);
        $normB = $this->norm($b);
        return (($normA * $normB) != 0)
            ? $this->dotProduct($a, $b) / ($normA * $normB)
            : 0;
    }


    function cosineSim($testID,$sim){
        $distances = [];
      foreach($this->trainingsData as $key => $value){
            $distances[$key] = $sim->similarity($this->testData[$testID],$this->trainingsData[$key]);
      }
        return $distances;
    }


    function tfidf(){
        $trainSet = new TrainingSet();
        $help = new Helper();
        foreach($this->dataTerms as $document){
            $content = $document->getTerms();
            //remove numbers
            $content = preg_replace('/[0-9]+/', '', $content);
            //stemming
            $array =  explode(" ",$content);
            $array = $help->stemTerms($array);
            foreach($array as $k => $v){
                if(strlen($v) <3){
                    unset($array[$k]);
                }
            }
            $trainSet->addDocument(
                "",
                new TokensDocument(
                   //explode(" ",$content)
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

        for($i = 0; $i <count($trainSet);$i++){
            $allValues[$i] = $ff->getFeatureArray("", $trainSet[$i]);
        }

        return $allValues;
    }

    //TO DO :
    /*
     * bag of words testen
     * zufällig trainingsdaten/testdaten zusammenstellen -> array splitten
     * bag of words testen
     *
     *
     */

}