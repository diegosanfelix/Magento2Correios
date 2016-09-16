<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 14/09/16
 * Time: 15:22
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

class PostDataProcessor
{
    protected $dateFilter;

    protected $validatorFactory;

    protected $messageManager;

    protected $helper;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\View\Model\Layout\Update\ValidatorFactory $validatorFactory,
        \Igorludgero\Correios\Helper\Data $helper
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
        $this->validatorFactory = $validatorFactory;
        $this->helper = $helper;
    }

    public function filter($data)
    {
        $filterRules = [];

        foreach (['custom_theme_from', 'custom_theme_to'] as $dateField) {
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->dateFilter;
            }
        }

        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }

    public function validate($model)
    {
        $errorNo = true;
        if(!$model->getServico()){
            $this->messageManager->addError(__("The field 'Service' can't be empty!"));
            $errorNo = false;
        }
        if(!$model->getCepInicio()){
            $this->messageManager->addError(__("The field 'First Postcode' can't be empty!"));
            $errorNo = false;
        }
        if(!$model->getCepFim()){
            $this->messageManager->addError(__("The field 'Last Postcode' can't be empty!"));
            $errorNo = false;
        }
        if(!$model->getValor()){
            $this->messageManager->addError(__("The field 'Price' can't be empty!"));
            $errorNo = false;
        }
        if(!$model->getPeso()){
            $this->messageManager->addError(__("The field 'Weight' can't be empty!"));
            $errorNo = false;
        }
        if(!$model->getPrazo()){
            $this->messageManager->addError(__("The field 'Delivery Days' can't be empty!"));
            $errorNo = false;
        }
        if(!$model->getUltimoUpdate()){
            $this->messageManager->addError(__("The field 'Last Update' can't be empty!"));
            $errorNo = false;
        }
        if($this->helper->canCreateOfflineTrack($model->getServico(),$model->getCepInicio(),$model->getCepFim())==false){
            $this->messageManager->addError(__("Your DB have a postcode track to this postcode track. You can't create more than one postcode track for an one service and same postcode tracks."));
            $errorNo = false;
        }
        return $errorNo;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }

}