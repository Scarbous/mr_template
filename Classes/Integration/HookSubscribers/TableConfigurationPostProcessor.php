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
     * @param TemplateFinder $templateFinder
     */
    function __construct(
        TemplateFinder $templateFinder
    )
    {
        $this->templateFinder = $templateFinder;
    }

    /**
     * Includes TypoScript from Template
     *
     * @param array $params Hook parameter
     */
    public function includeStaticTypoScriptSources(array &$params): void
    {
        if (
            ($params['row']['root'] ?? 0) != 1
            ||
            !($template = $this->templateFinder->getTemplateByRootPageId($params['row']['uid']))
        ) {
            return;
        }

        $extensionStaticFiles = [];
        $staticFiles = [];
        foreach ($this->templateFinder->getParentTemplates($template) as $template) {
            $staticFiles = array_merge($staticFiles, $template->getTypoScript());
            foreach ($template->getExtensions() as $extension) {
                $extensionStaticFiles[] = 'EXT:' . $template->getExKey() . '/Extensions/' . $extension . '/Configuration/TypoScript';
            }
        }

        $params['row']['include_static_file'] =
            implode(',', array_unique(array_merge(
                    GeneralUtility::trimExplode(',', $params['row']['include_static_file'] ?? ''),
                    $staticFiles,
                    $extensionStaticFiles,
                    ['EXT:' . $template->getExKey() . '/Configuration/TypoScript']
                )
            ));
    }
}
