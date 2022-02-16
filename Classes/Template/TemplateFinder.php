<?php

namespace Scarbous\MrTemplate\Template;

use Scarbous\MrTemplate\Configuration\TemplateConfiguration;
use Scarbous\MrTemplate\Template\Entity\Template;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TemplateFinder implements TemplateFinderInterface
{
    /**
     * @var Template
     */
    protected $templates = [];

    /**
     * @var TemplateConfiguration
     */
    protected $templateConfiguration;

    /**
     * @var SiteFinder
     */
    protected $siteFinder;

    /**
     * TemplateFinder constructor.
     *
     * @param TemplateConfiguration $templateConfiguration
     * @param SiteFinder $siteFinder
     */
    function __construct(
        TemplateConfiguration $templateConfiguration,
        SiteFinder            $siteFinder
    )
    {
        $this->siteFinder = $siteFinder;
        $this->templateConfiguration = $templateConfiguration;
        $this->fetchAllTemplates();
    }

    /**
     * @inheritDoc
     */
    public function getAllTemplates(bool $useCache = true): array
    {
        if ($useCache === false) {
            $this->fetchAllTemplates($useCache);
        }

        return $this->templates;
    }

    /**
     * @param bool $useCache
     */
    private function fetchAllTemplates(bool $useCache = true): void
    {
        $this->templates = $this->templateConfiguration->getAllExistingTemplates($useCache);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateBySite(Site $site): ?Template
    {
        $config = $this->getTemplateConfigBySite($site);

        return $config['template'] === null ? null : $this->getTemplateByIdentifier($config['template']);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateByRootPageId(int $rootPageId): ?Template
    {
        $config = $this->getTemplateConfigByRootPage($rootPageId);
        if (!isset($config['template'])) {
            return null;
        }

        return $this->getTemplateByIdentifier($config['template']);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateByIdentifier(string $identifier): ?Template
    {
        return $this->templates[$identifier] ?? null;
    }

    /**
     * @param int $rootPageId
     *
     * @return array
     */
    private function getTemplateConfigByRootPage(int $rootPageId): array
    {
        try {
            $site = $this->siteFinder->getSiteByRootPageId($rootPageId);
        } catch (SiteNotFoundException $e) {
            return [];
        }

        return $this->getTemplateConfigBySite($site);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateConfigBySite(Site $site): array
    {
        $siteConfig = $site->getConfiguration();

        return ['template' => $siteConfig['mr_template_template']];
    }

    /**
     * @inheritDoc
     */
    public function getParentTemplates(Template $template): array
    {
        $templates = [$template];
        while ($template->getParent()) {
            if ($template = $this->getTemplateByIdentifier($template->getParent())) {
                $templates[] = $template;
            }
        }

        return array_reverse($templates);
    }
}
