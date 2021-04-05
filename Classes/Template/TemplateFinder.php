<?php

namespace Scarbous\MrTemplate\Template;

use Scarbous\MrTemplate\Configuration\TemplateConfiguration;
use Scarbous\MrTemplate\Template\Entity\Template;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TemplateFinder implements SingletonInterface
{
    /**
     * @var Template
     */
    protected $templates = [];

    /**
     * Short-hand to quickly fetch a site based on a rootPageId
     *
     * @var array
     */
    protected $mappingRootPageIdToIdentifier = [];

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
     * @param TemplateConfiguration|null $templateConfiguration
     * @param SiteFinder|null $siteFinder
     */
    function __construct(
        TemplateConfiguration $templateConfiguration = null,
        SiteFinder $siteFinder = null
    ) {
        $this->siteFinder            = $siteFinder ?? GeneralUtility::makeInstance(SiteFinder::class);
        $this->templateConfiguration = $templateConfiguration ?? GeneralUtility::makeInstance(TemplateConfiguration::class);
        $this->fetchAllTemplates();
    }

    /**
     * @param bool $useCache
     *
     * @return Template[]
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
    public function fetchAllTemplates(bool $useCache = true): void
    {
        $this->templates = $this->templateConfiguration->getAllExistingTemplates($useCache);

        foreach ($this->siteFinder->getAllSites($useCache) as $identifier => $site) {
            if ($template = $this->getTemplateBySite($site)) {
                $this->mappingRootPageIdToIdentifier[$site->getRootPageId()] = $template->getIdentifier();
            }
        }
    }

    /**
     * @param Site $site
     *
     * @return Template|null
     */
    public function getTemplateBySite(Site $site): ?Template
    {
        $config = $this->getTemplateConfigBySite($site);

        return $config['template'] === null ? null : $this->getTemplateByIdentifier($config['template']);
    }

    /**
     * @param int $rootPageId
     *
     * @return Template|null
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function getTemplateByRootPageId(int $rootPageId): ?Template
    {
        $config = $this->getTemplateConfigByRootPage($rootPageId);
        if ( ! isset($config['template'])) {
            return null;
        }

        return $this->getTemplateByIdentifier($config['template']);
    }

    /**
     * @param string $identifier
     *
     * @return Template|null
     */
    public function getTemplateByIdentifier(string $identifier): ?Template
    {
        return ($identifier && key_exists($identifier, $this->templates)) ? $this->templates[$identifier] : null;
    }

    /**
     * @param int $rootPageId
     *
     * @return array
     */
    public function getTemplateConfigByRootPage(int $rootPageId): array
    {
        try {
            $site = $this->siteFinder->getSiteByRootPageId($rootPageId);
        } catch (SiteNotFoundException $e) {
            return [];
        }

        return $this->getTemplateConfigBySite($site);
    }

    /**
     * @param Site $site
     *
     * @return array
     */
    public function getTemplateConfigBySite(Site $site): array
    {
        $siteConfig = $site->getConfiguration();

        return ['template' => $siteConfig['mr_template_template']];
    }

    /**
     * @param Template|null $template
     *
     * @return Template[]
     */
    public function getParentTemplates(?Template $template): array
    {
        $templates = [$template];
        while ($template->getParent()) {
            if ($template = $this->getTemplateByIdentifier($template->getParent())) {
                $templates[] = $template;
            }

        }

        return array_reverse($templates);
    }

    /**
     * @return $this
     */
    static function getInstance(): self
    {
        return GeneralUtility::makeInstance(self::class);
    }
}
