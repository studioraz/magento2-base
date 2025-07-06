<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace SR\Base\Helper;

use Magento\Framework\View\Element\Template;

class Css
{
    protected static Template $block;
    protected static string $name;

    /**
     * @param Template $block
     * @param string $name
     *
     * @return void
     */
    public static function setBlock(Template $block, string $name): void
    {
        self::$block = $block;
        self::$name = $name;
    }

    /**
     * @param string $blockPartName
     * @param string $css
     *
     * @return string
     */
    public static function css(string $blockPartName, string $css = ''): string
    {
        $blockName = self::$name;
        $dataKey = $blockPartName !== '' ? "{$blockPartName}_css" : "css";
        $cssName = $blockPartName !== '' ? "{$blockName}-{$blockPartName}" : $blockName;

        return self::$block->getData($dataKey) ?: "$cssName {$css}";
    }
}
