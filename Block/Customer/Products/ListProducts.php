<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SolidStateNetworks\ddpmodule\Block\Customer\Products;

//require_once("app/code/SolidStateNetworks/ddpmodule/Helper/Akamai/TokenAuth.php"); 
//require_once("../../../Helper/Akamai/InvalidArgumentException.php"); 

use Magento\Downloadable\Model\Link\Purchased\Item;
use Matricali\Security\EdgeAuth\TokenAuth;
use Magento\Catalog\Model\ProductRepository;
use Magento\Downloadable\Model\LinkRepository;
use SolidStateNetworks\ddpmodule\Model\DDPItemFactory;


/**
 * Block to display downloadable links bought by customer
 *
 * @api
 * @since 100.0.2
 */
class DDPListProducts extends \Magento\Downloadable\Block\Customer\Products\ListProducts
{
    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Downloadable\Model\ResourceModel\Link\Purchased\CollectionFactory
     */
    protected $_linksFactory;

    /**
     * @var \Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory
     */
    protected $_itemsFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Downloadable\Model\ResourceModel\Link\Purchased\CollectionFactory $linksFactory
     * @param \Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory $itemsFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Downloadable\Model\LinkRepository $linkRepository,
        DDPItemFactory $ditemFactory,
        \Magento\Downloadable\Model\ResourceModel\Link\Purchased\CollectionFactory $linksFactory,
        \Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory $itemsFactory,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->_linksFactory = $linksFactory;
        $this->_itemsFactory = $itemsFactory;
        $this->_ditemFactory = $ditemFactory;
        $this->_productRepository = $productRepository;
        $this->_linkRepository = $linkRepository;
        parent::__construct($context, $data);
    }

    

    /**
     * Return url to download link
     *
     * @param Item $item
     * @return string
     */
    public function getDownloadUrl($item)
    {
        $productId = $item->getProductId();
        $ddpi = $this->_ditemFactory->create();
        $ddpi->load($productId, "product_id");

        if($ddpi->getData("ddp_id") == null || $ddpi->getData("enabled") != true) {
            return parent::getDownloadUrl($item);
        }

        $product = $this->_productRepository->getById($item->getProductId());
        $links = $this->_linkRepository->getList($product->getSku());

        $dlmid = $ddpi->getData("dlm_id");
        $cdnpass = $ddpi->getData("secret");
        $edgeAuth = new TokenAuth($cdnpass, TokenAuth::ALGORITHM_SHA256);
        $edgeAuth->setAcl($ddpi->getData("acl"));
        $edgeAuth->setWindow($ddpi->getData("ttl"));
        $authUrl = $edgeAuth->generateToken();

        $dlmitems = "";

        foreach($links as &$value) {
            $dlmitems = $dlmitems . '{"name":"' . $value->getTitle() . '", "url":"' . $value->getLinkUrl() . '?__token__=' . $authUrl . '"},';
        }

        $transid = $item->getPurchased()->getOrderId();

        $workflow = '{"analytics":{"transactionId":"' . $transid . '","downloadName":"Magento"},"items":[' . substr($dlmitems, 0, -1) . ']}';

        $wf = urlencode(base64_encode($workflow));
        return "https://stampqa.directdlm.com/stamp/" . $dlmid . "/" . $wf . "/downloader.dmg";
    }

}
