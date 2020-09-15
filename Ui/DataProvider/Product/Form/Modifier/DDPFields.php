<?php
namespace SolidStateNetworks\ddpmodule\Ui\DataProvider\Product\Form\Modifier;
use Magento\Downloadable\Model\Product\Type;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;
use SolidStateNetworks\ddpmodule\Model\DDPItemFactory;

class DDPFields extends AbstractModifier
{
    private $locator;
    public function __construct(
        LocatorInterface $locator,
        DDPItemFactory $ditemFactory
    ) {
        $this->locator = $locator;
        $this->_ditemFactory = $ditemFactory;
    }

    public function modifyData(array $data)
    {
        //THIS IS WHERE WE WILL LOAD DATA FROM THE DATABASE AND PLACE IT ON THE FORM

        /*if($this->locator->getProduct()->getTypeId() !== Type::TYPE_DOWNLOADABLE) {
            return $data;
        }*/

        error_log("Prod Type " . $this->locator->getProduct()->getTypeId());

        $product   = $this->locator->getProduct();
        $productId = $product->getId();

        $ddpi = $this->_ditemFactory->create();
        $ddpi->load($productId, "product_id");

        $data = array_replace_recursive(
            $data,
            [
                $productId => [
                    'solidddp' => [
                        'enabled' => $ddpi->getData('enabled'),
                        'dlm_id' => $ddpi->getData('dlm_id'),
                        'acl' => $ddpi->getData('acl'),
                        'ttl' => $ddpi->getData('ttl'),
                        'secret' => $ddpi->getData('secret'),
                    ]
                ]
            ]
        );

        return $data;
    }

    public function modifyMeta(array $meta)
    {
        /*if($this->locator->getProduct()->getTypeId() !== Type::TYPE_DOWNLOADABLE) {
            return $meta;
        }*/

        $meta = array_replace_recursive(
            $meta,
            [
                'custom_fieldset' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Solid State Networks DDP'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.solidddp',
                                'collapsible' => true,
                                'sortOrder' => 5,
                            ],
                        ],
                    ],
                    'children' => $this->getCustomFields()
                ]
            ]
        );
        return $meta;
    }


    /*public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'custom_fieldset' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Solid State Networks DDP'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.solidddp',
                                'collapsible' => true,
                                'sortOrder' => 5,
                            ],
                        ],
                    ],
                    'children' => ['enabled' => $this->getCustomField()],
                ]
            ]
        );
        return $meta;
    }*/

    public function getCustomField()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Custom Field'),
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'dataScope' => 'enabled',
                        'dataType' => Text::NAME,
                        'sortOrder' => 10,
                        'options' => [
                            ['value' => '0', 'label' => __('No')],
                            ['value' => '1', 'label' => __('Yes')]
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getCustomFields()
    {
        return [
            'enabled' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Enabled'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => 'enabled',
                            'dataType' => Text::NAME,
                            'sortOrder' => 10,
                            'options' => [
                                ['value' => '0', 'label' => __('No')],
                                ['value' => '1', 'label' => __('Yes')]
                            ],
                        ],
                    ],
                ],
            ],
            'dlm_id' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Solid DLM ID'),
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                            'dataScope' => 'dlm_id',
                            'dataType' => Text::NAME,
                            'sortOrder' => 20
                        ],
                    ],
                ],
            ],
            'acl' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('CDN ACL'),
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                            'dataScope' => 'acl',
                            'dataType' => Text::NAME,
                            'sortOrder' => 30
                        ],
                    ],
                ],
            ],
            'ttl' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('CDN Time to Live'),
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                            'dataScope' => 'ttl',
                            'dataType' => Text::NAME,
                            'sortOrder' => 40
                        ],
                    ],
                ],
            ],
            'secret' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('CDN Shared Secret'),
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                            'dataScope' => 'secret',
                            'dataType' => Text::NAME,
                            'sortOrder' => 50
                        ],
                    ],
                ],
            ],
        ];
    }
}