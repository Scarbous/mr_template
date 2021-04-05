<?php
// Configure a new simple required input field to site
$GLOBALS['SiteConfiguration']['site']['columns']['mr_template_template'] = [
    'label'       => 'LLL:EXT:mr_template/Resources/Private/Language/locallang_siteconfiguration_tca.xlf:mrTemplate.template',
    'description' => 'LLL:EXT:mr_template/Resources/Private/Language/locallang_siteconfiguration_tca.xlf:mrTemplate.template.description',
    'config'      => [
        'type'          => 'select',
        'renderType'    => 'selectSingle',
        'onChange'      => 'reload',
        'items'         => [
            ['none', ''],
        ],
        'itemsProcFunc' => \Scarbous\MrTemplate\Integration\FormEngine\SiteConfigurationProviderItems::class . '->processTemplateItems',
    ],
];

$GLOBALS['SiteConfiguration']['site']['types'][0]['showitem'] = preg_replace_callback(
    '/--div--/i',
    function () {
        return '--div--;LLL:EXT:mr_template/Resources/Private/Language/locallang_siteconfiguration_tca.xlf:mrTemplate.tab,mr_template_template,--div--';
    },
    $GLOBALS['SiteConfiguration']['site']['types'][0]['showitem'],
    1
);
