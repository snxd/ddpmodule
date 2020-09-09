<?php

namespace SolidStateNetworks\ddpmodule\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class DDPSave implements ObserverInterface
{    
	public function __construct(
		RequestInterface $request
	   //Context $context
	   //other objects
	) {
	   //$this->context     = $context;
	   $this->_request   = $request;//$context->getRequest();
	   //other objects
	}

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //$_product = $observer->getProduct();  // you will get product object
        //$_sku=$_product->getSku(); // for sku

        //$post = $observer->getController();
        //$data = $post->getRequest()->getPost();
        //$productAsArray = $data['product'];

        $params               = $this->_request->getParams();
        $customFieldData = $params['solid_ddp'];

        error_log("observer .. ");
        error_log($customFieldData['dlmId']);

    }   
}