<?php
/**
 * Copyright © 2026 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Model\Extension;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Cache\Type\Config as ConfigCache;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

/**
 * Loads the StudioRaz extensions feed (latest version + changelog url per composer package).
 */
class Feed
{
    public const XML_PATH_ENABLED = 'srbase/update_check/enabled';
    public const FEED_URL = 'https://studioraz.github.io/ci-extensions-feed/feed.json';

    public const SCHEMA_VERSION = 1;
    public const CACHE_KEY = 'sr_base_extension_feed';
    public const CACHE_TAG = 'SR_BASE_EXTENSION_FEED';
    public const CACHE_LIFETIME = 3600;
    public const CACHE_LIFETIME_FAILURE = 600;
    public const TIMEOUT = 5;

    /**
     * @var CurlFactory
     */
    private $curlFactory;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array|null
     */
    private $packages;

    /**
     * @param CurlFactory $curlFactory
     * @param CacheInterface $cache
     * @param Json $json
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        CurlFactory $curlFactory,
        CacheInterface $cache,
        Json $json,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->curlFactory = $curlFactory;
        $this->cache = $cache;
        $this->json = $json;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Whether the update check is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }

    /**
     * Feed packages keyed by composer package name. Empty array when disabled or on any failure.
     *
     * @return array<string, array>
     */
    public function getPackages(): array
    {
        if ($this->packages !== null) {
            return $this->packages;
        }

        $this->packages = [];

        if (!$this->isEnabled()) {
            return $this->packages;
        }

        $url = self::FEED_URL;
        $cacheKey = self::CACHE_KEY;

        try {
            $cached = $this->cache->load($cacheKey);
            if ($cached !== false && $cached !== null) {
                $data = $this->json->unserialize((string)$cached);
                $this->packages = is_array($data) ? $data : [];

                return $this->packages;
            }
        } catch (\Exception $e) {
            $this->packages = [];
        }

        $packages = $this->fetch($url);

        try {
            $this->cache->save(
                $this->json->serialize($packages ?? []),
                $cacheKey,
                [self::CACHE_TAG, ConfigCache::CACHE_TAG],
                $packages === null ? self::CACHE_LIFETIME_FAILURE : self::CACHE_LIFETIME
            );
        } catch (\Exception $e) {
            $this->logger->warning('SR_Base: unable to cache extensions feed: ' . $e->getMessage());
        }

        $this->packages = $packages ?? [];

        return $this->packages;
    }

    /**
     * Fetch & validate the feed. Returns null on any failure.
     *
     * @param string $url
     * @return array|null
     */
    private function fetch(string $url): ?array
    {
        try {
            $curl = $this->curlFactory->create();
            $curl->setTimeout(self::TIMEOUT);
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);
            $curl->setOption(CURLOPT_FOLLOWLOCATION, true);
            $curl->get($url);

            if ((int)$curl->getStatus() !== 200) {
                throw new \RuntimeException('HTTP status ' . $curl->getStatus());
            }

            $data = $this->json->unserialize((string)$curl->getBody());

            if (!is_array($data)
                || (int)($data['schema'] ?? 0) !== self::SCHEMA_VERSION
                || !isset($data['packages'])
                || !is_array($data['packages'])
            ) {
                throw new \RuntimeException('unexpected feed format');
            }

            $packages = [];
            foreach ($data['packages'] as $name => $package) {
                if (is_string($name) && is_array($package)) {
                    $packages[$name] = $package;
                }
            }

            return $packages;
        } catch (\Throwable $e) {
            $this->logger->warning(sprintf('SR_Base: unable to load extensions feed %s: %s', $url, $e->getMessage()));

            return null;
        }
    }
}
