<?php

namespace SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Contact Resource Model Collection
 *
 * @author      Pierre FAY
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('SolidStateNetworks\ddpmodule\Model\DDPItem', 'SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem');
    }
}