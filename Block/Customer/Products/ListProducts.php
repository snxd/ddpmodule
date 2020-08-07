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



/**
 * Block to display downloadable links bought by customer
 *
 * @api
 * @since 100.0.2
 */
class ListProducts extends \Magento\Framework\View\Element\Template
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
        \Magento\Downloadable\Model\ResourceModel\Link\Purchased\CollectionFactory $linksFactory,
        \Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory $itemsFactory,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->_linksFactory = $linksFactory;
        $this->_itemsFactory = $itemsFactory;
        $this->_productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $purchased = $this->_linksFactory->create()
            ->addFieldToFilter('customer_id', $this->currentCustomer->getCustomerId())
            ->addOrder('created_at', 'desc');
        $this->setPurchased($purchased);
        $purchasedIds = [];
        foreach ($purchased as $_item) {
            $purchasedIds[] = $_item->getId();
        }
        if (empty($purchasedIds)) {
            $purchasedIds = [null];
        }
        $purchasedItems = $this->_itemsFactory->create()->addFieldToFilter(
            'purchased_id',
            ['in' => $purchasedIds]
        )->addFieldToFilter(
            'status',
            ['nin' => [Item::LINK_STATUS_PENDING_PAYMENT, Item::LINK_STATUS_PAYMENT_REVIEW]]
        )->setOrder(
            'item_id',
            'desc'
        );
        $this->setItems($purchasedItems);
    }

    /**
     * Enter description here...
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock(
            \Magento\Theme\Block\Html\Pager::class,
            'downloadable.customer.products.pager'
        )->setCollection(
            $this->getItems()
        )->setPath('downloadable/customer/products');
        $this->setChild('pager', $pager);
        $this->getItems()->load();
        foreach ($this->getItems() as $item) {
            $item->setPurchased($this->getPurchased()->getItemById($item->getPurchasedId()));
        }
        return $this;
    }

    /**
     * Return order view url
     *
     * @param integer $orderId
     * @return string
     */
    public function getOrderViewUrl($orderId)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $orderId]);
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/');
    }

    /**
     * Return number of left downloads or unlimited
     *
     * @param Item $item
     * @return \Magento\Framework\Phrase|int
     */
    public function getRemainingDownloads($item)
    {
        if ($item->getNumberOfDownloadsBought()) {
            $downloads = $item->getNumberOfDownloadsBought() - $item->getNumberOfDownloadsUsed();
            return $downloads;
        }
        return __('Unlimited');
    }

    /**
     * Return url to download link
     *
     * @param Item $item
     * @return string
     */
    public function getDownloadUrl($item)
    {

        //return "farts";
        $url = $this->getUrl('downloadable/download/link', ['id' => $item->getLinkHash(), '_secure' => true]);


        $dlmid = "no";//$this->getProduct()->getAttributeText('DLMID');
        //$prod = $this->getPurchased()->getItemById($item->getPurchasedId());
        $productId = $item->getProductId();

        error_log("Start 1");
        $product = $this->_productRepository->getById($productId);
        error_log("Start 2");
        $cl = $product->getCustomAttribute('DLMID');
        error_log("Start 3" . get_class($cl) . " " . $cl->getValue());

        //error_log("even sooner token auth" . getcwd());
        //error_log("before token auth" . $dlmid . " " . $prod);
        //error_log(TokenAuth);
        $edgeAuth = new TokenAuth('aabbccddeeff00112233445566', TokenAuth::ALGORITHM_SHA256);
        error_log("after token auth " . $cl . " ");
        $authUrl = $edgeAuth->generateToken();
        error_log($authUrl);


        $wf = base64_encode("{" . $url . $authUrl . "}");
        return "https://stamp.directdlm.com/stamp/bcb9ed17-ebc7-4344-94e1-1b88e321b0a2/" . $wf . "/downloader.exe";
    }

    /**
     * Return true if target of link new window
     *
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsOpenInNewWindow()
    {
        return $this->_scopeConfig->isSetFlag(
            \Magento\Downloadable\Model\Link::XML_PATH_TARGET_NEW_WINDOW,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
