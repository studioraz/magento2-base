<?php
/*
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENCE file for license details.
 */

declare(strict_types=1);

namespace SR\Base\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class Editor extends Field
{
    public function __construct(
        Context                 $context,
        protected WysiwygConfig $wysiwygConfig,
        array                   $data = [],
        ?SecureHtmlRenderer     $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        $element->setWysiwyg(true);
        $element->setConfig($this->wysiwygConfig->getConfig($element));

        return parent::_getElementHtml($element);
    }
}
