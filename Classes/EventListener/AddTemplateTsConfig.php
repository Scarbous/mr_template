<?php

namespace Scarbous\MrTemplate\EventListener;

use Scarbous\MrTemplate\Template\TemplateFinder;
use TYPO3\CMS\Core\Configuration\Event\ModifyLoadedPageTsConfigEvent;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AddTemplateTsConfig
 * @package Scarbous\MrTemplate\EventListener
 */
class AddTemplateTsConfig
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
     * @param ModifyLoadedPageTsConfigEvent $event
     *
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function __invoke(ModifyLoadedPageTsConfigEvent $event): void
    {
        foreach ($event->getRootLine() as $page) {
            if (!$page['is_siteroot']) {
                continue;
            }

            try {
                /** @var Site $site */
                $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByRootPageId(($page['uid'] ?? 0));
            } catch (SiteNotFoundException $e) {
                continue;
            }

            $template = $this->templateFinder->getTemplateBySite($site);

            if (!$template) {
                continue;
            }

            $tsConfig = $event->getTsConfig();
            $tsConfig['page_' . $page['uid']] .= $template->getPageTsConfig($page);
            $event->setTsConfig($tsConfig);
        }
    }
}
