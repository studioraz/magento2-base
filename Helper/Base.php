<?php
/*

Studio Raz

NOTICE OF LICENSE

This source file is subject to the End-user License Agreement
that is available through the world-wide-web at this URL:
https://wiki.studioraz.co.il/wiki/EULA
If you are unable to obtain it through the world-wide-web, please
send an email to support@studioraz.co.il so we can send you a copy immediately.

@package    SR_Base-v1.x.x
@copyright  Copyright (c) 2016 Studio Raz (https://studioraz.co.il)
@license    https://wiki.studioraz.co.il/wiki/EULA  End-user License Agreement

*/

namespace SR\Base\Helper;

class Base extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Helper\Context $context
    ) {

        $this->_objectManager = $objectManager;
        parent::__construct($context);
    }


    public function getConfigSectionId()
    {
        return $this->_configSectionId;
    }


    public function getConfig($path, $store = null, $scope = null)
    {
        if ($scope === null) {
            $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        }
        return $this->scopeConfig->getValue($path, $scope, $store);
    }


    public static function backtrace($title = 'Debug Backtrace:', $echo = true)
    {
        $output     = "";
        $output .= "<hr /><div>" . $title . '<br /><table border="1" cellpadding="2" cellspacing="2">';

        $stacks     = debug_backtrace();

        $output .= "<thead><tr><th><strong>File</strong></th><th><strong>Line</strong></th><th><strong>Function</strong></th>".
            "</tr></thead>";
        foreach($stacks as $_stack)
        {
            if (!isset($_stack['file'])) $_stack['file'] = '[PHP Kernel]';
            if (!isset($_stack['line'])) $_stack['line'] = '';

            $output .=  "<tr><td>{$_stack["file"]}</td><td>{$_stack["line"]}</td>".
                "<td>{$_stack["function"]}</td></tr>";
        }
        $output .=  "</table></div><hr /></p>";
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function moduleExists($moduleName)
    {
        $hasModule = $this->_objectManager->get('Magento\Framework\Module\Manager')->isEnabled('SR' . $moduleName);
        if($hasModule) {
            return $this->_objectManager->get('SR\\'. $moduleName .'\Helper\Data')->moduleEnabled()? 2 : 1;
        }

        return false;
    }

}
