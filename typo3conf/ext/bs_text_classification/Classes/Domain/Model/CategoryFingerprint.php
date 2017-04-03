<?php
namespace TextClassification\BsTextClassification\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Barbara Sikora <barbara-sikora@gmx.at>, FH Hagenberg
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
 * CategoryFingerprint
 */
class CategoryFingerprint extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * categoryName
     * 
     * @var string
     */
    protected $categoryName = '';
    
    /**
     * fingerprint
     * 
     * @var string
     */
    protected $fingerprint = '';
    
    /**
     * Returns the categoryName
     * 
     * @return string $categoryName
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }
    
    /**
     * Sets the categoryName
     * 
     * @param string $categoryName
     * @return void
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }
    
    /**
     * Returns the fingerprint
     * 
     * @return string $fingerprint
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }
    
    /**
     * Sets the fingerprint
     * 
     * @param string $fingerprint
     * @return void
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;
    }

}