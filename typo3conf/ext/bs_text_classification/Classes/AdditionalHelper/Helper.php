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

        //leere arraypl�tze eliminieren
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
        //if(count($array) < 50){
            $text = $this->getData($url);
            $doc->loadHTML($text);
            $attr = "article";
            $nodelist = $this->getNodeList("//section//ul//a[contains(@data-link-name, '$attr')]/@href",$doc);
            foreach ($nodelist as $node) {
                $array[] =  "{$node->nodeValue}";
            }
            $array = array_unique($array);
            foreach($array as $key => $one) {
                if(strpos($one, 'video') !== false || strpos($one, 'audio') !== false || strpos($one, 'picture') !== false || strpos($one, 'live') !== false || strpos($one, 'gallery') !== false)
                    unset($array[$key]);
            }
            $array = array_values($array);

         /*   $attr="next";
            $next = $this->getNodeList("//div//a[contains(@rel, '$attr')]/@href",$doc);
            $next = $next[0]->nodeValue;

            $array = $this->getAllLinks($next,$array,$doc);*/
     //   }
        return $array;
    }


}