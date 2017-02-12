<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

use Magento\Backend\App\Action\Context;
use Igorludgero\Correios\Model\Cotacoes;
use Magento\Framework\Controller\ResultFactory;

class RemoveInvalid extends \Magento\Backend\App\Action
{
    protected $_cotacao;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Cotacoes $cotacao
    )
    {
        parent::__construct($context);
        $this->_cotacao = $cotacao;
    }

    public function execute()
    {
        $collection = $this->_cotacao->getCollection()->addFilter("valor",0)->addFilter("prazo",0);
        foreach ($collection as $cotacao){
            $cotacaoObj = $this->_cotacao->load($cotacao->getId());
            $cotacaoObj->delete();
        }
        $this->messageManager->addSuccess(__("Deleted %1 invalid postcode tracks.",$collection->count()));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }

}