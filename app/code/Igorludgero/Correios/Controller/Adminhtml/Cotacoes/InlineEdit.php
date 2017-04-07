<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

use Igorludgero\Correios\Model\ResourceModel\CotacoesFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class InlineEdit extends \Magento\Backend\App\Action
{

    protected $resultPageFactory = false;
    protected $jsonFactory;
    protected $helper;
    protected $cotacoesFactory;
    protected $timeZoneInterface;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Igorludgero\Correios\Helper\Data $data,
        CotacoesFactory $cotacoesFactory,
        TimezoneInterface $timezone
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->helper = $data;
        $this->cotacoesFactory = $cotacoesFactory;
        $this->timeZoneInterface = $timezone;
    }

    public function execute()
    {

        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        $cotacaoModel = $this->cotacoesFactory->create();

        foreach ($postItems as $item){
            $objCotacao = $cotacaoModel->load($item["id"]);
            if($objCotacao->getData()){
                $objCotacao->setServico($item["servico"]);
                $objCotacao->setPrazo($item["prazo"]);
                $objCotacao->setPeso($item["peso"]);
                $objCotacao->setValor($item["valor"]);
                $objCotacao->setCepInicio($item["cep_inicio"]);
                $objCotacao->setCepFim($item["cep_fim"]);
                $currentTime = strtotime($this->timeZoneInterface
                    ->date(new \DateTime())
                    ->format('m/d/y H:i:s'));
                $objCotacao->setUltimoUpdate($currentTime);
                if($objCotacao->save()==false){
                    $messages[] = __("The Postcode Track %s wasn't updated. Check the logs.",$objCotacao->getId());
                    $error = true;
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }


}