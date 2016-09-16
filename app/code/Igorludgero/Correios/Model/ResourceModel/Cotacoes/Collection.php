<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 08/09/16
 * Time: 17:12
 */

namespace Igorludgero\Correios\Model\ResourceModel\Cotacoes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Igorludgero\Correios\Model\Cotacoes',
            'Igorludgero\Correios\Model\ResourceModel\Cotacoes'
        );
    }
}