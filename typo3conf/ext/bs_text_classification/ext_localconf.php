<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_reflection']['backend'] = 'TYPO3\CMS\Core\Cache\Backend\NullBackend';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_object']['backend'] = 'TYPO3\CMS\Core\Cache\Backend\NullBackend';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TextClassification.' . $_EXTKEY,
	'Bstextclassification',
	array(
		'EnglishTerms' => 'fingerprinting, knn, bayes, list, show, new, create, edit, update, delete',
		'EnglishData' => ' data, list, new, create, edit, update, delete',

	),
	// non-cacheable actions
	array(
		'EnglishTerms' => 'fingerprinting, knn,  bayes, list, show, new, create, edit, update, delete',
		'EnglishData' => '  data, list, new, create, edit, update, delete',

	)
);
