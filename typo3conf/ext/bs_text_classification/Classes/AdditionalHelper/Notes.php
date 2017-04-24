<?php


// Word stacks for 5 cats

####ZUM Testen des Thresholds bzw um Calculation zu vereinfachen, so dass die wort stacks gespeichert werden ############



// frist
$contextMap = $this->categoryFingerprintRepository->findByUid(23);

/*$stacks['uk news'] = $this->categoryFingerprintRepository->findByUid(31)->getFingerprint();
     $stacks['business'] = $this->categoryFingerprintRepository->findByUid(32)->getFingerprint();
     $stacks['opinion'] = $this->categoryFingerprintRepository->findByUid(33)->getFingerprint();
     $stacks['sport'] = $this->categoryFingerprintRepository->findByUid(34)->getFingerprint();
     $stacks['society'] = $this->categoryFingerprintRepository->findByUid(35)->getFingerprint();*/

// second

$contextMap = $this->categoryFingerprintRepository->findByUid(21);

/* $stacks['politics'] = $this->categoryFingerprintRepository->findByUid(41)->getFingerprint();
   $stacks['world news'] = $this->categoryFingerprintRepository->findByUid(43)->getFingerprint();
   $stacks['life and style'] = $this->categoryFingerprintRepository->findByUid(45)->getFingerprint();
   $stacks['environment'] = $this->categoryFingerprintRepository->findByUid(42)->getFingerprint();
   $stacks['technology'] = $this->categoryFingerprintRepository->findByUid(44)->getFingerprint();*/

// third

$contextMap = $this->categoryFingerprintRepository->findByUid(24);

/*$stacks['television & radio'] = $this->categoryFingerprintRepository->findByUid(47)->getFingerprint();
$stacks['culture'] = $this->categoryFingerprintRepository->findByUid(46)->getFingerprint();
$stacks['art and design'] = $this->categoryFingerprintRepository->findByUid(50)->getFingerprint();
$stacks['film'] = $this->categoryFingerprintRepository->findByUid(49)->getFingerprint();
$stacks['books'] = $this->categoryFingerprintRepository->findByUid(48)->getFingerprint();*/

// fourth
$contextMap = $this->categoryFingerprintRepository->findByUid(25);

/* $stacks['us news'] = $this->categoryFingerprintRepository->findByUid(54)->getFingerprint();
 $stacks['football'] = $this->categoryFingerprintRepository->findByUid(53)->getFingerprint();
 $stacks['fashion'] = $this->categoryFingerprintRepository->findByUid(51)->getFingerprint();
 $stacks['travel'] = $this->categoryFingerprintRepository->findByUid(52)->getFingerprint();
 $stacks['science'] = $this->categoryFingerprintRepository->findByUid(55)->getFingerprint();*/

// Word stacks for 10 cats

// first
$contextMap = $this->categoryFingerprintRepository->findByUid(77);

    $stacks = [];
    //finde die Wort - Stacks pro category auf der 20er ContextMap
    /*for($i = 78; $i <88; $i++){
        $stack = $this->categoryFingerprintRepository->findByUid($i);
        $class = explode("_",$stack->getCategoryName())[0];
        $stacks[$class] = $stack->getFingerprint();
    }*/


//second
$contextMap = $this->categoryFingerprintRepository->findByUid(88);

/*$stacks = [];
//finde die Wort - Stacks pro category auf der 20er ContextMap
for($i = 89; $i <99; $i++){
    $stack = $this->categoryFingerprintRepository->findByUid($i);
    $class = explode("_",$stack->getCategoryName())[0];
    $stacks[$class] = $stack->getFingerprint();
}*/

//third
$contextMap = $this->categoryFingerprintRepository->findByUid(99);

$stacks = [];
//finde die Wort - Stacks pro category auf der 20er ContextMap
for($i = 110; $i <120; $i++){
    $stack = $this->categoryFingerprintRepository->findByUid($i);
    $class = explode("_",$stack->getCategoryName())[0];
    $stacks[$class] = $stack->getFingerprint();
}

//fourth
$contextMap = $this->categoryFingerprintRepository->findByUid(120);

$stacks = [];
//finde die Wort - Stacks pro category auf der 20er ContextMap
for($i = 121; $i <131; $i++){
    $stack = $this->categoryFingerprintRepository->findByUid($i);
    $class = explode("_",$stack->getCategoryName())[0];
    $stacks[$class] = $stack->getFingerprint();
}


// Context map for 2er map

$contextMap = $this->categoryFingerprintRepository->findByUid(56);