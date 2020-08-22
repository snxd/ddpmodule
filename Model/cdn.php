<?php 
namespace namespace SolidStateNetworks\ddpmodule\Model;

class CDN extends \Magento\Framework\Model\AbstractModel implements \Mageplaza\HelloWorld\Model\Api\Data\TopicInterface //, \Magento\Framework\DataObject\IdentityInterface,

{
	const CACHE_TAG = 'solidstate_cdn';

	protected function _construct()
	{
		$this->_init('SolidStateNetworks\ddpmodule\Model\CDN');
	}

	/*public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}*/
}