<?php
namespace SolidStateNetworks\ddpmodule\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$tableName = "solidstatenetworks_ddpmodule_cdnx1";
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
					'dlm_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					32,
					[],
					'ID for the DLM at Solid State Networks'
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