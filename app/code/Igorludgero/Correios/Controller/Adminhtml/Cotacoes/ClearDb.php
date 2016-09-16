<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 12/09/16
 * Time: 18:24
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

use Magento\Backend\App\Action\Context;
use Igorludgero\Correios\Model\Cotacoes;
use Magento\Framework\Controller\ResultFactory;

class ClearDb extends \Magento\Backend\App\Action
{

    public function __construct(Context $context, Cotacoes $cotacao)
    {
        $this->cotacao = $cotacao;
        parent::__construct($context);
    }

    public function execute()
    {
        $error = 0;

        $collection = $this->cotacao->getCollection();

        foreach ($collection as $cotacao){
            $cotacaoObj = $this->cotacao->load($cotacao->getId());
            if($cotacaoObj->delete()==false)
                $error++;
        }

        if($error==0)
            $this->messageManager->addSuccess(__('The database of postcode tracks was succesfully cleared.'));
        else
            $this->messageManager->addError(__("An error occurred when tried to clear the postcode database."));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }

}