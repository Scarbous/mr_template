<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSources'][] =
    \Scarbous\MrTemplate\Integration\HookSubscribers\TableConfigurationPostProcessor::class . '->includeStaticTypoScriptSources';
