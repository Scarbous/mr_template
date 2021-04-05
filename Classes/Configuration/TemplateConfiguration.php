<?php

namespace Scarbous\MrTemplate\Configuration;

use Scarbous\MrTemplate\Cache\TemplateCache;
use Scarbous\MrTemplate\Template\Entity\Template;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class TemplateConfiguration
 * @package Scarbous\MrTemplate\Configuration
 */
class TemplateConfiguration implements SingletonInterface
{
    /**
     * @var string
     */
    protected $configPath = 'Configuration/MrTemplate';

    /**
     * Config yaml file name.
     *
     * @internal
     * @var string
     */
    protected $configFileName = 'template.yml';

    /**
     * @var TemplateCache
     */
    protected $templateCache;

    /**
     * Cache stores all configuration as Template objects, as long as they haven't been changed.
     * This drastically improves performance as TemplateFinder utilizes TemplateConfiguration heavily
     *
     * @var array|null
     */
    protected $firstLevelCache;

    /**
     * TemplateConfiguration constructor.
     *
     * @param TemplateCache|null $templateCache
     */
    function __construct(
        TemplateCache $templateCache = null
    ) {
        $this->templateCache = $templateCache ?? GeneralUtility::makeInstance(TemplateCache::class);
    }

    /**
     * Return all site objects which have been found in the filesystem.
     *
     * @param bool $useCache
     *
     * @return Template[]
     */
    public function getAllExistingTemplates(bool $useCache = true): array
    {
        if ($useCache && $this->firstLevelCache !== null) {
            return $this->firstLevelCache;
        }

        return $this->resolveAllExistingTemplates($useCache);
    }

    /**
     * Resolve all site objects which have been found in the filesystem.
     *
     * @param bool $useCache
     *
     * @return array
     */
    public function resolveAllExistingTemplates(bool $useCache = true): array
    {
        $templates             = [];
        $templateConfiguration = $this->getAllTemplateConfigurationFromFiles($useCache);
        foreach ($templateConfiguration as $identifier => $configuration) {
            $templates[$identifier] = GeneralUtility::makeInstance(
                Template::class,
                $identifier,
                $configuration
            );
        }
        $this->firstLevelCache = $templates;

        return $templates;
    }

    /**
     * Read the site configuration from config files.
     *
     * @param bool $useCache
     *
     * @return array
     */
    protected function getAllTemplateConfigurationFromFiles(bool $useCache = true): array
    {
        // Check if the data is already cached
        $templateConfig = $useCache ? $this->templateCache->get() : false;
        if ($templateConfig !== false) {
            return $templateConfig;
        }
        $finder = new Finder();
        $finder->files()->depth(0)->name($this->configFileName);

        foreach ($this->getActivePackages() as $package) {
            try {
                $finder->in($package->getPackagePath() . $this->configPath . '/*');
            } catch (\InvalidArgumentException $e) {
                // No such directory in this package
                continue;
            }
        }

        /** @var YamlFileLoader $loader */
        $loader         = GeneralUtility::makeInstance(YamlFileLoader::class);
        $templateConfig = [];

        foreach ($finder as $fileInfo) {
            $configuration = $loader->load(GeneralUtility::fixWindowsFilePath((string)$fileInfo));
            $templateName  = basename($fileInfo->getPath());
            $extKey        = basename(substr(
                $fileInfo->getPath(),
                0, strlen($fileInfo->getPath()) - strlen($this->configPath . '/' . $templateName)
            ));
            $identifier    = $extKey . '/' . $templateName;

            $templateConfig[$identifier] = $configuration;
        }

        $this->templateCache->set($templateConfig);

        return $templateConfig;
    }

    /**
     * @return PackageInterface[]
     */
    protected function getActivePackages(): array
    {
        return GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
    }
}
