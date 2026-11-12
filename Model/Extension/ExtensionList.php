<?php
/**
 * Copyright © 2026 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Model\Extension;

/**
 * Installed Studio Raz composer packages merged with the extensions feed.
 */
class ExtensionList
{
    /**
     * @var PackageList
     */
    private $packageList;

    /**
     * @var Feed
     */
    private $feed;

    /**
     * @var ExtensionFactory
     */
    private $extensionFactory;

    /**
     * @var Extension[]|null
     */
    private $extensions;

    /**
     * @param PackageList $packageList
     * @param Feed $feed
     * @param ExtensionFactory $extensionFactory
     */
    public function __construct(
        PackageList $packageList,
        Feed $feed,
        ExtensionFactory $extensionFactory
    ) {
        $this->packageList = $packageList;
        $this->feed = $feed;
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * One entry per installed package: Update available first, then Unknown, then Up to date
     *
     * @return Extension[]
     */
    public function getAll(): array
    {
        if ($this->extensions !== null) {
            return $this->extensions;
        }

        $feedPackages = $this->feed->getPackages();
        $this->extensions = [];

        foreach ($this->packageList->getAll() as $package) {
            $name = $package['name'];
            $feedData = $feedPackages[$name] ?? [];

            $this->extensions[] = $this->extensionFactory->create([
                'data' => [
                    'package_name' => $name,
                    'type' => $package['type'],
                    'version' => $package['version'],
                    'title' => trim((string)($feedData['title'] ?? '')) ?: $package['title'],
                    'description' => (string)($feedData['description'] ?? '') ?: $package['description'],
                    'latest_version' => (string)($feedData['latest_version'] ?? ''),
                    'released_at' => (string)($feedData['released_at'] ?? ''),
                    'changelog_url' => (string)($feedData['changelog_url'] ?? ''),
                ],
            ]);
        }

        usort($this->extensions, [$this, 'compare']);

        return $this->extensions;
    }

    /**
     * Number of packages per status
     *
     * @return array<string, int> keys: Extension::STATUS_*
     */
    public function getStatusCounts(): array
    {
        $counts = [
            Extension::STATUS_OUTDATED => 0,
            Extension::STATUS_UNKNOWN => 0,
            Extension::STATUS_LATEST => 0,
        ];
        foreach ($this->getAll() as $extension) {
            $counts[$extension->getStatus()]++;
        }

        return $counts;
    }

    /**
     * Number of outdated packages
     *
     * @return int
     */
    public function getOutdatedCount(): int
    {
        return $this->getStatusCounts()[Extension::STATUS_OUTDATED];
    }

    /**
     * @return string
     */
    public function getFeedUrl(): string
    {
        return Feed::FEED_URL;
    }

    /**
     * @return bool
     */
    public function isUpdateCheckEnabled(): bool
    {
        return $this->feed->isEnabled();
    }

    /**
     * Sort: Update available, Unknown, Up to date; then by package name
     *
     * @param Extension $a
     * @param Extension $b
     * @return int
     */
    private function compare(Extension $a, Extension $b): int
    {
        $order = [Extension::STATUS_OUTDATED => 0, Extension::STATUS_UNKNOWN => 1, Extension::STATUS_LATEST => 2];

        return [$order[$a->getStatus()], $a->getPackageName()] <=> [$order[$b->getStatus()], $b->getPackageName()];
    }
}
