<?php

namespace Scarbous\MrTemplate\Template\Entity;

class Template
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
     * @var TsConfigInterface[]
     */
    protected $tsConfig = [];

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
                'extensions' => 'extensions'
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
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getExKey(): string
    {
        [$extKey] = explode('/', $this->getIdentifier(),1);

        return $extKey;
    }

    /**
     * @param string $identifier
     *
     * @return Template
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? $this->getIdentifier();
    }

    /**
     * @param string $label
     *
     * @return Template
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * @param string|null $parent
     *
     * @return Template
     */
    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTypoScript(): array
    {
        return $this->typoScript;
    }

    /**
     * @param string[] $typoScript
     *
     * @return Template
     */
    public function setTypoScript(array $typoScript): self
    {
        $this->typoScript = $typoScript;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param string[] $extensions
     *
     * @return Template
     */
    public function setExtensions(array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @return TsConfigInterface[]
     */
    public function getTsConfig(): array
    {
        return $this->tsConfig;
    }

    /**
     * @param TsConfigInterface[] $tsConfig
     *
     * @return Template
     */
    public function setTsConfig(array $tsConfig): self
    {
        $this->tsConfig = $tsConfig;

        return $this;
    }
}
