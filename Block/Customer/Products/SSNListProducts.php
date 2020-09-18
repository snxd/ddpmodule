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

namespace SolidStateNetworks\ddpmodule\Block\Customer\Products;

use Magento\Downloadable\Model\Link\Purchased\Item;
use Matricali\Security\EdgeAuth\TokenAuth;
use Magento\Catalog\Model\ProductRepository;
use Magento\Downloadable\Model\LinkRepository;
use SolidStateNetworks\ddpmodule\Model\DDPItemFactory;

/**
 * DDPModule Admin UI field definitions
 *
 * @category Class
 * @package  DDPFields
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  OSL-3.0 http://opensource.org/licenses/OSL-3.0
 * @link     http://solidstatenetworks.com
 * @api
 * @method   array modifyData(array $data)
 * @method   Link setProductId(int $value)
 * @since    0.0.2
 */
class SSNListProducts extends \Magento\Framework\View\Element\Template
{

    private $_currentCustomer;
    private $_linksFactory;
    private $_itemsFactory;

    /**
     * Class contstructor
     *
     * @param Context           $context           Context
     * @param CurrentCustomer   $currentCustomer   Current Customer
     * @param ProductRepository $productRepository Product repo
     * @param LinkRepository    $linkRepository    Link repo
     * @param DDPItemFactory    $ditemFactory      Factory for DDPItem objects
     * @param CollectionFactory $linksFactory      Factory for link objects
     * @param CollectionFactory $itemsFactory      Factory for item objects
     * @param array             $data              context data
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
        $this->_currentCustomer = $currentCustomer;
        $this->_linksFactory = $linksFactory;
        $this->_itemsFactory = $itemsFactory;
        $this->_ditemFactory = $ditemFactory;
        $this->_productRepository = $productRepository;
        $this->_linkRepository = $linkRepository;
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
            ->addFieldToFilter('customer_id', $this->_currentCustomer->getCustomerId())
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
     * @param int $orderId Order Id
     *
     * @return string
     */
    public function getOrderViewUrl(int $orderId)
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
     * @param Item $item Current Item
     *
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
     * Return url to download link for MacOS
     *
     * @param Item $item Current Item
     *
     * @return string
     */
    public function getMacOSDownloadUrl($item)
    {
        return $this->getDownloadUrl($item, 'macos');
    }

    /**
     * Return url to download link for Windows
     *
     * @param Item $item Current Item
     *
     * @return string
     */
    public function getWinDownloadUrl($item)
    {
        return $this->getDownloadUrl($item, 'win');
    }

    /**
     * Helper function to determine if DDP is enabled for the given product
     *
     * @param Item $item Current Item
     *
     * @return bool
     */
    public function isDDPEnabled($item)
    {
        $productId = $item->getProductId();
        $ddpi = $this->_ditemFactory->create();
        $ddpi->load($productId, "product_id");


        if ($ddpi->getData("ddp_id") != null && $ddpi->getData("enabled") == true) {
            return true;
        }

        return false;
    }

    /**
     * Return url to download link
     *
     * @param Item   $item Current Item
     * @param string $os   OS specific links
     *
     * @return string
     */
    public function getDownloadUrl($item, $os = 'win')
    {
        $productId = $item->getProductId();
        $ddpi = $this->_ditemFactory->create();
        $ddpi->load($productId, "product_id");


        if ($ddpi->getData("ddp_id") != null && $ddpi->getData("enabled") == true) {
            $product = $this->_productRepository->getById($item->getProductId());
            $links = $this->_linkRepository->getList($product->getSku());

            $dlmid = $ddpi->getData("dlm_id_win");
            $cdnpass = $ddpi->getData("secret");
            $edgeAuth = new TokenAuth($cdnpass, TokenAuth::ALGORITHM_SHA256);
            $edgeAuth->setAcl($ddpi->getData("acl"));
            $edgeAuth->setWindow($ddpi->getData("ttl"));
            $authUrl = $edgeAuth->generateToken();

            $dlmitems = "";

            foreach ($links as &$value) {
                $dlmitems = $dlmitems . '{"name":"' . $value->getTitle() . '", "url":"' . $value->getLinkUrl() . '?__token__=' . $authUrl . '"},';
            }

            $transid = $item->getPurchased()->getOrderId();

            $workflow = '{"analytics":{"transactionId":"' . $transid . '","downloadName":"Magento"},"items":[' . substr($dlmitems, 0, -1) . ']}';

            $wf = urlencode(base64_encode($workflow));

            $dlmfile = "downloader.exe";
            if ($os == 'macos') {
                $dlmfile = "downloader.dmg";
                $dlmid = $ddpi->getData("dlm_id_macos");
            }

            return "https://stampqa.directdlm.com/stamp/" . $dlmid . "/" . $wf . "/" . $dlmfile;
        }

        return $this->getUrl('downloadable/download/link', ['id' => $item->getLinkHash(), '_secure' => true]);
    }

    /**
     * Return true if target of link new window
     *
     * @return bool
     */
    public function getIsOpenInNewWindow()
    {
        return $this->_scopeConfig->isSetFlag(
            \Magento\Downloadable\Model\Link::XML_PATH_TARGET_NEW_WINDOW,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
