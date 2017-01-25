<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 17.01.2017
 * Time: 16:57
 */

namespace TextClassification\BsTextClassification\Classes\AdditionalHelper;


use NlpTools\Analysis\Idf;
use NlpTools\Documents\TokensDocument;
use NlpTools\Documents\TrainingSet;
use NlpTools\Similarity\CosineSimilarity;

class SemanticFingerprinting
{

    protected $trainingsData = null;
    protected $dataTerms = null;
    protected $testData = null;
    protected $contextMap = null;
    protected $trainVector = null;
    protected $simMatrix = null;
    protected $contextLabelMap = null;
    protected $data = null;
    protected $categoryFingerprints = null;
    protected $threshold=35;
    /**
     * @return array
     */
    public function getTrainingsData()
    {
        return $this->trainingsData;
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return $this->testData;
    }

    /**
     * @return array
     */
    public function getContextMap()
    {
        return $this->contextMap;
    }

    public function startSemanticFingerprinting($data){
        $labels = null;
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $trainingNumb = ceil($count*0.80);
        $testingNumb = floor($count*0.20);

        $help->shuffle_assoc($this->dataTerms);

        $this->trainingsData = array_slice($this->dataTerms, 0,$trainingNumb,true);
        $this->testData = array_slice($this->dataTerms, $trainingNumb,$testingNumb,true);

        $this->trainVector= $this->tfidf(); //innerhalb von texten unique wörter, aber innerhalb einer klasse nicht

        /*print("<pre>");
        print_r("<br>");
        print_r($this->trainVector);
        print("</pre>");*/

        foreach($this->trainVector as $textID =>$values){
            $cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
            if(!array_key_exists($cat,$labels)){
                $labels[$cat] = 0;
            }
            $labels[$cat]++;
        }

        $this->contextLabelMap = $this->createContextMap($labels);

        print("<pre>");
        print_r("-------------------");
        print_r("<br>");
       print_r($this->contextLabelMap);
        print("</pre>");

        //sorting context array funzt ned wirkli
        /* asort($array);
       $sortedContextMap = array_keys($array);
     $this->contextMap = $sortedContextMap;*/



        //get all words of each train class and count
         foreach($this->trainVector as $key => $doc){
             $cat = $this->trainingsData[$key]->getArticleID()->getCategory();
             $this->prepareClassifier($cat,array_keys($this->trainVector[$key]));
         }

         //whole category for category fp
        foreach($this->data as $key => $words){
             $this->createCategoryFingerprints($key,$words);
         }

        print("<pre>");
        print_r("-------------------");
        print_r("<br>");
         //print_r($this->categoryFingerprints);
        print("</pre>");


       /* //single text for category fp
         $array = array_unique($array);

        print("<pre>");
        print_r("<br>");
        print_r($array);
        print("</pre>");

        foreach($array as $key => $label){
            $this->createSingleTextCategoryFingerprints($label,$key);
        }*/


      /*  $help = new Helper();
        $help->exportFingerprint("fashion",$this->categoryFingerprints['fashion']);
        $help->exportFingerprint("technology",$this->categoryFingerprints['technology']);*/


    }

   // protected function prepareClassifier($class, $termArray,$key){
    protected function prepareClassifier($class, $termArray){
        //$cat = explode(" ",$class);
        $cat=trim(strtolower(strstr($class, ' ')));
        $class = $cat;

        if (!isset($this->data[$class])) {
            $this->data[$class] = [];
        }
        foreach ($termArray as $term) {
            /// duplicate drinnen lassen!!!!!!  oder mitzählen
            if (!isset($this->data[$class][$term])) {
                $this->data[$class][$term] = 0;
             }
            $this->data[$class][$term]++;
        }
    }

    function createSingleTextCategoryFingerprints($category,$id){
        $stack = null;
        $this->categoryFingerprints[$category] = [];

        $text = array_keys($this->trainVector[$id]);

        //create all word SDRs
        foreach($text as $k => $word){
            $stack[$word] = $this->getWordSDR($word);

        }
        //stack all word SDRs to get text SDR
        foreach($stack as $word => $array){
            for($i = 0; $i < count($array);$i++){
                $this->categoryFingerprints[$category][$i] += $array[$i];
            }
        }

        $tmp = $this->calculateThreshold($stack);
        arsort($tmp);
        $threshold = array_slice($tmp,0,$this->threshold,true);

        $this->categoryFingerprints[$category] = $this->getTextSDR($tmp,$threshold);
        ksort($this->categoryFingerprints[$category]);
        /*print("<pre>");
          print_r($stack);
          print("</pre>"); */
    }

    function createCategoryFingerprints($category,$words){

        $stack = null;
        $this->categoryFingerprints[$category] = [];
        $array = array_fill(0,count($this->trainVector),0);


        foreach($words as $word => $numb){
            $array = $this->getStackOfWordSDRs($word,$array,$numb);
        }

        $stack[$category] = $array;
        arsort($stack[$category]);


        $threshold = array_slice($stack[$category],0,$this->threshold,true);


        $this->categoryFingerprints[$category] = $this->getTextSDR($stack[$category],$threshold);
        ksort($this->categoryFingerprints[$category]);

    }

