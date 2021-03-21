<?php
/**
 * Copyright © 2021 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SR\Base\Model\View\Element;

use SR\Base\Utils\ArrayValueByPath;

class JsLayoutProcessor
{
    /**
     * @var ArrayValueByPath
     */
    protected $arrayValueByPath;

    /**
     * @param ArrayValueByPath $arrayValueByPath
     */
    public function __construct(
        ArrayValueByPath $arrayValueByPath
    ) {
        $this->arrayValueByPath = $arrayValueByPath;
    }

    const MOVE_JS_LAYOUT_ELEMENT_DIRECTION = 'moveTo';

    /**
     * @var array
     */
    protected $jsLayout;

    public function process(array &$jsLayout)
    {
        $this->jsLayout = &$jsLayout;

        $this->processElementsMove($jsLayout);
    }


    /**
     * @param array $array
     * @param array $path
     * @return array
     */
    public function processElementsMove(array $array, array $path = []): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if ($key === self::MOVE_JS_LAYOUT_ELEMENT_DIRECTION) {
                $this->moveElement($this->jsLayout, implode('/', $path), $value);
            }

            $currentPath = array_merge($path, [$key]);

            if (is_array($value)) {
                $result = array_merge($result, $this->processElementsMove($value, $currentPath));
            } else {
                $result[] = join('/', $currentPath);
            }
        }

        return $result;
    }

    /**
     * @param array  $jsLayout
     * @param string $elementPath
     * @param string $destinationPath
     * @return bool returns true if the element was moved successfully
     */
    public function moveElement(array &$jsLayout, string $elementPath, string $destinationPath): bool
    {
        // NOTE: get string part after the last "/" in the path
        $elementName = substr($elementPath, strrpos($elementPath, '/') + 1);
        $target      = $this->arrayValueByPath->get($jsLayout, $elementPath);
        $destination = $this->arrayValueByPath->get($jsLayout, $destinationPath);

        // NOTE: if the element or the destination not found in the layout
        if (!$target || !$destination) {
            return false;
        }

        // NOTE: Don't actually remove target element on move - only disable it
        $targetUpdateConfig = [
            'config' => [
                'componentDisabled' => true
            ]
        ];

        // NOTE: Wrap target element in the "children" container
        $newElement = [
            'children' => [
                $elementName => $target
            ]
        ];

        $updatedTargetLayout      = $this->mergeConfigs($target, $targetUpdateConfig);
        $updatedDestinationLayout = $this->mergeConfigs($destination, $newElement);

        // NOTE: Cancel the move if merge had errors
        if ($updatedTargetLayout === null || $updatedDestinationLayout === null) {
            return false;
        }

        // NOTE: Update target element layout to disable the old element
        $this->arrayValueByPath->set($jsLayout, $elementPath, $updatedTargetLayout);

        // NOTE: Update destination container to inject the new element
        $this->arrayValueByPath->set($jsLayout, $destinationPath, $updatedDestinationLayout);

        return true;
    }

    /**
     * @param array $originalConfig
     * @param array $updateConfig
     * @return array|null an array, or null if an error occurs
     */
    public function mergeConfigs(array $originalConfig, array $updateConfig): ?array
    {
        return array_replace_recursive($originalConfig, $updateConfig);
    }
}
