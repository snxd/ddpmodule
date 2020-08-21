<?php 
namespace namespace SolidStateNetworks\ddpmodule\Model;

class CDN extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface,
\Mageplaza\HelloWorld\Model\Api\Data\TopicInterface
{
	const CACHE_TAG = 'solidstate_cdn';

	protected function _construct()
	{
		$this->_init('SolidStateNetworks\ddpmodule\Model\CDN');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}
}