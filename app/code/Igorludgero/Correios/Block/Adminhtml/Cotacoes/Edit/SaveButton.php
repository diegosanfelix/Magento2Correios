<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 13/09/16
 * Time: 16:49
 */

namespace Igorludgero\Correios\Block\Adminhtml\Cotacoes\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Igorludgero\Correios\Block\Adminhtml\Cotacoes\Edit\GenericButton;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save Track'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}