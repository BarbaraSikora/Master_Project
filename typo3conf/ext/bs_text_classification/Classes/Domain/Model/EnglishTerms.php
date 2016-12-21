<?php
namespace TextClassification\BsTextClassification\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Barbara Sikora <barbara-sikora@gmx.at>, FH Hagenberg
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Terms
 */
class EnglishTerms extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * terms
     * 
     * @var string
     */
    protected $terms = '';
    
    /**
     * Connection to article
     * 
     * @var \TextClassification\BsTextClassification\Domain\Model\EnglishData
     */
    protected $articleID = null;
    
    /**
     * Returns the terms
     * 
     * @return string $terms
     */
    public function getTerms()
    {
        return $this->terms;
    }
    
    /**
     * Sets the terms
     * 
     * @param string $terms
     * @return void
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }
    
    /**
     * Returns the articleID
     * 
     * @return \TextClassification\BsTextClassification\Domain\Model\EnglishData $articleID
     */
    public function getArticleID()
    {
        return $this->articleID;
    }
    
    /**
     * Sets the articleID
     * 
     * @param \TextClassification\BsTextClassification\Domain\Model\EnglishData $articleID
     * @return void
     */
    public function setArticleID(\TextClassification\BsTextClassification\Domain\Model\EnglishData $articleID)
    {
        $this->articleID = $articleID;
    }

}