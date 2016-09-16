<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 12/09/16
 * Time: 18:45
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

use Magento\Backend\App\Action\Context;
use Igorludgero\Correios\Model\Cotacoes;
use Magento\Framework\Controller\ResultFactory;

class PopulateTracks extends \Magento\Backend\App\Action
{

    public function __construct(Context $context, Cotacoes $cotacao)
    {
        $this->cotacao = $cotacao;
        parent::__construct($context);
    }

    public function execute()
    {
        if($this->cotacao->getCollection()->count()>0){
            $this->messageManager->addError(__("You have to clear the postcode tracks db first!"));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        }
        else{
            if($this->cotacao->populate()){
                $this->messageManager->addSuccess(__("Postcode tracks database populated!"));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                return $resultRedirect->setPath('*/*/');
            }
            else{
                $this->messageManager->addError(__("An error occurred when the populate action was executed. Check the logs to see the cause of error."));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                return $resultRedirect->setPath('*/*/');
            }
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }

}