<?php
/**
 * Copyright © Solid State Networks, Inc. All rights reserved.
 *
 * @category Class
 * @package  DDPModule
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  MIT https://mit-license.org/
 * @link     http://solidstatenetworks.com
 */

namespace SolidStateNetworks\ddpmodule\Setup;

/**
 * DDPModule Database setup script
 *
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  MIT https://mit-license.org/
 * @link     http://solidstatenetworks.com
 * @api
 * @method   void install(SchemaSetupInterface $setup, ModuleContextInterface $context)
 * @since    0.0.2
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    /**
     * Install function
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup   Setup Interface
     * @param \Magento\Framework\Setup\ModuleContextInterface $context Context Interface
     *
     * @return void
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $tableName = "solidstatenetworks_ddpmodule";
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable($tableName)
            )
                ->addColumn(
                    'ddp_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'DDP ID'
                )
                ->addColumn(
                    'enabled',
                    \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    1,
                    [],
                    'Enable or disable the DDP plugin for this product'
                )
                ->addColumn(
                    'dlm_id_win',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    36,
                    [],
                    'ID for the Windows DLM at Solid State Networks'
                )
                ->addColumn(
                    'dlm_id_macos',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    36,
                    [],
                    'ID for the MacOS DLM at Solid State Networks'
                )
                ->addColumn(
                    'product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    8,
                    [],
                    'The Magento product associated with this record'
                )
                ->addColumn(
                    'acl',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'CDN Token ACL'
                )
                ->addColumn(
                    'ttl',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    8,
                    [],
                    'CDN Token time to live in seconds'
                )
                ->addColumn(
                    'secret',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'CDN token shared secret'
                )
                ->setComment('CDN Table');
            $installer->getConnection()->createTable($table);

            /*$installer->getConnection()->addIndex(
                $installer->getTable($tableName),
                $setup->getIdxName(
                    $installer->getTable($tableName),
                    ['name','url_key','post_content','tags','featured_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name','url_key','post_content','tags','featured_image'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );*/
        }
        $installer->endSetup();
    }
}
