<?php

namespace SolidStateNetworks\ddpmodule\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class DDPSave implements ObserverInterface, RequestInterface
{    
	public function __construct(
	   Context $context
	   //other objects
	) {
	   $this->context     = $context;
	   $this->_request   = $context->getRequest();
	   //other objects
	}

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //$_product = $observer->getProduct();  // you will get product object
        //$_sku=$_product->getSku(); // for sku

        //$post = $observer->getController();
        //$data = $post->getRequest()->getPost();
        //$productAsArray = $data['product'];

        $params               = $this->request->getParams();
        $customFieldData = $data['solid_ddp'];

        error_log("observer .. ");
        error_log($customFieldData['dlmId']);

    }   
}