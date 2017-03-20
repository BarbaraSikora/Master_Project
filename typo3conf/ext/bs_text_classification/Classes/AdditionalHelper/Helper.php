<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 12.12.2016
 * Time: 16:27
 */
namespace TextClassification\BsTextClassification\Classes\AdditionalHelper;
include('C:\xampp\htdocs\Master_Project\typo3conf\ext\bs_text_classification\Resources\Private\Libraries\php-nlp-tools\autoloader.php');
use NlpTools\Analysis\FreqDist;
use NlpTools\Analysis\Idf;
use NlpTools\Clustering\CentroidFactories\Euclidean;
use NlpTools\Similarity\CosineSimilarity;
use NlpTools\Stemmers\LancasterStemmer;
use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Tokenizers\PennTreeBankTokenizer;
use NlpTools\Tokenizers\RegexTokenizer;
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Utils\Normalizers\English;
use NlpTools\Utils\StopWords;



#######################################################
// HELPER FUNCTIONS
#######################################################
class Helper
{
    protected $stopwordsENG = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");
    protected $stopwordsENGAdditional = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the","a","able","about","across","after","all","almost","also","am","among","an","and","any","are","as","at","be","because","been","but","by","can","cannot","could","dear","did","do","does","either","else","ever","every","for","from","get","got","had","has","have","he","her","hers","him","his","how","however","i","if","in","into","is","it","its","just","least","let","like","likely","may","me","might","most","must","my","neither","no","nor","not","of","off","often","on","only","or","other","our","own","rather","said","say","says","she","should","since","so","some","than","that","the","their","them","then","there","these","they","this","tis","to","too","twas","us","wants","was","we","were","what","when","where","which","while","who","whom","why","will","with","would","yet","you","your","aint","arent","cant","couldve","couldnt","didnt","doesnt","dont","hasnt","hed","hell","hes","howd","howll","hows","id","ill","im","ive","isnt","its","mightve","mightnt","mustve","mustnt","shant","shed","shell","shes","shouldve","shouldnt","thatll","thats","theres","theyd","theyll","theyre","theyve","wasnt","wed","well","were","werent","whatd","whats","whend","whenll","whens","whered","wherell","wheres","whod","wholl","whos","whyd","whyll","whys","wont","wouldve","wouldnt","youd","youll","youre","youve");

    /**
     * action getData
     *
     * @return string
     */
    public function getData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * action everything_in_tags
     *
     * @return string
     */
    public function getEverythingBetweenTags($string, $tagname)
    {
        $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
        preg_match($pattern, $string, $matches);
        return $matches[1];
    }

    /**
     * action everything_with_class
     *
     * @return string
     */
    public function pregMatchAll($string, $starttag, $endtag)
    {
        $pattern = "#<$starttag>(.*?)</$endtag\b[^>]*>#s";
        preg_match_all($pattern, $string, $matches);
        return array_unique($matches[1]);
    }

    /**
     * action preprocessing data
     *
     * @return array
     */
    public function preprocessingData($string)
    {
        //deletes punctuation
        $string = trim(preg_replace("/[^0-9a-z ]+/i", "", $string));
        //to lower case
        $norm = new English();
        $string = $norm->normalize($string);

        $wtok = new WhitespaceTokenizer();
        $array = $wtok->tokenize($string);

        $stop = new StopWords($this->stopwordsENG);
        //$stem = new PorterStemmer();

        foreach ($array as $key => $value) {
            $array[$key] = $stop->transform($value);
           // $array[$key] = $stem->transform($value);
        }

        //leere arrayplätze eliminieren
        $array = array_filter($array);
        //$array = array_unique($array);

        //durchnummerieren
        $array = array_values($array);

        // $array = $stem->stemAll($array);
        return $array;
    }


    public function stemTerms($array){

        $stem = new PorterStemmer();
        foreach ($array as $key => $value) {
            $array[$key] = $stem->transform($value);
        }

        return $array;
    }

    public function stopWordsReduction($array){
        $stop = new StopWords($this->stopwordsENGAdditional);
        //$stem = new PorterStemmer();

        foreach ($array as $key => $value) {
            $array[$key] = $stop->transform($value);
        }

        return $array;
    }

