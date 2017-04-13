<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Model\ResourceModel;

class Cotacoes extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('igorludgero_cotacoes', 'id');
    }

}