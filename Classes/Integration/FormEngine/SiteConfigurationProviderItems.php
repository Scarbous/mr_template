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
     * @param array $tca
     * @param TcaSelectItems $bar
     *
     * @return array
     */
    public function processTemplateItems(array $tca, TcaSelectItems $bar): array
    {
        foreach ($this->getTemplateFinder()->getAllTemplates() as $template) {
            $tca['items'][] = [
                $template->getLabel(),
                $template->getIdentifier()
            ];
        }

        return $tca;
    }

    /**
     * @return TemplateFinder
     */
    protected function getTemplateFinder(): TemplateFinder
    {
        return GeneralUtility::makeInstance(TemplateFinder::class);
    }
}
