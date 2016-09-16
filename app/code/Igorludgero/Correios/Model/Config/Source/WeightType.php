<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 04/09/16
 * Time: 15:00
 */

namespace Igorludgero\Correios\Model\Config\Source;

use Magento\Framework\App\ObjectManager;

class WeightType implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'gr', 'label'=>__('Gramas')),
            array('value'=>'kg', 'label'=>__('Kilos')),
        );
    }

}
