<?php
/**
 * Created by PhpStorm.
 * User: Barbara
 * Date: 20.12.2016
 * Time: 12:50
 */

namespace TextClassification\BsTextClassification\Classes\AdditionalHelper;


use NlpTools\Documents\DocumentInterface;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\FunctionFeatures;
use NlpTools\Analysis\Idf;

class TfIdfFeatureFactory extends FunctionFeatures
{
    protected $idf;

    public function __construct(Idf $idf, array $functions)
    {
        parent::__construct($functions);
        $this->modelFrequency();
        $this->idf = $idf;
    }

    public function getFeatureArray($class, DocumentInterface $doc)
    {
        //TF
        $frequencies = parent::getFeatureArray($class, $doc);
        $numbFeatures = array_sum($frequencies);
       /* print_r("<pre>");
        print_r($frequencies);
        print_r("<br>");
        print_r("</pre>");*/
        foreach ($frequencies as $term=>&$value) {
            $value = ($value/$numbFeatures)*($this->idf[$term]); ////neu!!!! $numbFeatures  count($frequencies)
        }
        return $frequencies;
    }
}