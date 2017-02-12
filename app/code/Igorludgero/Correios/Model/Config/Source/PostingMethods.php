<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */


namespace Igorludgero\Correios\Model\Config\Source;

use Magento\Framework\App\ObjectManager;

class PostingMethods implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {

        return array(
            array('value'=>40010, 'label'=>__('Sedex Sem Contrato (40010)')),
            array('value'=>40096, 'label'=>__('Sedex Com Contrato (40096)')),
            array('value'=>40436, 'label'=>__('Sedex Com Contrato (40436)')),
            array('value'=>40444, 'label'=>__('Sedex Com Contrato (40444)')),
            array('value'=>81019, 'label'=>__('E-Sedex Com Contrato (81019)')),
            array('value'=>41106, 'label'=>__('PAC Sem Contrato (41106)')),
            array('value'=>41068, 'label'=>__('PAC Com Contrato (41068)')),
            array('value'=>40215, 'label'=>__('Sedex 10 (40215)')),
            array('value'=>40290, 'label'=>__('Sedex HOJE (40290)')),
            array('value'=>40045, 'label'=>__('Sedex a Cobrar (40045)')),
    );

    }
}