<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 10.12.2016
 * Time: 10:24
 */

namespace TextClassification\BsTextClassification\Controller;
include('C:\xampp\htdocs\Master_Project\typo3conf\ext\bs_text_classification\Resources\Private\Libraries\php-nlp-tools\autoloader.php');
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;

class DataController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * action data
     *
     * @return void
     */
    public function dataAction()
    {

       // $tok = new WhitespaceAndPunctuationTokenizer();


        //tags weg, stopwords weg leezeichen weg, stemming


        $url_ENG = "https://www.theguardian.com/us-news/2016/dec/10/cia-concludes-russia-interfered-to-help-trump-win-election-report";

        $text = $this->getDataAction($url_ENG);

        $meta = get_meta_tags($url_ENG);
        $title = $this->everything_in_tags($text,"title");
        $split = preg_split('/\|+/', $title);

        $dataCategory = $split[1];
        $dataTitle = $title;
        $dataDescription = $meta['description'];
        $dataContent = implode(" ",$this->everything_with_class($text,'p','p'));

        $data =[
            "dataCategory" => $dataCategory,
            "dataTitle" => $dataTitle,
            "dataDescription" => $dataDescription,
            "dataContent" => $dataContent
        ];

        print "<pre>";
       // print_r($dataContent);
        print "</pre>";

        $this->view->assign('output', $data );
    }

    /**
     * action getData
     *
     * @return string
     */
    public function getDataAction($url)
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
    function everything_in_tags($string, $tagname)
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
    function everything_with_class($string, $classname,$endtag)
    {
        $pattern = "#<$classname>(.*?)</$endtag\b[^>]*>#s";
        preg_match_all($pattern, $string, $matches);
        return array_unique($matches[1]);
    }





}