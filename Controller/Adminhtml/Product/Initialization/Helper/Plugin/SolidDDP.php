<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SolidStateNetworks\ddpmodule\Controller\Adminhtml\Product\Initialization\Helper\Plugin;

use Magento\Framework\App\RequestInterface;

/**
 * Class for initialization downloadable info from request.
 */
class SolidDDP
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request

    ) {
        $this->request = $request;

    }

    /**
    * @param Observer $observer
    */
    public function execute(Observer $observer)
    {
       $params               = $this->request->getParams();
       $customFieldData = $params['solid_ddp'];

       error_log("field tet1 ");
       error_log($customFieldData['dlmId']);
       //logic to process custom fields data
       // ...
    }

    /**
     * Prepare product to save
     *
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $subject
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function afterInitialize(
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $subject,
        \Magento\Catalog\Model\Product $product
    ) {
        error_log("field afterInit ");

        $params               = $this->request->getParams();
        $customFieldData = $params['solid_ddp'];

        error_log("field tet2 ");
        error_log($customFieldData['dlmId']);
        /*if ($downloadable = $this->request->getPost('downloadable')) {
            $product->setTypeId(Type::TYPE_DOWNLOADABLE);
            $product->setDownloadableData($downloadable);
            $extension = $product->getExtensionAttributes();
            $productLinks = $product->getTypeInstance()->getLinks($product);
            $productSamples = $product->getTypeInstance()->getSamples($product);
            if (isset($downloadable['link']) && is_array($downloadable['link'])) {
                $links = [];
                foreach ($downloadable['link'] as $linkData) {
                    if (!$linkData || (isset($linkData['is_delete']) && $linkData['is_delete'])) {
                        continue;
                    } else {
                        $linkData = $this->processLink($linkData, $productLinks);
                        $links[] = $this->linkBuilder->setData(
                            $linkData
                        )->build(
                            $this->linkFactory->create()
                        );
                    }
                }
                $extension->setDownloadableProductLinks($links);
            } else {
                $extension->setDownloadableProductLinks([]);
            }
            if (isset($downloadable['sample']) && is_array($downloadable['sample'])) {
                $samples = [];
                foreach ($downloadable['sample'] as $sampleData) {
                    if (!$sampleData || (isset($sampleData['is_delete']) && (bool)$sampleData['is_delete'])) {
                        continue;
                    } else {
                        $sampleData = $this->processSample($sampleData, $productSamples);
                        $samples[] = $this->sampleBuilder->setData(
                            $sampleData
                        )->build(
                            $this->sampleFactory->create()
                        );
                    }
                }
                $extension->setDownloadableProductSamples($samples);
            } else {
                $extension->setDownloadableProductSamples([]);
            }
            $product->setExtensionAttributes($extension);
            if ($product->getLinksPurchasedSeparately()) {
                $product->setTypeHasRequiredOptions(true)->setRequiredOptions(true);
            } else {
                $product->setTypeHasRequiredOptions(false)->setRequiredOptions(false);
            }
        }*/
        return $product;
    }

    /**
     * Compare file path from request with DB and set status.
     *
     * @param array $data
     * @param string|null $file
     * @return array
     */
    private function processFileStatus(array $data, ?string $file): array
    {
        if (isset($data['type']) && $data['type'] === Download::LINK_TYPE_FILE && isset($data['file']['0']['file'])) {
            if ($data['file'][0]['file'] !== $file) {
                $data['file'][0]['status'] = 'new';
            } else {
                $data['file'][0]['status'] = 'old';
            }
        }

        return $data;
    }
}
