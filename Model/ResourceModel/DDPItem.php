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

namespace SolidStateNetworks\ddpmodule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * DDPItem Resource Model
 *
 * @category Class
 * @package  DDPItem
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  OSL-3.0 http://opensource.org/licenses/OSL-3.0
 * @link     http://solidstatenetworks.com
 * @since    0.0.2
 */
class DDPItem extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('solidstatenetworks_ddpmodule', 'ddp_id');
    }
}
