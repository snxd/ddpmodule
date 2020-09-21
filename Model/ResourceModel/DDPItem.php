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

namespace SolidStateNetworks\ddpmodule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * DDPItem Resource Model
 *
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  MIT https://mit-license.org/
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
