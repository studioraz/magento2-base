<?php
/**
 * Studio Raz
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * https://wiki.studioraz.co.il/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@studioraz.co.il so we can send you a copy immediately.
 *
 * @package     Plumrocket_Base
 * @copyright   Copyright (c) 2016 Studio Raz (https://studioraz.co.il)
 * @license     https://wiki.studioraz.co.il/wiki/EULA  End-user License Agreement
 */

namespace SR\Base\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

/* Uninstall  */
abstract class AbstractUninstall implements UninstallInterface
{

    protected $_configSectionId;
    protected $_attributes = [];
    protected $_tables = [];
    protected $_tablesFields = [];
    protected $_pathes = [];
    protected $_cmsBlocks = [];

    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_cmsBlockFactory;


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Cms\Model\BlockFactory $cmsBlockFactory
    ) {
        $this->_objectManager = $objectManager;
        $this->_cmsBlockFactory = $cmsBlockFactory;
    }


    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $moduleName = $this->getModuleName();
        $_context = $this->_objectManager->create('Magento\Framework\Module\Setup\Context');
        $_setup = new \Magento\Setup\Module\DataSetup($_context);
        $eavSetup = $this->_objectManager->create('Magento\Eav\Setup\EavSetup', ['setup' => $_setup]);

        //remove attribute
        foreach($this->_attributes as $entityTypeName => $attributeNames){
            $entityTypeId = $eavSetup->getEntityTypeId($entityTypeName);
            foreach($attributeNames as $attributeName){
                $eavSetup->removeAttribute($entityTypeId, $attributeName);
            }
        }

        //remove tables
        foreach($this->_tables as $_tableName){
            $_tableName = $setup->getTable($_tableName);
            $setup->getConnection()->dropTable($_tableName);
        }

        //remove tables fields
        foreach($this->_tablesFields as $_tableName => $_fields){
            $_tableName = $setup->getTable($_tableName);
            foreach($_fields as $_field){
                try{
                    $setup->getConnection()->dropColumn( $_tableName, $_field );
                } catch (\Exception $e) {

                }
            }
        }

        //remove static blocks
        foreach($this->_cmsBlocks as $_identifier){
            $cmsBlock = $this->_cmsBlockFactory->create();
            $cmsBlock->load($_identifier);
            if ($cmsBlock->getId()) {
                $cmsBlock->delete();
            }
        }

        //remove config
        if ($this->_configSectionId) {
            $setup->getConnection()->delete(
                $setup->getTable('core_config_data'),
                ['path LIKE ?' => $this->_configSectionId.'/%']
            );

            $setup->getConnection()->delete(
                $setup->getTable('setup_module'),
                ['module = ?' => $moduleName]
            );
        }

        $confFile = BP . '/app/etc/config.php';
        $config = require($confFile);
        if (isset($config['modules'][$moduleName])) {
            unset($config['modules'][$moduleName]);
            $f = fopen($confFile, 'w');
            fwrite($f, '<?php'."\r\n".'return '.var_export($config, true).';');
            fclose($f);
        }

        exec('rm -rf ' . BP . '/var/cache');

        foreach ($this->_pathes as $path) {
            exec('rm -rf ' . BP . $path);
        }

        $setup->endSetup();
    }


    public function getModuleName()
    {
        $className = get_class($this);
        $namespace = substr(
            $className,
            0,
            strpos($className, '\\' . 'Setup')
        );

        return str_replace('\\', '_', $namespace);
    }
}