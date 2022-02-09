<?php

namespace Scarbous\MrTemplate\EventListener;

use Scarbous\MrTemplate\Template\TemplateFinder;
use TYPO3\CMS\Core\Configuration\Event\ModifyLoadedPageTsConfigEvent;
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
     * @param TemplateFinder|null $templateFinder
     */
    function __construct(
        TemplateFinder $templateFinder = null
    ) {
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
            if (
                ! $page['is_siteroot']
                || ! ($template = $this->templateFinder->getTemplateByRootPageId($page['uid']))
            ) {
                continue;
            }

            $allTsConfigs = [];

            foreach ($this->templateFinder->getParentTemplates($template) as $template) {
                foreach ($template->getTsConfig() as $tsConfig) {
                    $allTsConfigs[] = $this->getHeader($tsConfig->getHeader())
                                      . $tsConfig->getTsConfig($template, $page);
                }
            }

            $tsConfig = $event->getTsConfig();

            $tsConfig['page_' . $page['uid']] .= implode("\n", $allTsConfigs);

            $event->setTsConfig($tsConfig);
        }
    }

    /**
     * @param string $title
     *
     * @return string
     */
    private function getHeader(string $title): string
    {
        return LF . str_repeat('#', strlen($title) + 10) . LF .
               '##### ' . $title . ' #####' . LF .
               str_repeat('#', strlen($title) + 10). LF;
    }
}
