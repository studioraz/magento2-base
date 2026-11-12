<?php
/**
 * Copyright © 2026 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Model\Extension;

use Magento\Framework\DataObject;

/**
 * Installed Studio Raz composer package enriched with data from the extensions feed.
 */
class Extension extends DataObject
{
    public const STATUS_LATEST = 'latest';
    public const STATUS_OUTDATED = 'outdated';
    public const STATUS_UNKNOWN = 'unknown';

    /**
     * @return string
     */
    public function getPackageName(): string
    {
        return (string)$this->getData('package_name');
    }

    /**
     * Composer package type (magento2-module, magento2-theme, ...)
     *
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->getData('type');
    }

    /**
     * Display name: feed "title", falling back to "extra.title" of the package composer.json
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->getData('title');
    }

    /**
     * Description from the feed, falling back to the package composer.json
     *
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->getData('description');
    }

    /**
     * Installed version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return (string)$this->getData('version');
    }

    /**
     * Latest version according to the feed, empty when unknown
     *
     * @return string
     */
    public function getLatestVersion(): string
    {
        return (string)$this->getData('latest_version');
    }

    /**
     * Release date of the latest version according to the feed
     *
     * @return string
     */
    public function getReleasedAt(): string
    {
        return (string)$this->getData('released_at');
    }

    /**
     * Changelog url from the feed, empty when the feed has none
     *
     * @return string
     */
    public function getChangelogUrl(): string
    {
        return (string)$this->getData('changelog_url');
    }

    /**
     * @return bool
     */
    public function isOutdated(): bool
    {
        return $this->getStatus() === self::STATUS_OUTDATED;
    }

    /**
     * One of STATUS_* constants
     *
     * @return string
     */
    public function getStatus(): string
    {
        $installed = $this->normalize($this->getVersion());
        $latest = $this->normalize($this->getLatestVersion());

        if ($installed === null || $latest === null) {
            return self::STATUS_UNKNOWN;
        }

        return version_compare($installed, $latest, '<') ? self::STATUS_OUTDATED : self::STATUS_LATEST;
    }

    /**
     * Strip a leading "v"; returns null for non numeric versions (e.g. dev-master)
     *
     * @param string $version
     * @return string|null
     */
    private function normalize(string $version): ?string
    {
        $version = ltrim(trim($version), 'vV');

        return preg_match('/^\d+(\.\d+)*([-+.].*)?$/', $version) ? $version : null;
    }
}
