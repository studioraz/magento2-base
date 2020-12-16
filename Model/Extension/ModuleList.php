<?php
/**
 * Copyright © 2020 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Model\Extension;

use Magento\Framework\Module\ModuleList\Loader;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Module\PackageInfo;

class ModuleList
{
    /**
     * @var array
     */
    private $modules;

    /**
     * List of modules which should not be listed
     *
     * @var string[]
     */
    private $restricted = [
        'SR_Base',
        'SR_Gateway',
    ];

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var Loader
     */
    private $moduleListLoader;

    /**
     * @var PackageInfo
     */
    private $packageInfo;

    /**
     * ModuleList constructor.
     * @param Loader $moduleListLoader
     * @param ModuleListInterface $moduleList
     * @param PackageInfo $packageInfo
     */
    public function __construct(
        Loader $moduleListLoader,
        ModuleListInterface $moduleList,
        PackageInfo $packageInfo
    ) {
        $this->moduleList = $moduleList;
        $this->moduleListLoader = $moduleListLoader;
        $this->packageInfo = $packageInfo;
    }

    /**
     * Returns All available SR modules including Disabled in config.php
     *
     * @return array
     */
    public function getAll(): array
    {
        if ($this->modules !== null) {
            return $this->modules;
        }

        try {
            $all = $this->moduleListLoader->load();
            sort($all);

            $this->modules = [];

            foreach ($all as $item) {
                $moduleName = $item['name'];
                if (strpos($moduleName, 'SR_') === false
                    || in_array($moduleName, $this->getRestricted(), true)
                ) {
                    continue;
                }

                $this->modules[] = [
                    'name' => $moduleName,
                    'version' => $this->packageInfo->getVersion($moduleName),
                    'is_active' => $this->moduleList->has($moduleName),
                ];
            }
        } catch (\Exception $e) {
            $this->modules = [];
        }

        return $this->modules;
    }

    /**
     * @return array
     */
    public function getRestricted(): array
    {
        return $this->restricted;
    }
}
