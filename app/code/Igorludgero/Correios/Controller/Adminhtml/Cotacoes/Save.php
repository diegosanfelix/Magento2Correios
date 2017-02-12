<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */
namespace Igorludgero\Correios\Controller\Adminhtml\Cotacoes;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Igorludgero\Correios\Controller\Adminhtml\Cotacoes\PostDataProcessor;

class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Igorludgero_Correios::save';
    protected $dataProcessor;
    protected $dataPersistor;
    protected $helper;

    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \Igorludgero\Correios\Helper\Data $data
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->helper = $data;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            if (empty($data['cotacoes_id'])) {
                $data['cotacoes_id'] = null;
            }

            $model = $this->_objectManager->create('Igorludgero\Correios\Model\Cotacoes');

            $id = $this->getRequest()->getParam('cotacoes_id');
            if ($id) {
                $model->load($id);
            }

            $model->setCepInicio($this->helper->formatZip($data["cep_inicio"]));
            $model->setCepFim($this->helper->formatZip($data["cep_fim"]));
            $model->setValor($this->helper->formatPrice($data["valor"]));
            $model->setPeso($data["peso"]);
            $model->setPrazo($data["prazo"]);
            $model->setUltimoUpdate($data["ultimo_update"]);
            $model->setServico($data["servico"]);

            if (!$this->dataProcessor->validate($model)) {
                return $resultRedirect->setPath('*/*/edit', ['cotacoes_id' => $model->getId(), '_current' => true]);
            }

            try {
                if($model->save()) {
                    $this->messageManager->addSuccess(__('You saved the new postcode track.'));
                    $this->dataPersistor->clear('correios_cotacoes');
                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['cotacoes_id' => $model->getId(), '_current' => true]);
                    }
                }
                else{
                    $this->messageManager->addError(__('Was not possible to save this new postcode track.'));
                    $this->dataPersistor->clear('correios_cotacoes');
                    return $resultRedirect->setPath('*/*/edit', ['cotacoes_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the new postcode track.'));
            }

            $this->dataPersistor->set('correios_cotacoes', $data);
            return $resultRedirect->setPath('*/*/edit', ['cotacoes_id' => $this->getRequest()->getParam('cotacoes_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Correios::correios_menuoption1');
    }

}
