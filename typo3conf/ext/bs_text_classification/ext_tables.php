<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'TextClassification.' . $_EXTKEY,
	'Bstextclassification',
	'BS Text Classification'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'TextClassification Module');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_bstextclassification_domain_model_englishterms', 'EXT:bs_text_classification/Resources/Private/Language/locallang_csh_tx_bstextclassification_domain_model_englishterms.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bstextclassification_domain_model_englishterms');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_bstextclassification_domain_model_englishdata', 'EXT:bs_text_classification/Resources/Private/Language/locallang_csh_tx_bstextclassification_domain_model_englishdata.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bstextclassification_domain_model_englishdata');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_bstextclassification_domain_model_categoryfingerprint', 'EXT:bs_text_classification/Resources/Private/Language/locallang_csh_tx_bstextclassification_domain_model_categoryfingerprint.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bstextclassification_domain_model_categoryfingerprint');
