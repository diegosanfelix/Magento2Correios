<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Model\Carrier;

use Igorludgero\Correios\Model\Tracker\Request;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Correiosship extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    protected $_code = 'correiosship';
    protected $_scopeConfig;
    protected $_storeScope;
    protected $_session;
    protected $_helper;
    protected $_enabled;
    protected $_destinationPostCode;
    protected $_weight;
    protected $_url;
    protected $_login;
    protected $_password;
    protected $_defHeight;
    protected $_defWidth;
    protected $_defDepth;
    protected $_weightType;
    protected $_postingMethods;
    protected $_deleteCodes;
    protected $_ownerHands;
    protected $_receivedWarning;
    protected $_declaredValue;
    protected $_maxWeight;
    protected $_packageValue;
    protected $_cubic;
    protected $_origPostcode;
    protected $_freeShipping;
    protected $_freeMethod;
    protected $_freeShippingMessage;
    protected $_statusFactory;
    protected $_handlingFee;
    protected $_functionMode;

    //Shipping Result
    protected $_result;
    protected $_resultError;
    protected $_tracking;

    //Cotacoes Model
    protected $_cotacoes;

    public function __construct(
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $statusFactory,
        \Magento\Shipping\Model\Tracking\Result\Error $resultError,
        \Magento\Shipping\Model\Tracking\Result\Status $resultStatus,
        \Magento\Shipping\Model\Tracking\Result $result,
        \Magento\Checkout\Model\Session $session,
        \Igorludgero\Correios\Helper\Data $helperData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Igorludgero\Correios\Model\Cotacoes $_cotacoes,
        array $data = []
    ) {
        $this->_statusFactory = $statusFactory;
        $this->_helper = $helperData;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_session = $session;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->_result = $result;
        $this->_resultError = $resultError;
        $this->_tracking = $resultStatus;
        $this->_cotacoes = $_cotacoes;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    public function getAllowedMethods()
    {
        return ['correiosship' => "correiosship"];
    }

    public function collectRates(RateRequest $request)
    {
        $result = $this->_rateResultFactory->create();

        //Init Correios Shipping Values
        $this->_enabled = $this->_scopeConfig->getValue("carriers/igorludgero_correios/active",$this->_storeScope);
        if($this->_scopeConfig->getValue('carriers/igorludgero_correios/webservice_url',$this->_storeScope) != "")
            $this->_url = $this->_scopeConfig->getValue('carriers/igorludgero_correios/webservice_url',$this->_storeScope);
        else
            $this->_url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?StrRetorno=xml";
        $this->_login = $this->_scopeConfig->getValue('carriers/igorludgero_correios/login',$this->_storeScope);
        $this->_password = $this->_scopeConfig->getValue('carriers/igorludgero_correios/password',$this->_storeScope);

        if(intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_height',$this->_storeScope))>0)
            $this->_defHeight = intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_height',$this->_storeScope));
        else
            $this->_defHeight = 2;
        if(intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_width',$this->_storeScope))>0)
            $this->_defWidth = intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_width',$this->_storeScope));
        else
            $this->_defWidth = 16;
        if(intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_depth',$this->_storeScope))>0)
            $this->_defDepth = intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_depth',$this->_storeScope));
        else
            $this->_defDepth = 11;
        $this->_weightType = $this->_scopeConfig->getValue('carriers/igorludgero_correios/weight_type',$this->_storeScope);
        $postingMethods = explode(",",$this->_scopeConfig->getValue('carriers/igorludgero_correios/posting_methods',$this->_storeScope));
        $this->_postingMethods = $this->_helper->getPostMethodCodes($postingMethods);
        $this->_deleteCodes = explode(",","008,-10,16");
        if($this->_scopeConfig->getValue('carriers/igorludgero_correios/owner_hands',$this->_storeScope)==0){
            $this->_ownerHands = 'N';
        }
        else{
            $this->_ownerHands = 'S';
        }
        if($this->_scopeConfig->getValue('carriers/igorludgero_correios/received_warning',$this->_storeScope)==0){
            $this->_receivedWarning = 'N';
        }
        else{
            $this->_receivedWarning = 'S';
        }
        $this->_freeShippingMessage = $this->_scopeConfig->getValue("carriers/igorludgero_correios/freeshipping_message",$this->_storeScope);
        $this->_origPostcode = $this->_scopeConfig->getValue("shipping/origin/postcode",$this->_storeScope);
        $this->_declaredValue = $this->_scopeConfig->getValue('carriers/igorludgero_correios/declared_value',$this->_storeScope);
        $this->_maxWeight = (doubleval($this->_scopeConfig->getValue("carriers/igorludgero_correios/max_weight",$this->_storeScope)));
        $this->_freeMethod = $this->_scopeConfig->getValue("carriers/igorludgero_correios/posting_freemethod",$this->_storeScope);
        $this->_functionMode = $this->_scopeConfig->getValue("carriers/igorludgero_correios/function_mode",$this->_storeScope);
        $this->_destinationPostCode = $this->_helper->formatZip($request->getDestPostcode());
        if(is_int($request->getPackageWeight()))
            $this->_weight = $request->getPackageWeight();
        else
            $this->_weight = ceil($this->_helper->fixWeight($request->getPackageWeight()));
        $this->_packageValue = $request->getBaseCurrency()->convert($request->getPackageValue(), $request->getPackageCurrency());

        $this->_handlingFee = 0;
        if($this->_scopeConfig->getValue("carriers/igorludgero_correios/handling_fee",$this->_storeScope)!=""){
            if(is_numeric($this->_scopeConfig->getValue("carriers/igorludgero_correios/handling_fee",$this->_storeScope))){
                $this->_handlingFee = $this->_scopeConfig->getValue("carriers/igorludgero_correios/handling_fee",$this->_storeScope);
            }
        }

        if($this->_enabled==0){
            $this->_helper->logMessage("Module disabled");
            return false;
        }
        if(!$this->_helper->checkCountry($request,$this->_scopeConfig->getValue("shipping/origin/country_id",$this->_storeScope))){
            $this->_helper->logMessage("Invalid Countries");
            return false;
        }
        if(!$this->_helper->checkWeightRange($request)){
            $this->_helper->logMessage("Invalid Weight in checkWeightRange");
            return false;
        }
        if($this->_helper->getCubicWeight($this->_session->getQuote()) == 0){
            $this->_helper->logMessage("Invalid Weight in getCubicWeight");
            return false;
        }
        else{
            $this->_cubic = $this->_helper->getCubicWeight($this->_session->getQuote());
        }
        $arrayConsult = $this->generateConsultUrl();
        $correiosMethods = array();
        if($this->_functionMode==2)
            $correiosMethods = $this->_helper->getOnlineShippingQuotes($arrayConsult);

        //To test Offline enable this next line
        //$correiosMethods = array();

        if($request->getFreeShipping() === true){
            $this->_freeShipping = true;
        } else {
            $this->_freeShipping = false;
        }

        //If not available online get offline
        if(count($this->_postingMethods)!=count($correiosMethods)){
            $deliveryMessage = $this->_scopeConfig->getValue("carriers/igorludgero_correios/deliverydays_message",$this->_storeScope);
            if($deliveryMessage==""){
                $deliveryMessage = "%s - Em média %d dia(s)";
            }
            $showDeliveryDays = $this->_scopeConfig->getValue("carriers/igorludgero_correios/show_deliverydays",$this->_storeScope);
            $addDeliveryDays = intval($this->_scopeConfig->getValue("carriers/igorludgero_correios/add_deliverydays",$this->_storeScope));

            foreach ($this->_postingMethods as $method){
                $haveToGetOffline = true;
                foreach ($correiosMethods as $onlineMethods){
                    if($onlineMethods["servico"] == $method && ($onlineMethods["valor"]>0 && $onlineMethods["prazo"]>0)){
                        $haveToGetOffline = false;
                    }
                }
                if($haveToGetOffline){
                    if($this->_cubic<=10){
                        $correiosWeight = max($this->_weight,$this->_cubic);
                    }
                    else{
                        $correiosWeight = $this->_cubic;
                    }
                    if(is_int($correiosWeight)==false) {
                        if ($correiosWeight > 0.5) {
                            $correiosWeight = round($correiosWeight);
                        } else {
                            $correiosWeight = 0.3;
                        }
                    }
                    $invalidPostcodeChars = array("-",".");
                    $postcodeNumber = str_replace($invalidPostcodeChars,"",$this->_destinationPostCode);
                    $cotacaoOffline = $this->_cotacoes->getCollection()->addFieldToFilter('cep_inicio', ["lteq" => $postcodeNumber])->addFieldToFilter('cep_fim', ["gteq" => $postcodeNumber])->addFilter("servico",$method)->addFilter("peso",$correiosWeight)->getFirstItem();
                    if($cotacaoOffline){
                        if($cotacaoOffline->getData()){
                            if($cotacaoOffline->getValor()>0){
                                $data = array();
                                if($showDeliveryDays==0)
                                    $data['servico'] = $this->_helper->getMethodName($cotacaoOffline->getServico());
                                else
                                    $data['servico'] = sprintf($deliveryMessage,$this->_helper->getMethodName($cotacaoOffline->getServico()),intval($cotacaoOffline->getPrazo()+$addDeliveryDays));
                                $data['valor'] = str_replace(",",".",$cotacaoOffline->getValor()) + $this->_handlingFee;
                                $data['prazo'] = $cotacaoOffline->getPrazo() + $addDeliveryDays;
                                $data['servico_codigo'] = $cotacaoOffline->getServico();
                                $correiosMethods[] = $data;
                            }
                        }
                    }
                }
            }
        }
        foreach ($correiosMethods as $correiosMethod){
            if($correiosMethod["valor"]>0){
                $method = $this->_rateMethodFactory->create();
                $method->setCarrier('correiosship');
                $method->setCarrierTitle($this->_scopeConfig->getValue('carriers/igorludgero_correios/name', $this->_storeScope));
                $method->setMethod('correiosship_' . $correiosMethod['servico_codigo']);
                if ($this->_freeShipping == true && $correiosMethod["servico_codigo"] == $this->_freeMethod) {
                    if ($this->_freeShippingMessage != "")
                        $method->setMethodTitle("[" . $this->_freeShippingMessage . "] " . $correiosMethod['servico']);
                    else
                        $method->setMethodTitle($correiosMethod['servico']);
                    $amount = 0;
                } else {
                    $amount = $correiosMethod['valor'];
                    $method->setMethodTitle($correiosMethod['servico']);
                }
                $method->setPrice($amount);
                $method->setCost($amount);
                $result->append($method);
            }
        }

        return $result;
    }

    public function isTrackingAvailable(){
        return true;
    }

    protected function _getTracking($code) {

        $body = file_get_contents("http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=002&P_COD_LIS=".$code);

        if (!preg_match('#<table ([^>]+)>(.*?)</table>#is', $body, $matches)) {
            return false;
        }
        $table = $matches[2];

        if (!preg_match_all('/<tr>(.*)<\/tr>/i', $table, $columns, PREG_SET_ORDER)) {
            return false;
        }

        for ($i = 0; $i < 1; $i++) {
            $column = $columns[$i][1];

            $found = false;
            if (preg_match('/<td rowspan="?2"?/i', $column) && preg_match('/<td rowspan="?2"?>(.*)<\/td><td>(.*)<\/td><td><font color="[A-Z0-9]{6}">(.*)<\/font><\/td>/i', $column, $matches)) {
                $found = true;
            } elseif (preg_match('/<td rowspan="?1"?>(.*)<\/td><td>(.*)<\/td><td><font color="[A-Z0-9]{6}">(.*)<\/font><\/td>/i', $column, $matches)) {
                $found = true;
            }

            if ($found) {
                $datetime = explode(' ', $matches[1]);
                $status = htmlentities($matches[3]);
                $deliveryTime = $datetime[1] . ':00';
                $date = $datetime[0];
                $dateArray = explode("/",$date);
                $newDate = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
                $track = array(
                    'deliverydate' => $newDate,
                    'deliverytime' => $deliveryTime,
                    'deliverylocation' => '',
                    'status' => htmlentities($status),
                    'activity' => htmlentities($status)
                );
                return $track;
            }
        }
    }

    public function getTrackingInfo($number){
        $aux = $this->_getTracking($number);
        $tracking = $this->_statusFactory->create();
        $tracking->setCarrier($this->_code);
        $tracking->setCarrierTitle("Correios");
        $tracking->setTracking($number);
        if($aux!=false)
            $tracking->addData($aux);
        return $tracking;
    }

    protected function generateConsultUrl(){
        if(count($this->_postingMethods)>0){
            $arrayConsult = array();
            foreach ($this->_postingMethods as $_method){
                $correiosWeight = 0;
                if($this->_cubic<=10){
                    $correiosWeight = max($this->_weight,$this->_cubic);
                }
                else{
                    $correiosWeight = $this->_cubic;
                }

                if($this->_login!="")
                    $url_d = $this->_url."&nCdEmpresa=".$this->_login."&sDsSenha=".$this->_password."&nCdFormato=1&nCdServico=".$_method."&nVlComprimento=".$this->_defWidth."&nVlAltura=".$this->_defHeight."&nVlLargura=".$this->_defWidth."&sCepOrigem=".$this->_origPostcode."&sCdMaoPropria=".$this->_ownerHands."&sCdAvisoRecebimento=".$this->_receivedWarning."&nVlValorDeclarado=".$this->_declaredValue."&nVlPeso=".$correiosWeight."&sCepDestino=".$this->_destinationPostCode;
                else
                    $url_d = $this->_url."&nCdFormato=1&nCdServico=".$_method."&nVlComprimento=".$this->_defWidth."&nVlAltura=".$this->_defHeight."&nVlLargura=".$this->_defWidth."&sCepOrigem=".$this->_origPostcode."&sCdMaoPropria=".$this->_ownerHands."&sCdAvisoRecebimento=".$this->_receivedWarning."&nVlValorDeclarado=".$this->_declaredValue."&nVlPeso=".$correiosWeight."&sCepDestino=".$this->_destinationPostCode;
                $arrayConsult[] = $url_d;
                $this->_helper->logMessage($url_d);
            }
            return $arrayConsult;
        }
    }

}