<?php
/**
 * Copyright © 2026 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Model\Extension;

use Composer\InstalledVersions;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Studio Raz composer packages installed on this instance (as recorded by Composer).
 */
class PackageList
{
    public const VENDOR_PREFIX = 'studioraz/';

    /**
     * Composer package types to list
     *
     * @var string[]
     */
    private $types = [
        'magento2-module',
        'magento2-theme',
        'magento2-library',
    ];

    /**
     * Packages which should not be listed
     *
     * @var string[]
     */
    private $restricted = [
        'studioraz/magento2-base',
        'studioraz/magento2-gateway',
    ];

    /**
     * @var Json
     */
    private $json;

    /**
     * @var array|null
     */
    private $packages;

    /**
     * @param Json $json
     * @param string[] $restricted
     */
    public function __construct(
        Json $json,
        array $restricted = []
    ) {
        $this->json = $json;
        $this->restricted = array_values(array_unique(array_merge($this->restricted, $restricted)));
    }

    /**
     * Installed Studio Raz packages sorted by name.
     *
     * Each row: ['name' => string, 'type' => string, 'version' => string, 'title' => string, 'description' => string]
     *
     * @return array
     */
    public function getAll(): array
    {
        if ($this->packages !== null) {
            return $this->packages;
        }

        $this->packages = [];

        try {
            foreach (InstalledVersions::getAllRawData() as $installed) {
                foreach ($installed['versions'] ?? [] as $name => $package) {
                    $name = (string)$name;
                    if (isset($this->packages[$name])
                        || strpos($name, self::VENDOR_PREFIX) !== 0
                        || in_array($name, $this->restricted, true)
                        || !isset($package['install_path'], $package['pretty_version'])
                        || !in_array($package['type'] ?? '', $this->types, true)
                    ) {
                        continue;
                    }

                    $composer = $this->readComposerJson((string)$package['install_path']);

                    $this->packages[$name] = [
                        'name' => $name,
                        'type' => (string)$package['type'],
                        'version' => (string)$package['pretty_version'],
                        'title' => trim((string)($composer['extra']['title'] ?? '')),
                        'description' => trim((string)($composer['description'] ?? '')),
                    ];
                }
            }
        } catch (\Throwable $e) {
            $this->packages = [];
        }

        ksort($this->packages);
        $this->packages = array_values($this->packages);

        return $this->packages;
    }

    /**
     * Installed package composer.json (title/description used when the feed has none)
     *
     * @param string $installPath
     * @return array
     */
    private function readComposerJson(string $installPath): array
    {
        $file = $installPath . '/composer.json';
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        if (!is_file($file)) {
            return [];
        }

        try {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $data = $this->json->unserialize((string)file_get_contents($file));
        } catch (\Exception $e) {
            return [];
        }

        return is_array($data) ? $data : [];
    }
}
