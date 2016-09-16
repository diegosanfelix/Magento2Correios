<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 08/09/16
 * Time: 17:11
 */

namespace Igorludgero\Correios\Model\ResourceModel;

class Cotacoes extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('igorludgero_cotacoes', 'id');
    }

}