<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Model\ResourceModel\Cotacoes;

use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $dataPersistor;
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Igorludgero\Correios\Model\Cotacoes $cotacoes,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $cotacoes->getCollection();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $cotacao) {
            $this->loadedData[$cotacao->getId()] = $cotacao->getData();
        }

        $data = $this->dataPersistor->get('correios_cotacoes');
        if (!empty($data)) {
            $cotacao = $this->collection->getNewEmptyItem();
            $cotacao->setData($data);
            $this->loadedData[$cotacao->getId()] = $cotacao->getData();
            $this->dataPersistor->clear('correios_cotacoes');
        }

        return $this->loadedData;
    }
}
