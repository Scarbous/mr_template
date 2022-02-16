<?php

namespace Scarbous\MrTemplate\Integration\FormEngine;

use Scarbous\MrTemplate\Template\TemplateFinder;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SiteConfigurationProviderItems
 * @package Scarbous\MrTemplate\Integration\FormEngine
 */
class SiteConfigurationProviderItems
{
    /**
     * @var TemplateFinder
     */
    protected $templateFinder;

    function __construct()
    {
        $this->templateFinder = GeneralUtility::makeInstance(TemplateFinder::class);
    }

    /**
     * @param array $params
     * @param TcaSelectItems $tcaSelectItems
     *
     * @return void
     */
    public function processTemplateItems(array &$params, TcaSelectItems $tcaSelectItems): void
    {
        foreach ($this->templateFinder->getAllTemplates() as $template) {
            $params['itemGroups'][$template->getExKey()] = 'EXT:' . $template->getExKey();
            $params['items'][] = [
                $template->getLabel(),
                $template->getIdentifier(),
                $template->getIcon(),
                $template->getExKey()
            ];
        }
    }

}
