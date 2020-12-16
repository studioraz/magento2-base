<?php
/**
 * Copyright © 2020 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Block\Adminhtml\System\Config\Fieldset;

use Magento\Backend\Block\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\Js;
use SR\Base\Model\Extension\ModuleList as SrModuleList;

class Extensions extends Fieldset
{
    /**
     * @var SrModuleList
     */
    private $moduleList;

    /**
     * Extensions constructor.
     * @param Context $context
     * @param Session $authSession
     * @param Js $jsHelper
     * @param SrModuleList $moduleList
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        SrModuleList $moduleList,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->moduleList = $moduleList;
    }

    /**
     * @inheridoc
     */
    protected function _getChildrenElementsHtml(AbstractElement $element)
    {
        $html = '';

        foreach ($this->moduleList->getAll() as $module) {
            $version = ($module['version'] ?? null) ?: 'X.X.X';
            $enabled = ($module['is_active'] ?? false)
                ? "<span style=\"color:green\"><b>Enabled</b></span>"
                : "<span style=\"color:red\">Disabled</span>";

            $html .= "<tr>";
                $html .= "<td><b>{$this->escapeHtml($module['name'])}</b></td>";
                $html .= "<td>{$this->escapeHtml($version)}&nbsp;&nbsp;{$enabled}</td>";
            $html .= "</tr>";
        }

        return $html;
    }
}
