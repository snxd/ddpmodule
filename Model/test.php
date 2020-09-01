<?php 
namespace namespace SolidStateNetworks\ddpmodule\Model;

class Test extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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