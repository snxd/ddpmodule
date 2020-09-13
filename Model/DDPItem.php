<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SolidStateNetworks\ddpmodule\Model;

use SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem as Resource;
use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

/**
 * DDPItem link model
 *
 *
 */
class DDPItem extends AbstractModel
{


    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SolidStateNetworks\ddpmodule\Model\ResourceModel\DDPItem::class);
    }
}