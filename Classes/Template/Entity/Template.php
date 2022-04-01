<?php

namespace Scarbous\MrTemplate\Template\Entity;

use Scarbous\MrTemplate\Template\TemplateFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Template implements TemplateInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var ?string
     */
    protected $parent = null;

    /**
     * @var string[]
     */
    protected $typoScript = [];

    /**
     * @var string[]
     */
    protected $extensions = [];

    /**
     * @var null|string
     */
    protected $icon = null;

    /**
     * @var TsConfigInterface[]
     */
    protected $tsConfig = [];

    /**
     * @var TemplateFinder
     */
    protected $_templateFinder;

    /**
     * Template constructor.
     *
     * @param string $identifier
     * @param array $configuration
     */
    function __construct(
        string $identifier,
        array  $configuration
    )
    {
        $this->identifier = $identifier;

        foreach (
            [
                'label' => 'label',
                'parent' => 'parent',
                'typoScript' => 'typoScript',
                'extensions' => 'extensions',
                'icon' => 'icon'
            ] as $confKey => $var
        ) {
            if (isset($configuration[$confKey])) {
                $this->$var = $configuration[$confKey];
            }
        }

        if (isset($configuration['tsConfig'])) {
            foreach ($configuration['tsConfig'] as $tsConfig) {
                if (strpos($tsConfig, 'EXT:') === 0) {
                    $this->tsConfig[] = new TsConfig($tsConfig);
                } elseif (class_exists($tsConfig)) {
                    $this->tsConfig[] = new $tsConfig();
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function getExKey(): string
    {
        [$extKey] = explode('/', $this->getIdentifier(), 2);

        return $extKey;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->label ?? $this->getIdentifier();
    }

    /**
     * @inheritDoc
     */
    public function getIcon(): string
    {
        return $this->icon ?? "EXT:mr_template/Resources/Public/Icons/Extension.svg";
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function getParentTemplate(): ?Template
    {
        return $this->getParent() ? $this->getTemplateFinder()->getTemplateByIdentifier($this->getParent()) : null;
    }

    /**
     * @inheritDoc
     */
    public function getTypoScript(): array
    {
        return $this->typoScript;
    }

    /**
     * @inheritDoc
     */
    public function getTypoScriptStaticFiles(): array
    {
        $parent = $this;
        $extension = [];
        $staticFiles = [];
        $templates = [];

        do {
            $templates = array_merge(['EXT:' . $parent->getExKey() . '/Configuration/TypoScript'], $templates);
            $staticFiles = array_merge($parent->getTypoScript(), $staticFiles);
            $extension = array_merge(
                array_map(function ($extKey) use ($parent) {
                    return sprintf('EXT:%s/Extensions/%s/Configuration/TypoScript', $parent->getExKey(), $extKey);
                }, $parent->getExtensions()),
                $extension
            );

        } while ($parent = $parent->getParentTemplate());

        return array_merge($staticFiles, $extension, $templates);
    }

    /**
     * @inheritDoc
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @inheritDoc
     */
    public function getTsConfig(): array
    {
        return $this->tsConfig;
    }

    /**
     * @inheritDoc
     */
    public function getPageTsConfig(array $page): string
    {
        $parent = $this;
        $allTsConfigs = [];

        do {
            foreach ($parent->getTsConfig() as $tsConfig) {
                $allTsConfigs[] = $tsConfig->getHeader(true) . $tsConfig->getTsConfig($parent, $page);
            }
        } while ($parent = $parent->getParentTemplate());

        return implode(LF, $allTsConfigs);
    }

    /**
     * @return TemplateFinder
     */
    private function getTemplateFinder(): TemplateFinder
    {
        return $this->_templateFinder = $this->_templateFinder ?: GeneralUtility::makeInstance(TemplateFinder::class);
    }
}
