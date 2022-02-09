<?php

namespace Scarbous\MrTemplate\Cache;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TemplateCache implements SingletonInterface
{
    const IDENTIFIER = 'mr_template-templates'; // Identifier to store all templates in cache_core cache.

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * TemplateCache constructor.
     *
     * @param CacheManager|null $cacheManager
     */
    function __construct(
        CacheManager $cacheManager = null
    )
    {
        $this->cacheManager = $cacheManager ?? GeneralUtility::makeInstance(CacheManager::class);
    }

    /**
     * Short-hand function for the cache
     *
     * @return PhpFrontend
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    protected function getCache(): PhpFrontend
    {
        return $this->cacheManager->getCache('core');
    }

    /**
     * @return false|array
     */
    function get()
    {
        try {
            return $this->getCache()->require(self::IDENTIFIER);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    function set(array $data): bool
    {
        try {
            $value = 'return ' . var_export($data, true) . ';';
            $this->getCache()->set(self::IDENTIFIER, $value);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
