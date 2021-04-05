<?php

namespace Scarbous\MrTemplate\Template\Entity;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class TsConfig extends AbstractTsConfig implements TsConfigInterface
{
    /**
     * @var string
     */
    protected $path;

    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    function getHeader(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    function getTsConfig(\Scarbous\MrTemplate\Template\Entity\Template $template, array $page): string
    {
        if (strpos($this->path, 'EXT:') !== 0) {
            return '';
        }
        [$includeTsConfigFileExtensionKey, $includeTsConfigFilename] = explode(
            '/',
            substr($this->path, 4),
            2
        );
        if ((string)$includeTsConfigFileExtensionKey !== ''
            && ExtensionManagementUtility::isLoaded($includeTsConfigFileExtensionKey)
            && (string)$includeTsConfigFilename !== ''
        ) {
            $extensionPath              = ExtensionManagementUtility::extPath($includeTsConfigFileExtensionKey);
            $includeTsConfigFileAndPath = PathUtility::getCanonicalPath($extensionPath . $includeTsConfigFilename);
            if (strpos($includeTsConfigFileAndPath,
                    $extensionPath) === 0 && file_exists($includeTsConfigFileAndPath)) {
                return file_get_contents($includeTsConfigFileAndPath);
            }
        }

        return '';
    }

}
