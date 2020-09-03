<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SolidStateNetworks\ddpmodule\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\DB\Helper as DbHelper;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\AuthorizationInterface;
use Magento\Backend\Model\Auth\Session;

/**
 * Data provider for categories field of product page
 *
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @since 101.0.0
 */
class Cdn extends AbstractModifier
{
    /**#@+
     * Category tree cache id
     */
    const CATEGORY_TREE_ID = 'CATALOG_PRODUCT_CATEGORY_TREE';
    /**#@-*/

    /**
     * @var CategoryCollectionFactory
     * @since 101.0.0
     */
    protected $categoryCollectionFactory;

    /**
     * @var DbHelper
     * @since 101.0.0
     */
    protected $dbHelper;

    /**
     * @var array
     * @deprecated 101.0.0
     * @since 101.0.0
     */
    protected $categoriesTrees = [];

    /**
     * @var LocatorInterface
     * @since 101.0.0
     */
    protected $locator;

    /**
     * @var UrlInterface
     * @since 101.0.0
     */
    protected $urlBuilder;

    /**
     * @var ArrayManager
     * @since 101.0.0
     */
    protected $arrayManager;

    /**
     * @var CacheInterface
     */
    private $cacheManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param LocatorInterface $locator
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param DbHelper $dbHelper
     * @param UrlInterface $urlBuilder
     * @param ArrayManager $arrayManager
     * @param SerializerInterface $serializer
     * @param AuthorizationInterface $authorization
     * @param Session $session
     */
    public function __construct(
        LocatorInterface $locator,
        CategoryCollectionFactory $categoryCollectionFactory,
        DbHelper $dbHelper,
        UrlInterface $urlBuilder,
        ArrayManager $arrayManager,
        SerializerInterface $serializer = null,
        AuthorizationInterface $authorization = null,
        Session $session = null
    ) {
        $this->locator = $locator;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->dbHelper = $dbHelper;
        $this->urlBuilder = $urlBuilder;
        $this->arrayManager = $arrayManager;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(SerializerInterface::class);
        $this->authorization = $authorization ?: ObjectManager::getInstance()->get(AuthorizationInterface::class);
        $this->session = $session ?: ObjectManager::getInstance()->get(Session::class);
    }

    /**
     * Retrieve cache interface
     *
     * @return CacheInterface
     * @deprecated 101.0.3
     */
    private function getCacheManager(): CacheInterface
    {
        if (!$this->cacheManager) {
            $this->cacheManager = ObjectManager::getInstance()
                ->get(CacheInterface::class);
        }
        return $this->cacheManager;
    }

    /**
     * @inheritdoc
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->createNewCdnModal($meta);
        $meta = $this->customizeCdnField($meta);

        return $meta;
    }


    /**
     * Create slide-out panel for new CDN creation
     *
     * @param array $meta
     * @return array
     * @since 101.0.0
     */
    protected function createNewCdnModal(array $meta)
    {
        return $this->arrayManager->set(
            'create_cdn_modal',
            $meta,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'isTemplate' => false,
                            'componentType' => 'modal',
                            'options' => [
                                'title' => __('New CDN'),
                            ],
                            'imports' => [
                                'state' => '!index=create_cdn:responseStatus'
                            ],
                        ],
                    ],
                ],
                'children' => [
                    'create_category' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => '',
                                    'componentType' => 'container',
                                    'component' => 'Magento_Ui/js/form/components/insert-form',
                                    'dataScope' => '',
                                    'update_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                    'render_url' => $this->urlBuilder->getUrl(
                                        'mui/index/render_handle',
                                        [
                                            'handle' => 'catalog_category_create',
                                            'store' => $this->locator->getStore()->getId(),
                                            'buttons' => 1
                                        ]
                                    ),
                                    'autoRender' => false,
                                    'ns' => 'new_category_form',
                                    'externalProvider' => 'new_category_form.new_category_form_data_source',
                                    'toolbarContainer' => '${ $.parentName }',
                                    '__disableTmpl' => ['toolbarContainer' => false],
                                    'formSubmitType' => 'ajax',
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * Customize Categories field
     *
     * @param array $meta
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function customizeCdnField(array $meta)
    {
        $fieldCode = 'cdn_ids';
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');
        $fieldIsDisabled = $this->locator->getProduct()->isLockedAttribute($fieldCode);

        if (!$elementPath) {
            return $meta;
        }

        $value = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => false,
                        'required' => false,
                        'dataScope' => '',
                        'breakLine' => false,
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'component' => 'Magento_Ui/js/form/components/group',
                        'disabled' => $this->locator->getProduct()->isLockedAttribute($fieldCode),
                    ],
                ],
            ],
            'children' => [
                $fieldCode => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'component' => 'Magento_Catalog/js/components/new-category',
                                'filterOptions' => true,
                                'chipsEnabled' => true,
                                'disableLabel' => true,
                                'levelsVisibility' => '1',
                                'disabled' => $fieldIsDisabled,
                                'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                'options' => $this->getCategoriesTree(),
                                'listens' => [
                                    'index=create_category:responseData' => 'setParsed',
                                    'newOption' => 'toggleOptionSelected'
                                ],
                                'config' => [
                                    'dataScope' => $fieldCode,
                                    'sortOrder' => 10,
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
        if ($this->isAllowed()) {
            $value['children']['create_cdn_button'] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'title' => __('New Cdn'),
                            'formElement' => 'container',
                            'additionalClasses' => 'admin__field-small',
                            'componentType' => 'container',
                            'disabled' => $fieldIsDisabled,
                            'component' => 'Magento_Ui/js/form/components/button',
                            'template' => 'ui/form/components/button/container',
                            'actions' => [
                                [
                                    'targetName' => 'product_form.product_form.create_cdn_modal',
                                    'actionName' => 'toggleModal',
                                ],
                                [
                                    'targetName' =>
                                        'product_form.product_form.create_cdn_modal.create_cdn',
                                    'actionName' => 'render'
                                ],
                                [
                                    'targetName' =>
                                        'product_form.product_form.create_cdn_modal.create_cdn',
                                    'actionName' => 'resetForm'
                                ]
                            ],
                            'additionalForGroup' => true,
                            'provider' => false,
                            'source' => 'product_details',
                            'displayArea' => 'insideGroup',
                            'sortOrder' => 20,
                            'dataScope'  => $fieldCode,
                        ],
                    ],
                ]
            ];
        }
        $meta = $this->arrayManager->merge($containerPath, $meta, $value);

        return $meta;
    }