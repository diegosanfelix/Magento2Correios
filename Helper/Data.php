<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_storeScope;
    protected $_scopeConfig;
    protected $_productRepository;
    protected $_obligatoryLogin = array(40096,40436,40444,81019,41068);
    protected $_cotacoesFactory;

    public function __construct(\Magento\Catalog\Model\ProductRepository $productRepository, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Igorludgero\Correios\Model\ResourceModel\CotacoesFactory $cotacoesFactory)
    {
        $this->_storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->_scopeConfig = $scopeConfig;
        $this->_productRepository = $productRepository;
        $this->_cotacoesFactory = $cotacoesFactory;
    }

    public function getMethodName($codigo){
        $codigo = (int)$codigo;
        if($codigo==40010 || $codigo == $this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex', $this->_storeScope)){
            return "Sedex";
        }
        else if($codigo==81019 || $codigo == $this->_scopeConfig->getValue('correios_postingmethods_config/settings/esedex', $this->_storeScope)){
            return "E-Sedex";
        }
        else if($codigo==41106 || $codigo==41068 || $this->_scopeConfig->getValue('correios_postingmethods_config/settings/pac', $this->_storeScope)){
            return "PAC ";
        }
        else if($codigo==40215 || $this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex10', $this->_storeScope)){
            return "Sedex 10";
        }
        else if($codigo==40290 || $this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex_hoje', $this->_storeScope)){
            return "Sedex HOJE";
        }
        else if($codigo==40045 || $this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex_cobrar', $this->_storeScope)){
            return "Sedex a cobrar";
        }
        else{
            return "Undefined";
        }
    }

    /** get Shipping Quote To Populate */
    public function getServiceToPopulate($service,$weight,$finalPostcode){
        if($this->_scopeConfig->getValue('carriers/igorludgero_correios/webservice_url',$this->_storeScope) != "")
            $url = $this->_scopeConfig->getValue('carriers/igorludgero_correios/webservice_url',$this->_storeScope);
        else
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?StrRetorno=xml";
        $login = $this->_scopeConfig->getValue('carriers/igorludgero_correios/login',$this->_storeScope);
        $password = $this->_scopeConfig->getValue('carriers/igorludgero_correios/password',$this->_storeScope);
        if(intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_height',$this->_storeScope))>0)
            $defHeight = intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_height',$this->_storeScope));
        else
            $defHeight = 2;
        if(intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_width',$this->_storeScope))>0)
            $defWidth = intval($this->_scopeConfig->getValue('carriers/igorludgero_correios/default_width',$this->_storeScope));
        else
            $defWidth = 16;
        if($this->_scopeConfig->getValue('carriers/igorludgero_correios/owner_hands',$this->_storeScope)==0){
            $ownerHands = 'N';
        }
        else{
            $ownerHands = 'S';
        }
        if($this->_scopeConfig->getValue('carriers/igorludgero_correios/received_warning',$this->_storeScope)==0){
            $receivedWarning = 'N';
        }
        else{
            $receivedWarning = 'S';
        }
        $origPostcode = $this->_scopeConfig->getValue("shipping/origin/postcode",$this->_storeScope);
        $declaredValue = $this->_scopeConfig->getValue('carriers/igorludgero_correios/declared_value',$this->_storeScope);

        //Check if the service needs the login and password
        if(in_array($service,$this->_obligatoryLogin)==true && ($login=="" || $password=="")){
            $this->logMessage("Impossible to calculate the service ".$service." because the login/password isn't filled.");
            return false;
        }

        if($login!="")
            $url_d = $url."&nCdEmpresa=".$login."&sDsSenha=".$password."&nCdFormato=1&nCdServico=".$service."&nVlComprimento=".$defWidth."&nVlAltura=".$defHeight."&nVlLargura=".$defWidth."&sCepOrigem=".$origPostcode."&sCdMaoPropria=".$ownerHands."&sCdAvisoRecebimento=".$receivedWarning."&nVlValorDeclarado=".$declaredValue."&nVlPeso=".$weight."&sCepDestino=".$finalPostcode;
        else
            $url_d = $url."&nCdFormato=1&nCdServico=".$service."&nVlComprimento=".$defWidth."&nVlAltura=".$defHeight."&nVlLargura=".$defWidth."&sCepOrigem=".$origPostcode."&sCdMaoPropria=".$ownerHands."&sCdAvisoRecebimento=".$receivedWarning."&nVlValorDeclarado=".$declaredValue."&nVlPeso=".$weight."&sCepDestino=".$finalPostcode;
        $this->logMessage($url_d);
        $urls = array($url_d);
        $shippingQuotes = $this->getOnlineShippingQuotes($urls);
        if(count($shippingQuotes)>0)
            return $shippingQuotes[0];
        else
            return false;
    }

    /** Get Correios Online Quotes */
    public function getOnlineShippingQuotes($urlsArray,$isOffline = false){
        $deliveryMessage = $this->_scopeConfig->getValue("carriers/igorludgero_correios/deliverydays_message",$this->_storeScope);
        if($deliveryMessage==""){
            $deliveryMessage = "%s - Em média %d dia(s)";
        }
        $showDeliveryDays = $this->_scopeConfig->getValue("carriers/igorludgero_correios/show_deliverydays",$this->_storeScope);
        $addDeliveryDays = intval($this->_scopeConfig->getValue("carriers/igorludgero_correios/add_deliverydays",$this->_storeScope));

        $handlingFee = 0;
        if($this->_scopeConfig->getValue("carriers/igorludgero_correios/handling_fee",$this->_storeScope)!=""){
            if(is_numeric($this->_scopeConfig->getValue("carriers/igorludgero_correios/handling_fee",$this->_storeScope))){
                $handlingFee = $this->_scopeConfig->getValue("carriers/igorludgero_correios/handling_fee",$this->_storeScope);
            }
        }

        //Don't add more days if is a offline result
        if($isOffline==true){
            $addDeliveryDays = 0;
        }

        $ratingsCollection = array();
        foreach ($urlsArray as $url_d) {
            $xml=null;
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url_d);
                curl_setopt($ch, CURLOPT_HEADER, 0);

                ob_start();
                curl_exec($ch);
                curl_close($ch);
                $content = ob_get_contents();
                ob_end_clean();
                if($content)
                    $xml = new \SimpleXMLElement($content);
            } catch (\Exception $e) {
                $this->logMessage("Error in consult XML: ".$e->getMessage());
                continue;
            }

            if ($xml!=null) {
                foreach ($xml->cServico as $servico) {
                    if ($servico->Erro == "0"){
                        try {
                            $data = array();
                            if($showDeliveryDays==0)
                                $data['servico'] = $this->getMethodName($servico->Codigo);
                            else
                                $data['servico'] = sprintf($deliveryMessage,$this->getMethodName($servico->Codigo),intval($servico->PrazoEntrega+$addDeliveryDays));
                            $data['valor'] = str_replace(",",".",$servico->Valor) + $handlingFee;
                            $data['prazo'] = $servico->PrazoEntrega + $addDeliveryDays;
                            $data['servico_codigo'] = $servico->Codigo;
                            array_push($ratingsCollection, $data);
                        } catch (\Exception $ex) {
                            $this->logMessage("Error in consult XML2: ".$ex->getMessage());
                        }
                    } else {
                        $this->logMessage("Error in consult XML3: ");
                    }
                }
            }
        }
        return $ratingsCollection;
    }

    /** Check Country */
    public function checkCountry($request,$fromCountryId) {
        $from = $fromCountryId;
        $to = $request->getDestCountryId();
        if ($from != "BR" || $to != "BR"){
            return false;
        }
        return true;
    }

    /** Format Postcode */
    public function formatZip($zipcode){
        $new = trim($zipcode);
        $new = preg_replace('/[^0-9\s]/', '', $new);

        if(!preg_match("/^[0-9]{7,8}$/", $new)){
            return false;
        } elseif(preg_match("/^[0-9]{7}$/", $new)){
            $new = "0" . $new;
        }
        return $new;
    }

    /** Check if can create a new offline postcode track */
    public function canCreateOfflineTrack($service,$firstPostcode,$lastPostcode){
        $collection = $this->_cotacoesFactory->create()->getCollection()->addFieldToFilter('cep_inicio', ["lteq" => $firstPostcode])->addFieldToFilter('cep_fim', ["gteq" => $lastPostcode])->addFilter("servico",$service);
        if($collection->count()>0){
            return false;
        }
        else{
            return true;
        }
    }

    /** Format Price */
    public function formatPrice($originalPrice){
        $finalPrice = str_replace(" ","",$originalPrice);
        $finalPrice = str_replace("R$","",$finalPrice);
        return $finalPrice;
    }

    /** Convert weight to calculate */
    public function fixWeight($weight) {
        $result = $weight;
        if (($this->_scopeConfig->getValue("carriers/igorludgero_correios/weight_type",$this->_storeScope) == 'gr')) {
            $result = number_format($weight/1000, 2, '.', '');
        }
        return $result;
    }

    /** Check Weight Range */
    public function checkWeightRange($request) {
        $weight = $this->fixWeight($request->getPackageWeight());
        $maxWeight = (doubleval($this->_scopeConfig->getValue("carriers/igorludgero_correios/max_weight",$this->_storeScope)));
        if($weight > $maxWeight || $weight <= 0){
            return false;
        }
        return true;
    }

    /** Calculate Cubic Weight of packet */
    public function getCubicWeight($quote){
        $cubicWeight = 0;
        $items = $quote->getAllVisibleItems();
        $maxH = 90;
        $minH = 2;
        $maxW = 90;
        $minW = 16;
        $maxD = 90;
        $minD = 11;
        $sumMax = 160;
        $coefficient = 6000;
        $validate = $this->_scopeConfig->getValue('carriers/igorludgero_correios/validate_dimensions',$this->_storeScope);
        foreach($items as $item){
            $productItem = $item->getProduct();
            $product = $this->_productRepository->getById($productItem->getId());
            $width = (!$product->getData('correios_width')) ? intval($this->_scopeConfig->getValue("carriers/igorludgero_correios/default_width",$this->_storeScope)) : $product->getData('correios_width');
            $height = (!$product->getData('correios_height')) ? intval($this->_scopeConfig->getValue("carriers/igorludgero_correios/default_height",$this->_storeScope)) : $product->getData('correios_height');
            $depth = (!$product->getData('correios_depth')) ? intval($this->_scopeConfig->getValue("carriers/igorludgero_correios/default_depth",$this->_storeScope)) : $product->getData('correios_depth');
            if ($validate && ($height > $maxH || $height < $minH || $depth > $maxD || $depth < $minD || $width > $maxW || $width < $minW || ($height+$depth+$width) > $sumMax)) {
                $this->logMessage("Invalid Product Dimensions");
                return 0;
            }
            $cubicWeight += (($width * $depth * $height) / $coefficient) * $item->getQty();
        }
        return $this->fixWeight($cubicWeight);
    }

    /** Log a message */
    public function logMessage($message){
        if(($this->_scopeConfig->getValue("carriers/igorludgero_correios/enabled_log",$this->_storeScope) == 1)) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/igorludgero_correios.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info($message);
        }
    }

    /** Update offline tracks after a period */
    public function updateOfflineTracks(){
        $lastItem = $this->_cotacoesFactory->create()->getCollection()->setOrder("ultimo_update","desc")->getFirstItem();
        $this->logMessage("Last Update: ".$lastItem->getUltimoUpdate());

        $daysUpdate = $this->_scopeConfig->getValue("carriers/igorludgero_correios/maxdays_update",$this->_storeScope);
        if(!is_numeric($daysUpdate)){
            $daysUpdate = 15;
        }
        if($daysUpdate<=0){
            $daysUpdate = 15;
        }

        $lastUpdateDatetime = $lastItem->getUltimoUpdate();
        $nowDate = date('Y-m-d H:i:s');

        $diff = abs(strtotime($nowDate) - strtotime($lastUpdateDatetime));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        $this->logMessage("Days: ".$days." daysUpdate: ".$daysUpdate);

        if($days<$daysUpdate){
            //Dont need to update
            return false;
        }

        $collectionToUpdate = $this->_cotacoesFactory->create()->getCollection();
        $this->updateTrackCollection($collectionToUpdate);
        $this->logMessage("Offline Postcode Tracks updated");

    }

    private function updateTrackCollection($collection){
        $updated = 0;
        $errors = 0;
        if($collection->count()>0) {
            foreach ($collection as $cotacao) {
                $cotacaoObj = $this->_cotacoesFactory->create()->load($cotacao->getId());
                $cotacaoValues = $this->getServiceToPopulate($cotacaoObj->getServico(), $cotacaoObj->getPeso(), $cotacaoObj->getCepFim());
                if ($cotacaoValues != false) {
                    $now = new \DateTime();
                    $cotacaoObj->setPrazo($cotacaoValues["prazo"])->setValor($cotacaoValues["valor"])->setUltimoUpdate($now->format('Y-m-d H:i:s'));
                    if ($cotacaoObj->save()) {
                        $updated++;
                    } else {
                        $errors++;
                    }
                } else {
                    //Try to get with the half postcode in the current postcode track
                    $halfPostcode = ($cotacaoObj->getCepFim() - ($cotacaoObj->getCepFim() - $cotacaoObj->getCepInicio()) / 2);
                    $cotacaoValues = $this->getServiceToPopulate($cotacaoObj->getServico(), $cotacaoObj->getPeso(), $halfPostcode);
                    if ($cotacaoValues != false) {
                        $now = new \DateTime();
                        $cotacaoObj->setPrazo($cotacaoValues["prazo"])->setValor($cotacaoValues["valor"])->setUltimoUpdate($now->format('Y-m-d H:i:s'));
                        if ($cotacaoObj->save()) {
                            $updated++;
                        } else {
                            $errors++;
                        }
                    } else {
                        $errors++;
                    }
                }
            }
        }
        return array($updated,$errors);
    }

    public function getPostMethodCodes($methods){
        $arrayMethods = array();
        foreach ($methods as $codigo){
            if($codigo==40010){
                if($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex', $this->_storeScope) != "")
                    $arrayMethods[] = strval($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex', $this->_storeScope));
                else
                    $arrayMethods[] = $codigo;
            }
            else if($codigo==81019){
                if($this->_scopeConfig->getValue('correios_postingmethods_config/settings/esedex', $this->_storeScope) != "")
                    $arrayMethods[] = strval($this->_scopeConfig->getValue('correios_postingmethods_config/settings/esedex', $this->_storeScope));
                else
                    $arrayMethods[] = $codigo;
            }
            else if($codigo==41106 || $codigo==41068){
                if($this->_scopeConfig->getValue('correios_postingmethods_config/settings/pac', $this->_storeScope) != "")
                    $arrayMethods[] = strval($this->_scopeConfig->getValue('correios_postingmethods_config/settings/pac', $this->_storeScope));
                else
                    $arrayMethods[] = $codigo;
            }
            else if($codigo==40215){
                if($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex10', $this->_storeScope) != "")
                    $arrayMethods[] = strval($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex10', $this->_storeScope));
                else
                    $arrayMethods[] = $codigo;
            }
            else if($codigo==40290){
                if($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex_hoje', $this->_storeScope) != "")
                    $arrayMethods[] = strval($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex_hoje', $this->_storeScope));
                else
                    $arrayMethods[] = $codigo;
            }
            else if($codigo==40045){
                if($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex_cobrar', $this->_storeScope) != "")
                    $arrayMethods[] = strval($this->_scopeConfig->getValue('correios_postingmethods_config/settings/sedex_cobrar', $this->_storeScope));
                else
                    $arrayMethods[] = $codigo;
            }
        }
        return $arrayMethods;
    }
}