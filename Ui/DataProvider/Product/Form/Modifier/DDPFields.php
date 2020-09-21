<?php
/**
 * Copyright © Solid State Networks, Inc. All rights reserved.
 *
 * @category Class
 * @package  DDPModule
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  MIT https://mit-license.org/
 * @link     http://solidstatenetworks.com
 */

namespace SolidStateNetworks\ddpmodule\Ui\DataProvider\Product\Form\Modifier;

use Magento\Downloadable\Model\Product\Type;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;
use SolidStateNetworks\ddpmodule\Model\DDPItemFactory;

/**
 * DDPModule Admin UI field definitions
 *
 * @author   Jason Lines <jlines@solidstatenetworks.com>
 * @license  MIT https://mit-license.org/
 * @link     http://solidstatenetworks.com
 * @api
 * @method   array modifyData(array $data)
 * @method   Link setProductId(int $value)
 * @since    0.0.2
 */
class DDPFields extends AbstractModifier
{
    private $_locator;

    /**
     * Class Constructor
     *
     * @param Magento\Catalog\Model\Locator\LocatorInterface    $locator      The locator
     * @param SolidStateNetworks\ddpmodule\Model\DDPItemFactory $ditemFactory DDPItem loader
     */
    public function __construct(
        LocatorInterface $locator,
        DDPItemFactory $ditemFactory
    ) {
        $this->_locator = $locator;
        $this->_ditemFactory = $ditemFactory;
    }

    /**
     * Modify the magento data structure for DDPModule fields
     *
     * @param array $data incoming data
     *
     * @return array
     */
    public function modifyData(array $data)
    {
        //THIS IS WHERE WE WILL LOAD DATA FROM THE DATABASE AND PLACE IT ON THE FORM

        if ($this->_locator->getProduct()->getTypeId() !== Type::TYPE_DOWNLOADABLE) {
            return $data;
        }

        $product   = $this->_locator->getProduct();
        $productId = $product->getId();

        $ddpi = $this->_ditemFactory->create();
        $ddpi->load($productId, "product_id");

        $data = array_replace_recursive(
            $data,
            [
                $productId => [
                    'solidddp' => [
                        'enabled' => $ddpi->getData('enabled'),
                        'dlm_id_win' => $ddpi->getData('dlm_id_win'),
                        'dlm_id_macos' => $ddpi->getData('dlm_id_macos'),
                        'acl' => $ddpi->getData('acl'),
                        'ttl' => $ddpi->getData('ttl'),
                        'secret' => $ddpi->getData('secret'),
                    ]
                ]
            ]
        );

        return $data;
    }

    /**
     * Modify the magento UI structure for DDPModule fields
     *
     * @param array $meta incoming metadata
     *
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        if ($this->_locator->getProduct()->getTypeId() !== Type::TYPE_DOWNLOADABLE) {
            return $meta;
        }

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
                                'sortOrder' => 21,
                            ],
                        ],
                    ],
                    'children' => $this->_getCustomFields()
                ]
            ]
        );
        return $meta;
    }

    /**
     * Custom metadata for DDPModule fields
     *
     * @return array
     */
    private function _getCustomFields()
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
            'dlm_id_win' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Solid Windows DLM ID'),
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                            'dataScope' => 'dlm_id_win',
                            'dataType' => Text::NAME,
                            'sortOrder' => 20
                        ],
                    ],
                ],
            ],
            'dlm_id_macos' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Solid MacOS DLM ID'),
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                            'dataScope' => 'dlm_id_macos',
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
