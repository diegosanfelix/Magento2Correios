<?php

/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Block\Tracking;

use Magento\Framework\View\Element\Template;

class Order extends Template
{

    public function getTrackingTable()
    {
        if ($this->getRequest()->getParam('code')) {
            $code = $this->getRequest()->getParam('code');
            $__wsdl = "http://webservice.correios.com.br/service/rastro/Rastro.wsdl";
            $_buscaEventos = array(
                'usuario' => 'ECT',
                'senha' => 'SRO',
                'tipo' => 'L',
                'resultado' => 'T',
                'lingua' => '101'
            );
            $_buscaEventos['objetos'] = $code;

            $outputHtml = '';

            $outputHtml .= '<table style="width:100%">
        <tr>
          <th>' . __("Date & Time") . '</th>
          <th>' . __("Agency") . '</th>
          <th>' . __("Location") . '</th>
          <th>' . __("Description") . '</th>
        </tr>';

            $client = new \SoapClient($__wsdl);
            $r = $client->buscaEventos($_buscaEventos);
            $objeto = $r->return->objeto;
            if (!isset($objeto->erro)) {
                if (count($objeto->evento) > 1) {
                    foreach ($objeto->evento as $e) {
                        $outputHtml .= '<tr>';
                        $outputHtml .= '<th>' . $e->data . ' ' . $e->hora . '</th>';
                        $outputHtml .= '<th>' . $e->local . '</th>';
                        $outputHtml .= '<th>' . $e->cidade . ' ' . $e->uf . '</th>';
                        $outputHtml .= '<th>' . $e->descricao . '</th>';
                        $outputHtml .= '</tr>';
                    }
                }
            }

            $outputHtml .= '</table>';
            $outputHtml .= '<form method="POST" target="_blank" action="http://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm" class="shipment-details-service__correios">
                            <input type="hidden" name="Objetos" value="' . $this->getRequest()->getParam('code') . '">
                            <input class="shipment-details-service__correios-action" type="submit" value="' . __('See on Correios Website') . '">
                        </form>';
            return $outputHtml;
        } else {
            return __('No Shipping Code available');
        }
    }

}