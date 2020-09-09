<?php

namespace SolidStateNetworks\ddpmodule\Observer;

use Magento\Framework\Event\ObserverInterface;

class DDPSave implements ObserverInterface
{    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //$_product = $observer->getProduct();  // you will get product object
        //$_sku=$_product->getSku(); // for sku

        $post = $observer->getController();
        $data = $post->getRequest()->getPost();
        $productAsArray = $data['product'];

        //$params               = $this->request->getParams();
        $customFieldData = $data['solid_ddp'];

        error_log("observer .. ");
        error_log($customFieldData['dlmId']);

    }   
}