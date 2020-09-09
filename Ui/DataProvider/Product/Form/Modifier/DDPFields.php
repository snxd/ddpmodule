<?php
namespace SolidStateNetworks\ddpmodule\Ui\DataProvider\Product\Form\Modifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;
class DDPFields extends AbstractModifier
{
    private $locator;
    public function __construct(
        LocatorInterface $locator
    ) {
        $this->locator = $locator;
    }

    public function modifyData(array $data)
    {
        $product   = $this->locator->getProduct();
        $productId = $product->getId();
        
        $data = array_replace_recursive(
            $data,
            [
                $productId => [
                    'product' => [
                        'enabled2' => 'your_value'
                    ]
                ]
            ]
        );

        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'custom_fieldset' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Solid Fieldset'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.product.solidddp',
                                'collapsible' => true,
                                'sortOrder' => 5,
                            ],
                        ],
                    ],
                    'children' => ['custom_field' => $this->getCustomField(),
                                   'custom_f2' => $this->getCField()],
                ]
            ]
        );
        return $meta;
    }

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

    public function getCField()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Custom Field 2'),
                        'componentType' => Field::NAME,
                        'formElement' => \Magento\Ui\Component\Form\Element\Input::NAME,
                        'dataScope' => 'enabled2',
                        'dataType' => Text::NAME,
                        'sortOrder' => 10
                    ],
                ],
            ],
        ];
    }
}