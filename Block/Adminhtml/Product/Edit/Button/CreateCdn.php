<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SolidStateNetworks\ddpmodule\Block\Adminhtml\Product\Edit\Button;

/**
 * Button "Create Category" in "New Category" slide-out panel of a product page
 */
class CreateCdn extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Create Cdn'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 10
        ];
    }
}