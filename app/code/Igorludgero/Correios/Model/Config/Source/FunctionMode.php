<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Model\Config\Source;

use Magento\Framework\App\ObjectManager;

class FunctionMode implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {

        return array(
            array('value'=>1, 'label'=>__('Only Offline')),
            array('value'=>2, 'label'=>__('Hybrid')),
        );

    }
}