<?php
/**
 * Copyright © 2021 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SR\Base\Plugin\Framework\View\Element;

use Magento\Framework\Profiler;
use Magento\Framework\View\Element\AbstractBlock;
use SR\Base\Model\View\Element\JsLayoutProcessor;

class AbstractBlockAfterGetJsLayoutPlugin
{
    /**
     * @var JsLayoutProcessor
     */
    protected $jsLayoutProcessor;

    /**
     * @param JsLayoutProcessor $jsLayoutProcessor
     */
    public function __construct(
        JsLayoutProcessor $jsLayoutProcessor
    ) {
        $this->jsLayoutProcessor = $jsLayoutProcessor;
    }

    /**
     * @param AbstractBlock $subject
     * @param string        $jsLayout
     * @return string
     */
    public function afterGetJsLayout(AbstractBlock $subject, $jsLayout): string
    {
        if (!$jsLayout) {
            return $jsLayout;
        }
        Profiler::start(__METHOD__);

        $_jsLayout = json_decode($jsLayout, true);

        $this->jsLayoutProcessor->process($_jsLayout);

        Profiler::stop(__METHOD__);

        return json_encode($_jsLayout);
    }
}
