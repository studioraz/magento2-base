<?php
/**
 * Copyright © 2020 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\Base\Block\Adminhtml\System\Config\Fieldset;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Template;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\Js;
use SR\Base\Model\Extension\ExtensionList;

class Extensions extends Fieldset
{
    public const TEMPLATE = 'SR_Base::system/config/extensions.phtml';

    /**
     * @var ExtensionList
     */
    private $extensionList;

    /**
     * Extensions constructor.
     * @param Context $context
     * @param Session $authSession
     * @param Js $jsHelper
     * @param ExtensionList $extensionList
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        ExtensionList $extensionList,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->extensionList = $extensionList;
    }

    /**
     * @inheritdoc
     */
    protected function _getChildrenElementsHtml(AbstractElement $element)
    {
        /** @var Template $list */
        $list = $this->getLayout()->createBlock(Template::class);
        $list->setTemplate(self::TEMPLATE)
            ->setData('extension_list', $this->extensionList);

        return '<tr><td colspan="5">' . $list->toHtml() . '</td></tr>';
    }
}
