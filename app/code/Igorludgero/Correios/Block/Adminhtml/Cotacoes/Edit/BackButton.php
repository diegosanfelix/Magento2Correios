<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Block\Adminhtml\Cotacoes\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class BackButton implements ButtonProviderInterface
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