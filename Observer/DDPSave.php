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

namespace SolidStateNetworks\ddpmodule\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use SolidStateNetworks\ddpmodule\Model\DDPItemFactory;

/**
 * DDPModule Save event handler
 *
 * @category Class
 * @package  DDPSave
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  OSL-3.0 http://opensource.org/licenses/OSL-3.0
 * @link     http://solidstatenetworks.com
 * @api
 * @method   void execute(Observer $observer)
 * @since    0.0.2
 */
class DDPSave implements ObserverInterface
{

    /**
     * Class Constructor
     *
     * @param RequestInterface $request      The request that provoked the event
     * @param DDPItemFactory   $ditemFactory DDPItem loader
     */
    public function __construct(
        RequestInterface $request,
        DDPItemFactory $ditemFactory
    ) {
        $this->_request   = $request;//$context->getRequest();
        $this->_ditemFactory = $ditemFactory;
    }

    /**
     * Class Constructor
     *
     * @param Observer $observer The observer that captured the event
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //THIS IS WHERE WE WILL SAVE DATA TO THE DATABASE AND RELATE IT TO THE PRODUCT
        $params               = $this->_request->getParams();
        $customFieldData = $params['solidddp'];

        $product = $observer->getProduct();  // you will get product object
        $productId = $product->getId();
        
        $ddpi = $this->_ditemFactory->create();
        $ddpi->load($productId, "product_id");
        $ddpi->setData('product_id', $productId);

        $ddpi->setData('acl', $customFieldData['acl']);
        $ddpi->setData('ttl', $customFieldData['ttl']);
        $ddpi->setData('secret', $customFieldData['secret']);
        $ddpi->setData('dlm_id_win', $customFieldData['dlm_id_win']);
        $ddpi->setData('dlm_id_macos', $customFieldData['dlm_id_macos']);
        $ddpi->setData('enabled', $customFieldData['enabled']);

        $ddpi->save();
    }
}
