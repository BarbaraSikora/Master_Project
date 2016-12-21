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
use NlpTools\Utils\Normalizers\English;
use TextClassification\BsTextClassification\Domain\Model\EnglishTerms;

class KNearestNeighbours
{

    protected $dataTerms = null;
    protected $trainingsData = null;
    protected $testData = null;
    protected $dataVectors = null;

    /**
     * @return null
     */
    public function getDataVectors()
    {
        return $this->dataVectors;
    }




    function __construct($data) {
        $this->dataTerms = $data;
        $count = count($data);
        $training = $count*0.67;
        $testing = $count*0.33;

        //TRAININGS SET MUSS STRUKTUR HABEN!
        $trainSet = new TrainingSet();
        $norm = new English();
        foreach($this->dataTerms as $document){
            $content = $norm->normalize(trim(preg_replace("/[^0-9a-z ]+/i", "",$document->getArticleID()->getContent())));

            $trainSet->addDocument(
                  "",
                  new TokensDocument(
                      explode(" ",$content)
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

               $this->dataVectors = $allValues;   /**/

        //$idf = new Idf($trainSet);
       // $this->dataVectors = $idf->offsetGet("world");


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