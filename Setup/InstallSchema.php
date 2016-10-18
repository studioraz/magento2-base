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

namespace SR\Base\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{


    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /*
        $table = $installer->getConnection()
            ->newTable($installer->getTable('sr_product'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'signature',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Signature'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Status'
            )
            ->addColumn(
                'date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'Date'
            )
            ->setComment('Studio Raz Product Signature');
        $installer->getConnection()->createTable($table);
        */
        
        $installer->endSetup();

    }
}
