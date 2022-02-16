<?php

namespace Scarbous\MrTemplate\Cache;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

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
     * @param CacheManager $cacheManager
     */
    function __construct(
        CacheManager $cacheManager
    )
    {
        $this->cacheManager = $cacheManager;
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
            $this->getCache()->set(
                self::IDENTIFIER,
                "return unserialize('" . serialize($data) . "');"
            );
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