    public function classify($testDoc){
        $testArray = $this->prepareData($testDoc->getTerms());
        $probabilities = [];
        $fingerprint = $this->createTestDataFingerprint($testArray);
        ksort($fingerprint);

        /*$help = new Helper();
        $help->exportFingerprint("testfp-fashionTech04",$fingerprint);*/

        $sim = new CosineSimilarity();

        /*print("<pre>");
        print_r($fingerprint);
        print("</pre>");*/

        foreach($this->categoryFingerprints as $cat => $key){

            $probabilities[$cat] = 0;
            //semantic closeness!!!
            $probabilities[$cat] =  $sim->similarity($key,$fingerprint);
        }


        $overlaps = [];
        foreach($fingerprint as $i => $k){
            if($k == 1){
                if(!array_key_exists($this->contextLabelMap[$this->contextMap[$i]],$overlaps)){
                    $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]=0;
                }
                $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]++;
            }
        }

        /*arsort($overlaps);
        print("<pre>");
        print_r($overlaps);
        print("</pre>");*/

        return $probabilities;
    }

    function createTestDataFingerprint($testArray){
        $stack = null;
        $fingerprint = [];
        //create all word SDRs
        foreach($testArray as $k => $word){
            $stack[$word] = $this->getWordSDR($word);
        }


       //get the highest values
        $tmp = $this->calculateThreshold($stack);
        arsort($tmp);


        $threshold = array_slice($tmp,0,$this->threshold,true);

//testen ob fingerprint richtig bei einem 428 test doc

        return $this->getTextSDR($tmp,$threshold);
    }

    protected function calculateThreshold($stack){
        $array = null;
        //stack all word SDRs to get text SDR
        foreach($stack as $word => $array){
            for($i = 0; $i < count($array);$i++){
                $array[$i] += $array[$i];
            }
        }
        return $array;
    }

    protected function getTextSDR($tmp,$threshold){
        $array = null;
        //only biggest stacks are marked in the text SDR
        foreach($tmp as $it => $value){
            if(array_key_exists($it,$threshold)){
                $array[$it] = 1;
            }else{
                $array[$it] = 0;
            }
        }
        return $array;
    }

    protected function getWordSDR($word){
        $array = null;

        foreach($this->contextMap as $key => $index) {
            if(array_key_exists($word,$this->trainVector[$index])){
               $array[$key] = 1;
            }else{
                $array[$key] = 0;
            }
        }
        return $array;
    }
    protected function getStackOfWordSDRs($word,$array,$numb){


        foreach($this->contextMap as $key => $index) {
            if(array_key_exists($word,$this->trainVector[$index])){
                $array[$key] += 1*$numb;
            }else{
                $array[$key] += 0;
            }
        }
        return $array;
    }

    protected function getStackOfTextSDRs($text,$array){

        foreach($text as $key => $val) {
                $array[$key] += $text[$key];
        }
        return $array;
    }




    protected function createContextMap($labels){

        $test= null;

        //foreach(array_keys($labels) as $class){
            foreach($this->trainVector as $textID => $val) {
                $indexSim = [];
                $cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));

              /*  print("<br>");
                print_r($textID);
                print("<br>");
                print_r($this->dataTerms[$textID]->getArticleID()->getCategory());
                print("<br>");*/

              //  if($class == $cat ){
                    for ($i = 0; $i < count($this->contextMap); $i++) {
                        $indexSim[$i] = $this->getSimilarity($this->trainVector[$textID], $this->trainVector[$this->contextMap[$i]]);
                    }
                    if (count($indexSim) == 0) {
                        $this->contextMap[0] = $textID;
                        $test[0] = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
                    } else {
                        arsort($indexSim);

                      /*  print("<pre>");
                        print("<br>");
                        print_r($indexSim);
                        print("</pre>");*/

                        $pastBehind = current(array_keys($indexSim)) + 1;

                        /*print("<br>");
                        print_r($pastBehind);*/

                        array_splice($this->contextMap, $pastBehind, 0, $textID);
                        array_splice($test, $pastBehind, 0, trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' '))));

                    }
                    /*print("<pre>");
                    print("<br>");
                    print_r($this->contextMap);
                    print("<br>");
                    print_r($test);
                    print("</pre>");*/
               // }
            }
       // }

        $array = null;
        foreach($this->contextMap as $val){
            $cat = $this->dataTerms[$val]->getArticleID()->getCategory();
           // $cat = explode(" ",$cat);
            $cat=trim(strtolower(strstr($cat, ' ')));
            // $this->contextLabelMap[$cat][]=$val ;
            $array[$val] =$cat;
        }

        return $array;
    }


    function getSimilarity($A,$B){
        $sim = new CosineSimilarity();
        $similarity =  $sim->similarity($A,$B);
        return $similarity;
    }


    function createSimilarityMatrix(){
        $sim = new CosineSimilarity();
        $distances = [];

        $distances[0][0] = "docID";
        foreach($this->trainVector as $k1 => $v1){
            $id = $this->trainingsData[$k1]->getArticleID()->getUid();
            $distances[$k1+1][0]= $id;
            foreach($this->trainVector as $k2 => $v2){
                $id2 = $this->trainingsData[$k2]->getArticleID()->getUid();
                $distances[0][$k2+1] = $id2;
                $distances[$k1+1][]= $sim->similarity($this->trainVector[$k1],$this->trainVector[$k2]);
            }
        }


        /*$fp = fopen('file.csv', 'w');

        foreach ($distances as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }

        fclose($fp);*/

        $this->simMatrix=$distances;
    }

    function tfidf(){
        $trainSet = new TrainingSet();

        foreach($this->trainingsData as $document){
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
        foreach($this->trainingsData as $key => $d){
            $allValues[$key] = $ff->getFeatureArray("", $trainSet[$i]);
            $i++;
        }

        return $allValues;
    }

    protected function prepareData($content){
        $help = new Helper();
        //remove numbers
        $content = preg_replace('/[0-9]+/', '', $content);
        //stemming
        $array =  explode(" ",$content);
        $array = $help->stemTerms($array);
        foreach($array as $k => $v){
            if(strlen($v) <3 || strlen($v) > 20 ){
                unset($array[$k]);
            }
        }
        return $array;
    }



}