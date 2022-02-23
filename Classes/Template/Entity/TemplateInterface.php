<?php

namespace Scarbous\MrTemplate\Template\Entity;

interface TemplateInterface
{

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @return string
     */
    public function getExKey(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getIcon(): string;

    /**
     * @return string|null
     */
    public function getParent(): ?string;

    /**
     * @return Template|null
     */
    public function getParentTemplate(): ?Template;

    /**
     * @return string[]
     */
    public function getTypoScript(): array;

    /**
     * @return string[]
     */
    public function getTypoScriptStaticFiles(): array;

    /**
     * @return string[]
     */
    public function getExtensions(): array;

    /**
     * @return TsConfigInterface[]
     */
    public function getTsConfig(): array;

    /**
     * @param array $page
     * @return string
     */
    public function getPageTsConfig(array $page): string;
}
