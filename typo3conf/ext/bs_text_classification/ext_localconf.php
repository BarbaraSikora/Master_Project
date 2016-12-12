<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TextClassification.' . $_EXTKEY,
	'Bstextclassification',
	array(
		'Data' => 'data,list',
		'Terms' => 'list',
	),
	// non-cacheable actions
	array(
		'Data' => ',list',
		'Terms' => 'list',
		
	)
);
