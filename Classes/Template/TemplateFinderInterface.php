<?php

namespace Scarbous\MrTemplate\Template;

use Scarbous\MrTemplate\Template\Entity\Template;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\Site;

interface TemplateFinderInterface extends SingletonInterface
{
    /**
     * @return Template[]
     */
    public function getAllTemplates(): array;

    /**
     * @param Site $site
     *
     * @return Template|null
     */
    public function getTemplateBySite(Site $site): ?Template;

    /**
     * @param int $rootPageId
     *
     * @return Template|null
     */
    public function getTemplateByRootPageId(int $rootPageId): ?Template;

    /**
     * @param string $identifier
     *
     * @return Template|null
     */
    public function getTemplateByIdentifier(string $identifier): ?Template;

    /**
     * @param Site $site
     *
     * @return array
     */
    public function getTemplateConfigBySite(Site $site): array;

    /**
     * @param Template $template
     *
     * @return Template[]
     */
    public function getParentTemplates(Template $template): array;
}
