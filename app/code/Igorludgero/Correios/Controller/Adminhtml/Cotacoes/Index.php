<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 10/09/16
 * Time: 14:36
 */

namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();

        //Set the menu which will be active for this page
        $resultPage->setActiveMenu('Igorludgero_Correios::correios_menuoption1');

        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend(__('Correios Shipping Tracks'));

        return $resultPage;
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }
}