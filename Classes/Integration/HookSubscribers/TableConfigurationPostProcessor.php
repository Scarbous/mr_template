<?php

namespace Scarbous\MrTemplate\Integration\HookSubscribers;

use Scarbous\MrTemplate\Template\TemplateFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class TableConfigurationPostProcessor
 * @package Scarbous\MrTemplate\Integration\HookSubscribers
 */
class TableConfigurationPostProcessor implements SingletonInterface
{
    /**
     * @var TemplateFinder
     */
    protected $templateFinder;

    /**
     * TableConfigurationPostProcessor constructor.
     *
     * @param TemplateFinder|null $templateFinder
     */
    function __construct(
        TemplateFinder $templateFinder = null
    ) {
        $this->templateFinder = $templateFinder ?? GeneralUtility::makeInstance(TemplateFinder::class);
    }

    /**
     * Includes TypoScript from Template
     *
     * @param array $params Hook parameter
     *
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function includeStaticTypoScriptSources(array $params): void
    {
        if ( ! (isset($params['row']['root']) && $params['row']['root'] === 1)) {
            return;
        }
        if ( ! ($template = $this->templateFinder->getTemplateByRootPageId($params['row']['uid']))) {
            return;
        }

        if ( ! (
            isset($params['row']['include_static_file'])
            || is_array($params['row']['include_static_file'])
        )) {
            $params['row']['include_static_file'] = [];
        }

        $backupStaticFiles = GeneralUtility::trimExplode(',', $params['row']['include_static_file']);

        $extensionStaticFiles = [];
        $staticFiles          = [];
        foreach ($this->templateFinder->getParentTemplates($template) as $template) {
            $staticFiles = array_merge($staticFiles, $template->getTypoScript());
            foreach ($template->getExtensions() as $extension) {
                $extensionStaticFiles[] = 'EXT:' . $template->getExKey() . '/Extensions/' . $extension . '/Configuration/TypoScript';
            }
        }

        $params['row']['include_static_file'] =
            implode(',', array_unique(array_merge(
                    $backupStaticFiles,
                    $staticFiles,
                    $extensionStaticFiles,
                    ['EXT:' . $template->getExKey() . '/Configuration/TypoScript']
                )
            ));
    }
}
