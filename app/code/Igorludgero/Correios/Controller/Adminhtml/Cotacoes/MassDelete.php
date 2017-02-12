<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Igorludgero\Correios\Model\Cotacoes;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $cotacao;

    public function __construct(Context $context, Filter $filter, Cotacoes $cotacao)
    {
        $this->filter = $filter;
        $this->cotacao = $cotacao;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->cotacao->getCollection());

        $sucess = 0;
        $error = 0;

        foreach ($collection as $cotacao) {
            $data = (array)$cotacao->getData();
            $id = $data["id"];
            $cotacaoObj = $this->cotacao->load($id);
            if($cotacaoObj->delete()){
                $sucess++;
            }
            else{
                $error++;
            }
        }
        if($error==0){
            if($sucess>1){
                $this->messageManager->addSuccess(__('A total of %1 postcode tracks have been deleted.', $sucess));
            }
            else{
                $this->messageManager->addSuccess(__('The postcode track has been deleted.'));
            }
        }
        else{
            $this->messageManager->addError(__('Impossible to delete %1 postcode tracks.', $error));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }

}
