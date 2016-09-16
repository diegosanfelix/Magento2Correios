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

class BackButton extends GenericButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }
}