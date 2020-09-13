<?php

namespace SolidStateNetworks\ddpmodule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * DDPItem Resource Model
 *
 */
class DDPItem extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('solidstatenetworks_ddpmodule_cdnx1', 'cdn_id');
    }
}