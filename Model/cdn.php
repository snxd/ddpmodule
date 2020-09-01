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

	public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => 'one', 'label' => __('one')],
                ['value' => 'two', 'label' => __('two')]
            ];
        }
        return $this->_options;
    }
    final public function toOptionArray()
    {
         return array(
            array('value' => 'one', 'label' => __('one')),
            array('value' => 'two', 'label' => __('two'))
         );
     }
}