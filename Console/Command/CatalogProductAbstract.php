<?php
namespace SR\Base\Console\Command;

use Magento\Framework\Acl\Role\Registry;
use Symfony\Component\Console\Command\Command;

class CatalogProductAbstract extends AbstractCommand
{

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magento\Framework\App\State
     */

    protected $_state;
    /**
     * AbstractCommand constructor.
     * @param ObjectManagerInterface $manager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $manager,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product $product,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\State $state
    )
    {
        $this->_registry = $registry;
        $this->_productRepository = $productRepository;
        $this->_product = $product;
        $this->_state = $state;
        parent::__construct($manager);
    }
}