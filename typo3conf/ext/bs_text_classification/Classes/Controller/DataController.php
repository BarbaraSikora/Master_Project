<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 10.12.2016
 * Time: 10:24
 */

namespace TextClassification\BsTextClassification\Controller;
include('C:\xampp\htdocs\Master_Project\typo3conf\ext\bs_text_classification\Resources\Private\Libraries\php-nlp-tools\autoloader.php');
use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Tokenizers\PennTreeBankTokenizer;
use NlpTools\Tokenizers\RegexTokenizer;
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Utils\Normalizers\English;
use NlpTools\Utils\StopWords;

class DataController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    public $stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");

    /**
     * action data
     *
     * @return void
     */
    public function dataAction()
    {
        //get Data from guardian
        $url_ENG = "https://www.theguardian.com/world/2016/dec/10/bomb-outside-istanbul-football-stadium-causes-multiple-casualties";

        $text = $this->getDataAction($url_ENG);

        //filter data

        $meta = get_meta_tags($url_ENG);
        $title = $this->getEverythingBetweenTags($text,"title");
        $split = preg_split('/\|+/', $title);

        $dataCategory = $split[1];
        $dataTitle = $title;
        $dataDescription = $meta['description'];
        $dataContent = implode(" ",$this->pregMatchAll($text,'p','p'));

        // preprocess Data tags weg, stopwords weg leezeichen weg, stemming

        $dataContent = $this->preprocessingData($dataContent);
        $dataTitle = $this->preprocessingData($dataTitle);
        $dataDescription = $this->preprocessingData($dataDescription);

        $data =[
            "dataCategory" => $dataCategory,
            "dataTitle" => implode(" ",$dataTitle),
            "dataDescription" => implode(" ",$dataDescription),
            "dataContent" => implode(" ",$dataContent)
        ];

        print "<pre>";
        //print_r($dataContent);
        print "</pre>";

        $this->view->assign('output', $data );
    }




    #######################################################
   // HELPER FUNCTIONS
    #######################################################
    /**
     * action getData
     *
     * @return string
     */
    function getDataAction($url)
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
        return  $data;
    }

    /**
     * action everything_in_tags
     *
     * @return string
     */
    function getEverythingBetweenTags($string, $tagname)
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
    function pregMatchAll($string, $classname,$endtag)
    {
        $pattern = "#<$classname>(.*?)</$endtag\b[^>]*>#s";
        preg_match_all($pattern, $string, $matches);
        return array_unique($matches[1]);
    }

    /**
     * action preprocessing data
     *
     * @return array
     */
    function preprocessingData($string)
    {
        $string = strip_tags($string);
        $string =  trim( preg_replace( "/[^0-9a-z ]+/i", "", $string ) );
        $norm = new English();
        $string = $norm->normalize($string);

        $wtok = new WhitespaceTokenizer();
        $array = $wtok->tokenize($string);

        $stop = new StopWords($this->stopwords);
        $stem = new PorterStemmer();

        foreach ($array as $key => $value) {
            $array[$key] = $stop->transform($value);
        }

        $array  = array_filter($array);

        // $array = $stem->stemAll($array);
        return $array;
    }







}