    function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        srand(1000);
        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }

    /**
     * action getNodeList of query
     *
     * @return \DOMNodeList
     */
    public function getNodeList($query,$doc)
    {
        $finder= new \DomXPath($doc);
        $links = $finder->query($query);


        return $links;
    }

    /**
     * action getAllLinks
     *
     * @return array
     */
    public function getAllLinks($url,$array,$doc)
    {
        if(count($array) < 80){
            $text = $this->getData($url);
            $doc->loadHTML($text);

            $attr = "article";
            // //section//ul
            $nodelist = $this->getNodeList("//a[@data-link-name='$attr']/@href",$doc);
            foreach ($nodelist as $node) {
                $array[] =  "{$node->nodeValue}";
            }
            $array = array_unique($array);
            foreach($array as $key => $one) {
                    if(strpos($one, '/science/') == false || strpos($one, 'video') !== false || strpos($one, 'audio') !== false || strpos($one, 'picture') !== false || strpos($one, 'live') !== false || strpos($one, 'gallery') !== false) {
                        unset($array[$key]);
                    }
            }
            $array = array_values($array);

            $attr="next";
            $next = $this->getNodeList("//a[contains(@rel, '$attr')]/@href",$doc);
            $next = $next[0]->nodeValue;

            $array = $this->getAllLinks($next,$array,$doc);
       }
        return $array;
    }

    //FILTER DATA

    /**
     * action filter only big categories
     *
     * @return array
     */
    public function filterGeneralCategories($array){

        $newArray = [];

        foreach($array as $key => $value){
            $cat = explode(" ",$value->getArticleID()->getCategory());

            if( $cat[0] == "world"  || $cat[0] == "sport"){
                $newArray[$key] = $value;
            }

           /* if($cat[0] == "uk-news" || $cat[0] == "world"  || $cat[0] == "sport"  || $cat[0] == "fashion"  || $cat[0] == "football"){
               $newArray[$key] = $value;
            }*/
        }


        return $newArray;
    }

    /**
     * action filter only big categories
     *
     * @return array
     */
    public function filterSpecificCategories($array){

        $newArray = [];

        foreach($array as $key => $value){
            $cat = trim(strtolower(strstr($value->getArticleID()->getCategory(), ' ')));
             if($cat == "travel"  || $cat == "science"/*|| $cat == "fashion" || $cat == "technology"*/){
              //test fashion whs technology $numb raus?   film und politics guuut 0.8
                    $newArray[$key] = $value;
                }

            /*  if($cat == "sport"  || $cat == "football" || $cat == "fashion"  || $cat == "life and style"){
                    $newArray[$key] = $value;
                }*/

            /*if(  /*$cat == "sport" || $cat == "uk news"  || $cat == "opinion"  || $cat == "society"  || $cat == "business"||
                $cat == "politics" || $cat == "world news"  || $cat == "life and style"  || $cat == "environment" || $cat == "technology"
                   || $cat == "television & radio"  || $cat == "culture" || $cat == "art and design"  || $cat == "film"  || $cat == "books"
                   ||$cat == "us news"  || $cat == "football" || $cat == "fashion"  || $cat == "travel"  || $cat == "science"){  //20 categories
         $newArray[$key] = $value;
      }*/
        }


        return $newArray;
    }

    // EXPORT FUNCTIONS
    public function writeFile($data){
        $article = $data->getArticleID();
        $myfile = fopen("data/".$article->getUid().".txt", "w") or die("Unable to open file!");
        $txt = "Category: ".$article->getCategory()."\r\n\r\n".$article->getContent();
        fwrite($myfile, $txt);
        fclose($myfile);

        $myfile2 = fopen("terms/".$article->getUid().".txt", "w") or die("Unable to open file!");
        $txt2 = "Category: ".$article->getCategory()."\r\n\r\n".$data->getTerms();
        fwrite($myfile2, $txt2);
        fclose($myfile2);
    }

    public function exportGeneralCategory($dataTerms){
        $partCategories=[];
        foreach($dataTerms as $key => $term){
            $secndPart = explode(" ",$term->getArticleID()->getCategory())[0];
            if(isset($partCategories[$secndPart])){

                $partCategories[$secndPart][1]++;
                $partCategories[$secndPart][3] = $partCategories[$secndPart][3].$key."|";
                $partCategories[$secndPart][2] = $partCategories[$secndPart][2].$term->getArticleID()->getUid()."|" ;
            }else{
                $partCategories[$secndPart][0] = $secndPart;
                $partCategories[$secndPart][1] = 1;
                $partCategories[$secndPart][2] =$term->getArticleID()->getUid()."|" ;
                $partCategories[$secndPart][3] =$key."|" ;
            }
        }

        $fp = fopen('generalCategories_02.csv', 'w');

        foreach ($partCategories as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }

        fclose($fp);

    }

    public function exportCategories($dataTerms){
        $partCategories=[];
        foreach($dataTerms as $key => $term){
            $secndPart = strstr($term->getArticleID()->getCategory(), ' ');
            if(isset($partCategories[$secndPart])){

                $partCategories[$secndPart][1]++;
                $partCategories[$secndPart][3] = $partCategories[$secndPart][3].$key."|";
                $partCategories[$secndPart][2] = $partCategories[$secndPart][2].$term->getArticleID()->getUid()."|" ;
            }else{
                $partCategories[$secndPart][0] = $secndPart;
                $partCategories[$secndPart][1] = 1;
                $partCategories[$secndPart][2] =$term->getArticleID()->getUid()."|" ;
                $partCategories[$secndPart][3] =$key."|" ;
            }
        }

        $fp = fopen('partTestCategories_03.csv', 'w');

        foreach ($partCategories as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }

        fclose($fp);

    }

    public function exportExactCatgeory($dataTerms){

        $allCatgegories = [];
        foreach($dataTerms as $key => $term){
            if(isset($allCatgegories[$term->getArticleID()->getCategory()])){

                $allCatgegories[$term->getArticleID()->getCategory()][1]++;
                $allCatgegories[$term->getArticleID()->getCategory()][3] = $allCatgegories[$term->getArticleID()->getCategory()][3].$key."|";
                $allCatgegories[$term->getArticleID()->getCategory()][2] = $allCatgegories[$term->getArticleID()->getCategory()][2].$term->getArticleID()->getUid()."|" ;
            }else{
                $allCatgegories[$term->getArticleID()->getCategory()][0] = $term->getArticleID()->getCategory();
                $allCatgegories[$term->getArticleID()->getCategory()][1] = 1;
                $allCatgegories[$term->getArticleID()->getCategory()][2] =$term->getArticleID()->getUid()."|" ;
                $allCatgegories[$term->getArticleID()->getCategory()][3] =$key."|" ;
            }

        }

        $fp = fopen('allCatgegories_02.csv', 'w');

        foreach ($allCatgegories as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }
        fclose($fp);

    }

    public function exportComparisonTwoFiles($knn){
        $dataVector = $knn->getDataVectors();
        $dataTerms = $knn->getDataTerms();
        $sim = new CosineSimilarity();

        $id1=rand(0,494);
        $id2 =rand(0,494);
        $a = $dataVector[$id1];
        $b = $dataVector[$id2];
        $similarity = $sim->similarity($a,$b);
        ksort($a);
        ksort($b);
        $a=array_keys($a);
        $b=array_keys($b);


        $distances[0][0] ="Document 1";
        $distances[0][1] ="Document 2";
        $distances[0][2] ="Similarity";


       $distances[0][3] ="Words1";
       for($i = 1; $i< count($a)+1;$i++){
           $distances[$i][0] = "";
           $distances[$i][1] = "";
           $distances[$i][2] = "";
           $distances[$i][3]=$a[$i];
       }

       $distances[0][4] ="Words2";
       for($i = 1; $i< count($b)+1;$i++){
           $distances[$i][0] = "";
           $distances[$i][1] = "";
           $distances[$i][2] = "";
           $distances[$i][4]=$b[$i];
       }

       $distances[1][0] = $dataTerms[$id1]->getArticleID()->getUid();
       $distances[2][0] = $dataTerms[$id1]->getArticleID()->getCategory();
       $distances[1][1] = $dataTerms[$id2]->getArticleID()->getUid();
       $distances[2][1] = $dataTerms[$id2]->getArticleID()->getCategory();
       $distances[1][2] =$similarity;

        $fp = fopen('file01.csv', 'w');

        foreach ($distances as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }

        fclose($fp);

    }

    public function exportMDS($knn){

        $dataVector = $knn->getDataVectors();
        $dataTerms = $knn->getDataTerms();
        $sim = new CosineSimilarity();
        $distances = [];

        $distances[0][0] = "docID";
        foreach(array_slice($dataVector,0,count($dataVector),true) as $k1 => $v1){
           // $cat1 = explode(" ",$dataTerms[$k1]->getArticleID()->getCategory());
            $cat1=trim(strtolower(strstr($dataTerms[$k1]->getArticleID()->getCategory(), ' ')));
            //$id = $dataTerms[$k1]->getArticleID()->getUid();
            $distances[$k1+1][0]= $cat1;
            foreach(array_slice($dataVector,0,count($dataVector),true) as $k2 => $v2){
                // $cat2 = explode(" ",$dataTerms[$k2]->getArticleID()->getCategory());
                $cat2=trim(strtolower(strstr($dataTerms[$k2]->getArticleID()->getCategory(), ' ')));
               // $id2 = $dataTerms[$k2]->getArticleID()->getUid();
                //$distances[0][$k2+1] = $id2;
                $distances[0][$k2+1] = $cat2;
                $distances[$k1+1][]= $sim->similarity($dataVector[$k1],$dataVector[$k2]);
            }
        }


        $fp = fopen('file_sportFootballFashionLife.csv', 'w');

        foreach ($distances as $fields) {
            print_r(fputcsv($fp, $fields));
            print_r("<br>");
        }

        fclose($fp);

    }

    public function exportFingerprint($category,$array){

        /*foreach (array_keys($array, 0) as $key) {
            unset($array[$key]);
        }*/

        $fp = fopen('file_finger_'.$category.'.csv', 'w');

        //foreach ($array as $fields) {
             print_r(fputcsv($fp, array_keys($array)));
            print_r(fputcsv($fp, $array));
            print_r("<br>");
       // }

        fclose($fp);
    }


}