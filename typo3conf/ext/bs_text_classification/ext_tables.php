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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_bstextclassification_domain_model_terms', 'EXT:bs_text_classification/Resources/Private/Language/locallang_csh_tx_bstextclassification_domain_model_terms.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bstextclassification_domain_model_terms');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_bstextclassification_domain_model_data', 'EXT:bs_text_classification/Resources/Private/Language/locallang_csh_tx_bstextclassification_domain_model_data.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bstextclassification_domain_model_data');
