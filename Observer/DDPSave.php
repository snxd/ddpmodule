<?php

namespace SolidStateNetworks\ddpmodule\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use SolidStateNetworks\ddpmodule\Model\DDPItemFactory;

class DDPSave implements ObserverInterface
{    
	public function __construct(
		RequestInterface $request,
		DDPItemFactory $ditemFactory
	   //Context $context
	   //other objects
	) {
	   //$this->context     = $context;
	   $this->_request   = $request;//$context->getRequest();
	   $this->_ditemFactory = $ditemFactory;
	   //other objects
	}

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

        $ddpi->setData('acl',$customFieldData['acl']);
        $ddpi->setData('ttl',$customFieldData['ttl']);
        $ddpi->setData('secret',$customFieldData['secret']);
        $ddpi->setData('dlm_id_win',$customFieldData['dlm_id_win']);
        $ddpi->setData('dlm_id_macos',$customFieldData['dlm_id_macos']);
        $ddpi->setData('enabled',$customFieldData['enabled']);

        $ddpi->save();
    }   
}