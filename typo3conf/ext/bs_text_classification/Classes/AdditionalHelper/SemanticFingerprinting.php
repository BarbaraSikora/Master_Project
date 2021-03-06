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
    protected $customThreshold=35;
    protected $threshold=35;
    protected $weighting = null;


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


    public function resetValues(){
     /*   $this->trainingsData = null;
       $this->dataTerms = null;
        $this->testData = null;
        $this->contextMap = null;
        $this->trainVector = null;
        $this->simMatrix = null;
        $this->contextLabelMap = null;
       $this->data = null;*/
        $this->categoryFingerprints = null;
        //$this->threshold=35;

    }


    public function simpleStart($data,$testTerms,$contextMap,$stacks){

        $labels = [];
        //nicht di echte ID in data terms[??]
        $this->dataTerms = $data;
        $count = count($data);

        $this->threshold = floor($count*0.5);

        $this->trainingsData = array_slice($this->dataTerms, 0,$count,true);
        $this->testData = array_fill_keys($testTerms[array_keys($testTerms)[0]],array_keys($testTerms)[0]);
        $this->getTermsPerDoc();

        // trainvector nur ben�tigt f�r termliste pro dokument

        if($contextMap) {
            $this->contextMap = explode(" ", $contextMap);
        }
            $this->contextLabelMap = $this->createContextMap($labels);


          foreach($this->trainVector as $key => $doc){
              $cat = $this->contextLabelMap[$key];
              $this->prepareClassifier($cat,array_keys($this->trainVector[$key]));
          }

       /* foreach($stacks as $key => $words){
            $this->createCategoryFingerprints($key,$words);
        }*/


        if($stacks){
            foreach($stacks as $key => $words){
                $this->categoryFingerprints[$key] = $words;
                $this->createCategoryFingerprints($key,$words);
            }
        }else{
            foreach($this->data as $key => $words){
                $this->createCategoryFingerprints($key,$words);
            }
        }


    }

    ###################VER�NDERT######################
    public function startSemanticFingerprinting($data,$contextMap,$wordStacks,$factor,$customThres){
        $labels = null;
        $this->dataTerms = $data;
        $count = count($data);
        $help = new Helper();
        $trainingNumb = ceil($count*0.67);
        $testingNumb = floor($count*0.33);

        ###################VER�NDERT######################
        if($factor){
            $this->threshold = $factor;
        }else{
            $this->threshold = floor($trainingNumb*0.5);
        }

        if($customThres){
            $this->customThreshold = $customThres;
        }else{
            $this->customThreshold = $this->threshold;
        }

        ###################VER�NDERT######################

        print_r("<pre>");
        print_r('<br>------THRES: ');
        print_r($this->threshold);
        print_r('<br>CUSTOM-THRES: ');
        print_r($this->customThreshold);
        print_r('<br>');
        print_r("</pre>");

        $help->shuffle_assoc($this->dataTerms);

        $this->trainingsData = array_slice($this->dataTerms, 0,$trainingNumb,true);
       // $this->trainingsData = array_slice($this->dataTerms, 0,$count,true);
        $this->testData = array_slice($this->dataTerms, $trainingNumb,$testingNumb,true);

        //innerhalb von texten unique w�rter, aber innerhalb einer klasse nicht
        //jedes doc mit key und allen w�rter + weights

###################VER�NDERT######################

        if($contextMap){
           // print_r("hhhhola");
            $this->getTermsPerDoc();
        }else{
            $this->tfidf(false);
        }




###################VER�NDERT######################

        // z�hlt wie viele docs es f�r welche klasse gibt => $labels
        foreach($this->trainVector as $textID =>$values){
            //$cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
            $cat =   explode(" ",$this->dataTerms[$textID]->getArticleID()->getCategory())[0]; //HERE!!!

            if(!array_key_exists($cat,$labels)){
                $labels[$cat] = 0;
            }
            $labels[$cat]++;
        }

        if($contextMap){
            $this->contextMap = explode(" ", $contextMap);

            ############  VERSUCH 1
            //nur die IDS di in trainingsdata sind d�rfen in contextmap enthalten sein
            foreach($this->contextMap as $key => $value){
                if(!isset($this->trainingsData[$value])){
                    unset($this->contextMap[$key]);
                }
            }

            $this->contextMap = array_filter($this->contextMap, function($value) { return $value !== ''; });
            $this->contextMap = array_values($this->contextMap);

        }

        #TODO
        $this->contextLabelMap = $this->createContextMap($labels);




###################VER�NDERT######################

      /*  print_r("<pre>");
        //print_r('<br>-------------------<br>');
       print_r($this->trainVector);
       // print_r('<br>-------------------<br>');
        //print_r(count($this->contextMap));
        print_r("</pre>");*/

        //get all words of each train class and count
        //erstellt data klasse und alle w�rter die darin vorkommen mit h�ufigkeit in der klasse
         foreach($this->trainVector as $key => $doc){
             $cat = $this->contextLabelMap[$key];
            // $cat = $this->dataTerms[$key]->getArticleID()->getCategory();
             //$cat = $this->trainingsData[$key]->getArticleID()->getCategory();
             $this->prepareClassifier($cat,array_keys($this->trainVector[$key]));
            /* if(!isset($this->categoryFingerprints[$cat])){
                 $this->createSingleTextCategoryFingerprints($cat,$key);
             }*/
         }

       // $this->testTFIDF();


      /*   $sim = new CosineSimilarity();
          //  $similarity = $sim->similarity($this->data['fashion'],$this->data['football']);
        $simArray = [];
            $array = array_values($this->data);
            for($i = 0; $i < 4; $i++){
              for($j = 1; $j < 4; $j++) {
                  if(!isset($simArray[$i . "-" . $j])&&!isset($simArray[$j . "-" . $i]) && $i != $j){
                      $simArray[$i . "-" . $j] = $sim->similarity($array[$i], $array[$j]);
                  }
              }
            }

         print_r("<pre>");
        print_r(array_keys($this->data));
         print_r("SIMILARITY = ");
         print_r($simArray);
         print_r("</pre>");*/

        ###################VER�NDERT######################
        //whole category for category fp

        if($wordStacks){
           // print_r("::::::hhhhola");
            foreach($wordStacks as $key => $stacks){
                $this->categoryFingerprints[$key] = $stacks;
                $this->createCategoryFingerprints($key,$stacks);
            }
        }else{
            foreach($this->data as $key => $words){
                $this->createCategoryFingerprints($key,$words);
            }
        }

        //$this->checkTestData();

      //whole category for category fp

 ###################VER�NDERT######################
         /*print_r("<pre>");
         print_r($this->categoryFingerprints);
         print_r("</pre>");*/

      // $this->createSimilarityMatrix();

      /*   $help = new Helper();
         $help->exportFingerprint("catfp_film",$this->categoryFingerprints['film']);
        $help->exportFingerprint("catfp_politics",$this->categoryFingerprints['politics']);*/
    }

    protected function createContextMap($labels){
        ###################VER�NDERT######################
    if(!$this->contextMap) {
        ###################VER�NDERT######################
        foreach(array_keys($labels) as $class){
        foreach ($this->trainVector as $textID => $val) {
            $indexSim = [];
            //$cat = trim(strtolower(strstr($this->dataTerms[$textID]->getArticleID()->getCategory(), ' ')));
            $cat =   explode(" ",$this->dataTerms[$textID]->getArticleID()->getCategory())[0]; //HERE!!!

             if($class == $cat ){
            //sammelt alle similarities
            for ($i = 0; $i < count($this->contextMap); $i++) {
                $indexSim[$i] = $this->getSimilarity($this->trainVector[$textID], $this->trainVector[$this->contextMap[$i]]);
            }
            // wenn erstes doc eingef�gt wird
            if (count($indexSim) == 0) {
                $this->contextMap[0] = $textID;
            } else {
                // finde das doc mit h�chster similarity
                arsort($indexSim);
                // berechne die position zum einf�gen
                $pastBehind = current(array_keys($indexSim)) + 1;
                // f�ge die ID an der richtigen stelle ein
                array_splice($this->contextMap, $pastBehind, 0, $textID);
            }
             }
        }
         }
        ###################VER�NDERT######################
    }
        ###################VER�NDERT######################
        $context_label_map = null;
        foreach($this->contextMap as $val){
            $cat = $this->dataTerms[$val]->getArticleID()->getCategory();
            // $cat = explode(" ",$cat);
            //$cat=trim(strtolower(strstr($cat, ' ')));
            $cat =   explode(" ",$cat)[0]; //HERE!!!
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

        ###################VER�NDERT######################
        if(!$this->categoryFingerprints[$class]){
            $stack = null;
            $this->categoryFingerprints[$class] = [];
            //erstellt leeren fingerprint so lang wie es documente im trainset gibt
            $categoryFP = array_fill(0,count($this->trainVector),0);


            foreach($words as $word => $numb){
                // $val = $this->weighting[$class][$word];
                $categoryFP = $this->getStackOfWordSDRs($word,$categoryFP,$numb);
            }

            $stack[$class] = $categoryFP;
        }else{
            $stack[$class] = explode(" ",$words);
        }

       // $this->categoryFingerprints[$class] = $categoryFP;

        ###################VER�NDERT######################

          arsort($stack[$class]);

            //h�chste stacksrausschneiden
           $threshold = array_slice($stack[$class],0,$this->threshold,true);

            /*print_r("<pre>");
            print_r("<b>CategoryFP:</b>".$class." <br>");
            print_r($threshold);
            print_r("</pre>");*/

            //zur�cksetzen auf 0n und 1en
            $this->categoryFingerprints[$class] = $this->getTextSDR($stack[$class],$threshold);

            //$this->categoryFingerprints[$class] = $this->getWeightedTextSDR($stack[$class],$threshold,array_sum($stack[$class]));

            // 0 -> max id aufsteigend sortiern
            ksort($this->categoryFingerprints[$class]);

        //performance test
        $this->categoryFingerprints[$class] = array_filter($this->categoryFingerprints[$class]); // removes 0s


    }

    protected function getStackOfWordSDRs($word,$categoryFP,$numb){
        foreach($this->contextMap as $key => $index) {
            if(array_key_exists($word,$this->trainVector[$index])){
                $categoryFP[$key] +=  1*$numb; /* *$numb */
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
                $overlaps[$this->contextLabelMap[$this->contextMap[$i]]]++;
            }
        }

        arsort($overlaps);
        arsort($probabilities);



       /* //weighting is m�glich aber nicht immer von vorteil
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

        $threshold = array_slice($tmp,0,$this->customThreshold,true); //test data

       /* print_r("<pre>");
        print_r("<b>TestFP:</b> <br>");
        print_r($threshold);
        print_r("</pre>");*/


        //get durchschnitts classe in threshold
       $presentClass = [];
        foreach($threshold as $i => $k){
            $presentClass[$this->contextLabelMap[$this->contextMap[$i]]]++;
        }

        arsort($presentClass);


        //return $threshold;
        $fpPackage['fp'] = $this->getTextSDR($tmp,$threshold);

        $fpPackage['fp'] = array_filter($fpPackage['fp']);  //performance test

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
        $words=[];
        foreach($this->trainingsData as $key => $document) {
            $content = $document->getTerms();
            $cat = trim(strtolower(strstr($document->getArticleID()->getCategory(), ' ')));
            $array = $this->prepareData($content);

          //  $words[$cat][]=count($array);
            $this->trainVector[$key] = array_fill_keys($array,$key);

         /*  foreach ($array as $term => $iwas) {
                if (!isset($words[$iwas])) {
                    $words[$iwas] = 0;
                }
                $words[$iwas]++;
            }*/


        }

       /* print_r("<pre>");
        print_r(count($words));
        print_r("</pre>");

        print_r("<pre>");
        print_r(array_sum($words));
        print_r("</pre>");*/

     /*   $s=0;
        $sm = 0;
        $m= 0;
        $ml=0;
        $l =0;
        $xl =0;

        foreach($words as $class => $docs) {
           // asort($words[$class]);
            foreach($docs as $i => $charNumb) {
                if ($charNumb < 100) {
                    $s++;
                } else if ($charNumb >= 100 && $charNumb < 300) {
                    $sm++;
                } else if ($charNumb >= 300 && $charNumb < 500) {
                    $m++;
                } else if ($charNumb >= 500 && $charNumb < 800) {
                    $ml++;
                } else if ($charNumb >= 800 && $charNumb < 1000) {
                    $l++;
                } else if ($charNumb >= 1000) {
                    $xl++;
                }

            }

            print_r("<pre>");
            print_r("<b>".$class."</b><br>");
            print_r($s."<br>");
            print_r($sm."<br>");
            print_r($m."<br>");
            print_r($ml."<br>");
            print_r($l."<br>");
            print_r($xl."<br>");
            print_r("</pre>");

            $s=0;
            $sm = 0;
            $m= 0;
            $ml=0;
            $l =0;
            $xl =0;
        }*/


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

    function testTFIDF(){
        $trainSet = new TrainingSet();

        foreach($this->data as $class => $words){
            $trainSet->addDocument(
                "",
                new TokensDocument(
                    array_keys($this->data[$class])
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
        foreach($this->data as $class => $words){
            $allValues[$class] = $ff->getFeatureArray("", $trainSet[$i]);
            $i++;
        }

        $this->weigthing = $allValues;

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
        //$this->categoryFingerprints[$category] = [];

        $text = array_keys($this->trainVector[$id]);

        //erstellt leeren fingerprint so lang wie es documente im trainset gibt
        $categoryFP = array_fill(0,count($this->trainVector),0);


        foreach($text as $key => $word){
            $categoryFP = $this->getStackOfWordSDRs($word,$categoryFP,1,0);
        }

        $stack[$category] = $categoryFP;

        arsort($stack[$category]);

        //h�chste stacksrausschneiden
        $threshold = array_slice($stack[$category],0,$this->threshold,true);

        /*print_r("<pre>");
        print_r("<b>CategoryFP:</b>".$class." <br>");
        print_r($threshold);
        print_r("</pre>");*/

        //zur�cksetzen auf 0n und 1en
        $this->categoryFingerprints[$category] = $this->getTextSDR($stack[$category],$threshold);

        //$this->categoryFingerprints[$class] = $this->getWeightedTextSDR($stack[$class],$threshold,array_sum($stack[$class]));

        // 0 -> max id aufsteigend sortiern
        ksort($this->categoryFingerprints[$category]);

        //performance test
        $this->categoryFingerprints[$category] = array_filter($this->categoryFingerprints[$category]); // removes 0s
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

    protected function checkTestData(){
        $numbClass =[];
        $words = [];
        foreach($this->testData as $key => $doc){
            $class= trim(strtolower(strstr($this->testData[$key]->getArticleID()->getCategory(), ' ')));
            $terms = $testTerms = $this->prepareData($this->testData[$key]->getTerms());
            $terms = array_unique($terms);
            $words[$class][]=count($terms);
            /*if (!isset($numbClass[$class])) {
                $numbClass[$class] = 0;
            }

            $numbClass[$class]++;*/
        }
        /*print("<pre>");
        print_r($numbClass);
        print("</pre>");*/

        print("<pre>");
        print_r($words);
        print("</pre>");

        $s=0;
        $sm = 0;
        $m= 0;
        $ml=0;
        $l =0;
        $xl =0;

        foreach($words as $class => $docs) {
            // asort($words[$class]);
            foreach($docs as $i => $charNumb) {
                if ($charNumb < 100) {
                    $s++;
                } else if ($charNumb >= 100 && $charNumb < 300) {
                    $sm++;
                } else if ($charNumb >= 300 && $charNumb < 500) {
                    $m++;
                } else if ($charNumb >= 500 && $charNumb < 800) {
                    $ml++;
                } else if ($charNumb >= 800 && $charNumb < 1000) {
                    $l++;
                } else if ($charNumb >= 1000) {
                    $xl++;
                }

            }

            print_r("<pre>");
            print_r("<b>".$class."</b><br>");
            print_r($s."<br>");
            print_r($sm."<br>");
            print_r($m."<br>");
            print_r($ml."<br>");
            print_r($l."<br>");
            print_r($xl."<br>");
            print_r("</pre>");

            $s=0;
            $sm = 0;
            $m= 0;
            $ml=0;
            $l =0;
            $xl =0;
        }

    }

}