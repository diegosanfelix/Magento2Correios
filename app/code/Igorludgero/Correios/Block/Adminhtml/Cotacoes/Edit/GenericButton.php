<?php

namespace Igorludgero\Correios\Block\Adminhtml\Cotacoes\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;
    protected $cotacoes;

    public function __construct(
        Context $context,
        \Igorludgero\Correios\Model\Cotacoes $cotacoes
    ) {
        $this->context = $context;
        $this->cotacoes = $cotacoes;
    }

    public function getPageId()
    {
        try {
            return $this->cotacoes->load(
                $this->context->getRequest()->getParam('cotacoes_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
