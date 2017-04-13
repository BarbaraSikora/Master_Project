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
     * @return null
     */
    public function getCategoryFingerprints()
    {
        return $this->categoryFingerprints;
    }


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

    public function simpleStart($data,$testTerms,$contextMap,$stacks){

        $labels = [];
        //nicht di echte ID in data terms[??]
        $this->dataTerms = $data;
        $count = count($data);

        $this->threshold = floor($count*0.5);


        print_r("<pre>");
        print_r('<br>------THRES-------------<br>');
        print_r($this->threshold);
        print_r('<br>-------------------<br>');
        print_r("</pre>");

        $this->trainingsData = array_slice($this->dataTerms, 0,$count,true);
        $this->testData = array_fill_keys($testTerms[array_keys($testTerms)[0]],array_keys($testTerms)[0]);
        $this->getTermsPerDoc();

        // trainvector nur benötigt für termliste pro dokument

        if($contextMap) {
            $this->contextMap = explode(" ", $contextMap);
        }
            $this->contextLabelMap = $this->createContextMap($labels);


          foreach($this->trainVector as $key => $doc){
              $cat = $this->contextLabelMap[$key];
              $this->prepareClassifier($cat,array_keys($this->trainVector[$key]));
          }

        foreach($stacks as $key => $words){
            $this->createCategoryFingerprints($key,$words);
        }



/*


          foreach($this->data as $key => $words){
              $this->createCategoryFingerprints($key,$words);
          }*/






    }

    ###################VERÄNDERT######################
    public function startSemanticFingerprinting($data,$contextMap,$wordStacks,$factor){
        $labels = null;
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $trainingNumb = ceil($count*0.80);
        $testingNumb = floor($count*0.20);

        ###################VERÄNDERT######################
        if($factor){
            $this->threshold = $factor;
        }else{
            $this->threshold = floor($trainingNumb*0.5);
        }

        ###################VERÄNDERT######################

        print_r("<pre>");
        print_r('<br>------THRES-------------<br>');
        print_r($this->threshold);
        print_r('<br>-------------------<br>');
        print_r("</pre>");

        $help->shuffle_assoc($this->dataTerms);

        $this->trainingsData = array_slice($this->dataTerms, 0,$trainingNumb,true);
        $this->testData = array_slice($this->dataTerms, $trainingNumb,$testingNumb,true);

        //innerhalb von texten unique wörter, aber innerhalb einer klasse nicht
        //jedes doc mit key und allen wörter + weights

###################VERÄNDERT######################
    // HIER FAIL WARUM??????? ööööööööööööööööööööööööööööööö ahahhhhh
        if($contextMap){
            print_r("hhhhola");
            $this->getTermsPerDoc();
        }else{
            $this->tfidf(false);
        }


###################VERÄNDERT######################

        // zählt wie viele docs es für welche klasse gibt => $labels
      /*  foreach($this->trainVector as $textID =>$values){
            $cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
            if(!array_key_exists($cat,$labels)){
                $labels[$cat] = 0;
            }
            $labels[$cat]++;
        }*/

        if($contextMap){
            $this->contextMap = explode(" ", $contextMap);

            ############  VERSUCH 1
            //nur die IDS di in trainingsdata sind dürfen in contextmap enthalten sein
            foreach($this->contextMap as $key => $value){
                if(!isset($this->trainingsData[$value])){
                    unset($this->contextMap[$key]);
                }
            }

            ################### VERSUCH 2 dauert zu lange
           /* //oooder alle anderen müssen data terms enthalten bleiben und nur test data ids weg
            foreach($this->testData as $key => $object){
                unset($this->contextMap[array_search($key, $this->contextMap)]);
            }*/

            $this->contextMap = array_filter($this->contextMap);
            $this->contextMap = array_values($this->contextMap);
        }

        #TODO
        $this->contextLabelMap = $this->createContextMap($labels);


###################VERÄNDERT######################

        /*print_r("<pre>");
        print_r('<br>-------------------<br>');
       print_r(count($this->contextLabelMap));
        print_r('<br>-------------------<br>');
        print_r(count($this->contextMap));
        print_r("</pre>");*/

        //get all words of each train class and count
        //erstellt data klasse und alle wörter die darin vorkommen mit häufigkeit in der klasse
         foreach($this->trainVector as $key => $doc){
             $cat = $this->contextLabelMap[$key];
             //$cat = $this->trainingsData[$key]->getArticleID()->getCategory();
             $this->prepareClassifier($cat,array_keys($this->trainVector[$key]));
         }

        /*print_r("<pre>");
        print_r($this->data);
        print_r("</pre>");*/

      /*  $sim = new CosineSimilarity();
         $similarity = $sim->similarity($this->data['sport'],$this->data['football']);
         print_r("<br>");
         print_r("SIMILARITY = ");
         print_r($similarity);
         print_r("<br>");*/

        ###################VERÄNDERT######################
        //whole category for category fp

        if($wordStacks){
            foreach($wordStacks as $key => $stacks){
                $this->categoryFingerprints[$key] = $stacks;
                $this->createCategoryFingerprints($key,$stacks);
            }
        }else{
            foreach($this->data as $key => $words){
                $this->createCategoryFingerprints($key,$words);
            }
        }


      //whole category for category fp

 ###################VERÄNDERT######################
         /*print_r("<pre>");
         print_r($this->categoryFingerprints);
         print_r("</pre>");*/

      // $this->createSimilarityMatrix();

      /*   $help = new Helper();
         $help->exportFingerprint("catfp_film",$this->categoryFingerprints['film']);
        $help->exportFingerprint("catfp_politics",$this->categoryFingerprints['politics']);*/
    }

    protected function createContextMap($labels){

        $test= null;
        ###################VERÄNDERT######################
    if(!$this->contextMap) {
        ###################VERÄNDERT######################
        //foreach(array_keys($labels) as $class){
        foreach ($this->trainVector as $textID => $val) {
            $indexSim = [];
            $cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));


            // if($class == $cat ){
            //sammelt alle similarities
            for ($i = 0; $i < count($this->contextMap); $i++) {
                $indexSim[$i] = $this->getSimilarity($this->trainVector[$textID], $this->trainVector[$this->contextMap[$i]]);
            }
            // wenn erstes doc eingefügt wird
            if (count($indexSim) == 0) {
                $this->contextMap[0] = $textID;
                $test[0] = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
            } else {
                // finde das doc mit höchster similarity
                arsort($indexSim);
                // berechne die position zum einfügen
                $pastBehind = current(array_keys($indexSim)) + 1;
                // füge die ID an der richtigen stelle ein
                array_splice($this->contextMap, $pastBehind, 0, $textID);
                array_splice($test, $pastBehind, 0, trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' '))));

            }
            // }
        }
        // }
        ###################VERÄNDERT######################
    }
        ###################VERÄNDERT######################
        $context_label_map = null;
        foreach($this->contextMap as $val){
            $cat = $this->dataTerms[$val]->getArticleID()->getCategory();
            // $cat = explode(" ",$cat);
            $cat=trim(strtolower(strstr($cat, ' ')));
            // $this->contextLabelMap[$cat][]=$val ;
            $context_label_map[$val] =$cat;
        }

        return $context_label_map;
    }

    // protected function prepareClassifier($class, $termArray,$key){
    protected function prepareClassifier($class, $termArray){
        //$cat = explode(" ",$class);
        //$cat=trim(strtolower(strstr($class, ' ')));
        //$class = $cat;

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

    protected function createCategoryFingerprints($class,$words){

        ###################VERÄNDERT######################
        if(!$this->categoryFingerprints[$class]){
            $stack = null;
            $this->categoryFingerprints[$class] = [];
            //erstellt leeren fingerprint so lang wie es documente im trainset gibt
            $categoryFP = array_fill(0,count($this->trainVector),0);


            foreach($words as $word => $numb){
                $categoryFP = $this->getStackOfWordSDRs($word,$categoryFP,$numb);
            }

            $stack[$class] = $categoryFP;
        }else{
            $stack[$class] = explode(" ",$words);
        }
         /*
        $this->categoryFingerprints[$class] = $categoryFP;*/

        ###################VERÄNDERT######################


        /* print_r("<pre>");
     print_r($categoryFP);
     print_r("</pre>");*/


           arsort($stack[$class]);

            //höchste stacksrausschneiden
           $threshold = array_slice($stack[$class],0,$this->threshold,true);

            //zurücksetzen auf 0n und 1en
            $this->categoryFingerprints[$class] = $this->getTextSDR($stack[$class],$threshold);

            //$this->categoryFingerprints[$class] = $this->getWeightedTextSDR($stack[$class],$threshold,array_sum($stack[$class]));

            // 0 -> max id aufsteigend sortiern
            ksort($this->categoryFingerprints[$class]);


    }

    protected function getStackOfWordSDRs($word,$categoryFP,$numb){
        foreach($this->contextMap as $key => $index) {
            if(array_key_exists($word,$this->trainVector[$index])){
                $categoryFP[$key] += 1*$numb; /* *numb */
            }else{
                $categoryFP[$key] += 0;
            }
        }
        return $categoryFP;
    }

    protected function getTextSDR($stackedFP,$threshold){
        $array = null;
        //only biggest stacks are marked in the text SDR
        foreach($stackedFP as $id => $value){
            if(array_key_exists($id,$threshold)){
                $array[$id] = 1;
            }else{
                $array[$id] = 0;
            }
        }
        return $array;
    }

    protected function getWeightedTextSDR($stackedFP,$threshold,$total){
        $array = null;

        $totalNumbWords = $total;
        print_r("<pre>");
        print_r("---".$totalNumbWords."---");
        print_r("</pre>");

        print_r("<pre>");
        print_r($threshold);
        print_r("</pre>");
        //only biggest stacks are marked in the text SDR
        foreach($stackedFP as $id => $value){
            if(array_key_exists($id,$threshold)){
                $array[$id] = $value/$totalNumbWords;
            }else{
                $array[$id] = 0;
            }
        }
        return $array;
    }

    public function classify($testDoc,$testTerms){
        if(!$testTerms){
            $testTerms = $this->prepareData($testDoc->getTerms());
        }


        //neu unique machen test data ??? besser oder schlechter
         $testTerms = array_unique($testTerms);

        $probabilities = [];
        $package = $this->createTestDataFingerprint($testTerms);
        $fingerprint = $package['fp'];

       // $class = array_values($this->contextLabelMap)[$package['highestContext']];
        $class = $package['highestContext'];
        ksort($fingerprint);

        /*print_r("<pre>");
        print_r($fingerprint);
        print_r("</pre>");*/

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
            if($k > 0){
                if(!array_key_exists($this->contextLabelMap[$this->contextMap[$i]],$overlaps)){
                    $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]=0;
                }
                $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]++;
            }
        }

        arsort($probabilities);



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
        $pack['prob'] = $probabilities;
        $pack['over'] = $overlaps;
       return $pack;
    }

    function createTestDataFingerprint($testTerms){
        $stack = null;
        $testFP = array_fill(0,count($this->contextMap),0);

        //create stacked word SDRs
        foreach($testTerms as $k => $word){
            $testFP = $this->getStackOfWordSDRs($word,$testFP,1);
        }

        $tmp = $testFP;
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
      // $fpPackage['fp'] = $this->getWeightedTextSDR($tmp,$threshold,count($testTerms));
        $fpPackage['highestContext'] =  current(array_keys($presentClass)); //array_slice($tmp,0,5,true);
        return $fpPackage;
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



    protected function getStackOfTextSDRs($text,$array){
        foreach($text as $key => $val) {
            $array[$key] += $text[$key];
        }
        return $array;
    }

//  ############HELPER##################

    function getSimilarity($A,$B){
        $sim = new CosineSimilarity();
        $similarity =  $sim->similarity($A,$B);
        return $similarity;
    }

    function getTermsPerDoc(){
        foreach($this->trainingsData as $key => $document) {
            $content = $document->getTerms();
            $array = $this->prepareData($content);
            $this->trainVector[$key] = array_fill_keys($array,$key);
        }
    }


    function tfidf($testTerms){
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
        foreach($this->trainingsData as $key => $d){
            $allValues[$key] = $ff->getFeatureArray("", $trainSet[$i]);
            $i++;
        }

        $this->trainVector = $allValues;

        if($testTerms){
            return $ff->getFeatureArray("", $trainSet[$i])  ;
        }else{
            return 1;
        }

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