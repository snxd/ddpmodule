<?php
namespace Mageplaza\HelloWorld\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '0.0.1', '<')) {
			if (!$installer->tableExists($tableName)) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable($tableName)
			)
				->addColumn(
					'cdn_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'CDN ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Post Name'
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
		
		}

		$installer->endSetup();
	}
}