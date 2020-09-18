<?php
/**
 * Copyright © Solid State Networks, Inc. All rights reserved.
 *
 * @category Class
 * @package  DDPModule
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  OSL-3.0 http://opensource.org/licenses/OSL-3.0
 * @link     http://solidstatenetworks.com
 */

namespace SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * DDPModule Collection for dealing with groups of DDPItem objects
 *
 * @category Class
 * @package  Collection
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  OSL-3.0 http://opensource.org/licenses/OSL-3.0
 * @link     http://solidstatenetworks.com
 * @since    0.0.2
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SolidStateNetworks\ddpmodule\Model\DDPItem', 'SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem');
    }
}
