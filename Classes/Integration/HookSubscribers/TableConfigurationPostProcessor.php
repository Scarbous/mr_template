<?php

namespace Scarbous\MrTemplate\Integration\HookSubscribers;

use Scarbous\MrTemplate\Template\TemplateFinder;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
        $this->templateFinder = $templateFinder ?? GeneralUtility::makeInstance(TemplateFinder::class);
    }

    /**
     * Includes TypoScript from Template
     *
     * @param array $params Hook parameter
     */
    public function includeStaticTypoScriptSources(array &$params): void
    {
        try {
            /** @var Site $site */
            $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByRootPageId(($params['row']['root'] ?? 0));
        } catch (SiteNotFoundException $e) {
            return;
        }

        $template = $this->templateFinder->getTemplateBySite($site);

        if (!$template) {
            return;
        }

        $params['row']['include_static_file'] = implode(',', array_unique(array_merge(
            GeneralUtility::trimExplode(',', $params['row']['include_static_file'] ?? ''),
            $template->getTypoScriptStaticFiles()
        )));
    }
}
