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

class UpdateTracks extends \Magento\Backend\App\Action
{

    public function __construct(Context $context, Cotacoes $cotacao)
    {
        $this->cotacao = $cotacao;
        parent::__construct($context);
    }

    public function execute()
    {
        $arrayResult = $this->cotacao->updateTracks();
        if($arrayResult!=false) {
            $this->messageManager->addSuccess(__("%1 Successful postcode tracks updated and %2 with error", $arrayResult[0], $arrayResult[1]));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        }
        else {
            $this->messageManager->addSuccess(__("You don't need to update the database now, the database is updated.", $arrayResult[0], $arrayResult[1]));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        }
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }


}