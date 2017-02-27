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
use NlpTools\Similarity\Euclidean;

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

        //innerhalb von texten unique wörter, aber innerhalb einer klasse nicht
        $this->trainVector= $this->tfidf();


    foreach($this->trainVector as $textID =>$values){
            $cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
            if(!array_key_exists($cat,$labels)){
                $labels[$cat] = 0;
            }
            $labels[$cat]++;
        }

        /*print_r("<pre>");
        print_r($this->trainVector);
        print_r("</pre>");*/

        $this->contextLabelMap = $this->createContextMap($labels);


        //get all words of each train class and count
         foreach($this->trainVector as $key => $doc){
             $cat = $this->trainingsData[$key]->getArticleID()->getCategory();
             $this->prepareClassifier($cat,array_keys($this->trainVector[$key]));
         }


     $sim = new CosineSimilarity();

        $similarity = $sim->similarity($this->data['sport'],$this->data['football']);
        print_r("<br>");
        print_r("SIMILARITY = ");
        print_r($similarity);
        print_r("<br>");

        //whole category for category fp
       foreach($this->data as $key => $words){
           $this->createCategoryFingerprints($key,$words);
        }

      // $this->createSimilarityMatrix();

      /*   $help = new Helper();
         $help->exportFingerprint("catfp_film",$this->categoryFingerprints['film']);
        $help->exportFingerprint("catfp_politics",$this->categoryFingerprints['politics']);*/
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
            if (!isset($this->data[$class][$term])) {
                $this->data[$class][$term] = 0;
             }
            $this->data[$class][$term]++;
        }

        ksort($this->data[$class]);
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
        $package = $this->createTestDataFingerprint($testArray);
        $fingerprint = $package['fp'];

       // $class = array_values($this->contextLabelMap)[$package['highestContext']];
        $class = $package['highestContext'];
        ksort($fingerprint);

        //$help = new Helper();
       // $help->exportFingerprint("testfp-filmPolitics02",$fingerprint);

        $sim = new CosineSimilarity();



        foreach($this->categoryFingerprints as $cat => $key){
            $probabilities[$cat] = 0;
            //semantic closeness!!!
            $probabilities[$cat] =  $sim->similarity($key,$fingerprint);
        }

      //topic overlaps
        $overlaps = [];
        foreach($fingerprint as $i => $k){
            if($k == 1){
                if(!array_key_exists($this->contextLabelMap[$this->contextMap[$i]],$overlaps)){
                    $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]=0;
                }
                $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]++;
            }
        }

        arsort($probabilities);

        print_r("<pre>");
        print_r($overlaps);
        print_r("</pre>");

       /* //weighting is möglich aber nicht immer von vorteil
       if(current(array_keys($probabilities)) != $class){
             $probabilities[$class] *= 1.5;
         }
         arsort($probabilities);*/

        //check if first two are equal
       $check = array_values(array_slice($probabilities,0,2,false));
        if($check[0] == $check[1]){
            $probabilities[$class] += 0.0001;
        }
        arsort($probabilities);

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


        //get durchschnitts classe in threshold
       $presentClass = [];
        foreach($threshold as $i => $k){
                if(!array_key_exists($this->contextLabelMap[$this->contextMap[$i]],$presentClass)){
                    $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]=0;
                }
            $presentClass[$this->contextLabelMap[$this->contextMap[$i]]]++;

        }

        arsort($presentClass);

        //return $threshold;
        $fpPackage['fp'] = $this->getTextSDR($tmp,$threshold);
        $fpPackage['highestContext'] =  current(array_keys($presentClass)); //array_slice($tmp,0,5,true);
        return $fpPackage;
    }


    protected function createContextMap($labels){

        $test= null;

        //foreach(array_keys($labels) as $class){
            foreach($this->trainVector as $textID => $val) {
                $indexSim = [];
                $cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));


            // if($class == $cat ){
                    for ($i = 0; $i < count($this->contextMap); $i++) {
                        $indexSim[$i] = $this->getSimilarity($this->trainVector[$textID], $this->trainVector[$this->contextMap[$i]]);
                    }
                    if (count($indexSim) == 0) {
                        $this->contextMap[0] = $textID;
                        $test[0] = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
                    } else {
                        arsort($indexSim);

                        $pastBehind = current(array_keys($indexSim)) + 1;


                        array_splice($this->contextMap, $pastBehind, 0, $textID);
                        array_splice($test, $pastBehind, 0, trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' '))));

                    }
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

    protected function calculateThreshold($stack){
        $arrayStack = array_fill(0,count($this->contextMap),0);
        //stack all word SDRs to get text SDR
        foreach($stack as $word => $array){
            for($i = 0; $i < count($array);$i++){
                $arrayStack[$i] += $array[$i];
            }
        }
        return $arrayStack;
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


    function getSimilarity($A,$B){
        $sim = new CosineSimilarity();
        $similarity =  $sim->similarity($A,$B);
        return $similarity;
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
        //$array = $help->stopWordsReduction($array);
        $array = $help->stemTerms($array);
        foreach($array as $k => $v){
            if(strlen($v) <3 || strlen($v) > 20 ){
                unset($array[$k]);
            }
        }

        return $array;
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


    function createSimilarityMatrix(){
        $sim = new Euclidean();
        $distances = [];

        $distances[0][0] = "className";
        $i= 0;
        foreach($this->data as $k1 => $v1){
            $class = $k1;
            $distances[$i+1][0]= $class;
            $j=0;
            foreach($this->data as $k2 => $v2){
                $class2 = $k2;
                $distances[0][$j+1] = $class2;
                $distances[$i+1][]= $sim->dist($v1,$v2);
                $j++;
            }
            $i++;
        }


        $fp = fopen('file_classSimEuclid.csv', 'w');

        foreach ($distances as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }

        fclose($fp);

    }




}