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

namespace SolidStateNetworks\ddpmodule\Model;

use SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem as Resource;
use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

/**
 * DDPModule Model for extension data set
 *
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  MIT https://mit-license.org/
 * @link     http://solidstatenetworks.com
 * @api
 * @method   array modifyData(array $data)
 * @method   Link setProductId(int $value)
 * @since    0.0.2
 */
class DDPItem extends AbstractModel
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem::class);
    }
}
