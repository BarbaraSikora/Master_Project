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
    protected $dataVectors = null;
    protected $simMatrix = null;
    protected $contextLabelMap = null;
    protected $data = null;
    protected $categoryFingerprints = null;
    protected $threshold=28;
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
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $trainingNumb = ceil($count*0.80);
        $testingNumb = floor($count*0.20);

        $help->shuffle_assoc($this->dataTerms);

        $this->trainingsData = array_slice($this->dataTerms, 0,$trainingNumb,true);
        $this->testData = array_slice($this->dataTerms, $trainingNumb,$testingNumb,true);

        $this->dataVectors= $this->tfidf();

        $array = $this->createContextMap();

        //sorting context array ??
        /*  asort($array);
       $sortedContextMap = array_keys($array);
      $this->contextMap = $sortedContextMap;

       /int("<pre>");
         print_r("<br>");
         print_r(  $array);
         print("</pre);*/


        /* $array = array_unique($array);

        /print("<pre>");
         print_r("<br>");
         print_r(  $array);
         print("</pre>");*/

        foreach($this->dataVectors as $key => $doc){
            $cat = $this->trainingsData[$key]->getArticleID()->getCategory();
            $this->prepareClassifier($cat,array_keys($this->dataVectors[$key]));
        }

       foreach($this->data as $key => $words){
            $this->createCategoryFingerprints($key,$words);
        }

        /*foreach($array as $key => $label){
            $this->createSingleTextCategoryFingerprints($label,$key);
        }*/

        //$this->createSingleTextCategoryFingerprints("football",429); //429 gut
        //$this->createSingleTextCategoryFingerprints("world news",131);//131,630,624 gut



        //$help = new Helper();
        //$help->exportFingerprint("world news",$this->categoryFingerprints['world news']);

        print("<pre>");
        print_r("<br>");
        print_r(  $this->categoryFingerprints);
        print("</pre>");


    }

    protected function prepareClassifier($class, $termArray){
        // $cat = explode(" ",$class);
        $cat=trim(strtolower(strstr($class, ' ')));
        $class = $cat;

        if (!isset($this->data[$class])) {
            $this->data[$class] = [];
        }
        foreach ($termArray as $term) {
            if (!isset($this->data[$class][$term])) {
                $this->data[$class][$term] = 0;
            }
            $this->data[$class][$term]++;
        }
    }

    function createSingleTextCategoryFingerprints($category,$id){
        $stack = null;
        $this->categoryFingerprints[$category] = [];

        $text = array_keys($this->dataVectors[$id]);

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
        $array = array_fill(0,count($this->dataVectors),0);

        foreach($words as $word => $numb){
            $array = $this->getStackOfWordSDRs($word,$array);
        }

        $stack[$category] = $array;
        arsort($stack[$category]);

        print("<pre>");
        print_r($stack);
        print("</pre>");


        $threshold = array_slice($stack[$category],0,$this->threshold,true);



        $this->categoryFingerprints[$category] = $this->getTextSDR($stack[$category],$threshold);
        ksort($this->categoryFingerprints[$category]);

    }

    public function classify($testDoc){
        $testArray = $this->prepareData($testDoc->getTerms());
        $probabilities = [];
        $fingerprint = $this->createTestDataFingerprint($testArray);
        ksort($fingerprint);


        print("<pre>");
        print_r($fingerprint);
        print("</pre>");

        foreach($this->categoryFingerprints as $cat => $key){
            $probabilities[$cat] = 0;
            //$test[$cat][0]=0;
            //$test[$cat][1]=0;
            for($i = 0; $i < count($key); $i++){
                //if($i < 122 && $cat=="football"){
                    if($key[$i] == $fingerprint[$i]){
                        $probabilities[$cat]++;
                        //$test[$cat][0]++;
                    }
               /* }else if($i >= 122 && $cat == "world news"){
                    if($key[$i] == $fingerprint[$i]){
                        $probabilities[$cat]++;
                        $test[$cat][1]++;
                    }
                }
*/
            }
            $probabilities[$cat] =  $probabilities[$cat]/count($key);
        }

        /*arsort($probabilities);
        print("<pre>");
        print_r($probabilities);
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
        $threshold = array_slice($tmp,0,28,true);

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
            if(array_key_exists($word,$this->dataVectors[$index])){
               $array[$key] = 1;
            }else{
                $array[$key] = 0;
            }
        }
        return $array;
    }
    protected function getStackOfWordSDRs($word,$array){


        foreach($this->contextMap as $key => $index) {
            if(array_key_exists($word,$this->dataVectors[$index])){
                $array[$key] += 1;
            }else{
                $array[$key] += 0;
            }
        }
        return $array;
    }




    function createContextMap(){

        $test= null;
        foreach(array_slice($this->dataVectors,0,count($this->dataVectors),true) as $key => $val){
            $indexSim = [];
            /*print("<br>");
            print_r($key);
            print("<br>");
            print_r($this->dataTerms[$key]->getArticleID()->getCategory());
            print("<br>");*/
            for($i = 0; $i < count($this->contextMap);$i++){
                $indexSim[$i] = $this->getSimilarity($this->dataVectors[$key],$this->dataVectors[$this->contextMap[$i]]);
            }
            if(count($indexSim)==0){
                $this->contextMap[0] = $key;
                $test[0] = $this->dataTerms[$key]->getArticleID()->getCategory();
            }else{
                arsort($indexSim);
                /*print("<pre>");
                print("<br>");
                print_r($indexSim);
                print("</pre>");*/
                $pastBehind = current(array_keys($indexSim));
              /*  print("<br>");
                print_r($pastBehind+1);*/
                array_splice( $this->contextMap, $pastBehind+1, 0, $key );
                array_splice( $test, $pastBehind+1, 0, $this->dataTerms[$key]->getArticleID()->getCategory() );

            }
            /*print("<pre>");
            print("<br>");
            print_r($this->contextMap);
            print("<br>");
            print_r($test);
            print("</pre>");*/
        }

        $array = null;
        foreach($this->contextMap as $val){
            $cat = $this->dataTerms[$val]->getArticleID()->getCategory();
            $cat=trim(strtolower(strstr($cat, ' ')));
            $this->contextLabelMap[$cat][]=$val ;
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
        foreach($this->dataVectors as $k1 => $v1){
            $id = $this->trainingsData[$k1]->getArticleID()->getUid();
            $distances[$k1+1][0]= $id;
            foreach($this->dataVectors as $k2 => $v2){
                $id2 = $this->trainingsData[$k2]->getArticleID()->getUid();
                $distances[0][$k2+1] = $id2;
                $distances[$k1+1][]= $sim->similarity($this->dataVectors[$k1],$this->dataVectors[$k2]);
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


}