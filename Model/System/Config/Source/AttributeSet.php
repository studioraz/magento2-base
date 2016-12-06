<?php

/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * See STUDIORAZ_COPYING.txt for license details.
 */
namespace SR\Base\Model\System\Config\Source;

/**
 * Config category source
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AttributeSet implements \Magento\Framework\Option\ArrayInterface
{

    /**#@+
     * Entity types
     */
    const ENTITY_TYPE_PRODUCT = 4;

    /**
     * @var null|array
     */
    protected $options;

    /**
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array|null
     */
    public function toOptionArray($addEmpty = true)
    {

        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->collectionFactory->create();

        $collection->setEntityTypeFilter(self::ENTITY_TYPE_PRODUCT)->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select --'), 'value' => ''];
        }
        foreach ($collection as $attributeset) {
            $options[] = ['label' => $attributeset->getAttributeSetName(), 'value' => $attributeset->getAttributeSetId()];
        }

        return $options;
    }
}
