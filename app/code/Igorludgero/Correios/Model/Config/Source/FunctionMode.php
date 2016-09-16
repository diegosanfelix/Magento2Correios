<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 15/09/16
 * Time: 18:15
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