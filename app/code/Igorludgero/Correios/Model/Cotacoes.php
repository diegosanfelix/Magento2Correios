<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Correios\Model;

use Magento\Framework\Model\AbstractModel;

class Cotacoes extends AbstractModel
{


    protected $_helper;
    protected $_storeScope;
    protected $_scopeConfig;
    protected $_cotacoesFactory;

    protected $_maxWeights = array(
        array('service' => 40010, 'max' => 30),
        array('service' => 40096, 'max' => 30),
        array('service' => 40436, 'max' => 30),
        array('service' => 40444, 'max' => 30),
        array('service' => 81019, 'max' => 15),
        array('service' => 41106, 'max' => 30),
        array('service' => 41068, 'max' => 30),
        array('service' => 40215, 'max' => 10),
        array('service' => 40290, 'max' => 10),
        array('service' => 40045, 'max' => 10)
    );

    protected $_ratesPacAndSedex = array(
        array(1,9999999,4811210),
        array(10000000,19999999,19999999),
        array(20000000,24799999,24799999),
        array(24800001,28999999,28999999),
        array(29000000,29184999,29184999),
        array(29185000,29999999,29999999),
        array(30000000,34999999,34999999),
        array(35000000,39999999,39999999),
        array(40000000,43849999,43849999),
        array(43850000,48999999,48999999),
        array(49000000,49099999,49099999),
        array(49100000,49999999,49999999),
        array(50000000,54999999,54999999),
        array(55000000,56999999,56999999),
        array(57000000,57099999,57099999),
        array(57100000,57999999,57999999),
        array(58000000,58099999,58099999),
        array(58100000,58999999,58999999),
        array(59000000,59099000,59099000),
        array(59100000,59999999,59999999),
        array(60000000,61699999,61699999),
        array(61700000,63999999,63999999),
        array(64000000,64099999,64099999),
        array(64100000,64999999,64999999),
        array(65000000,65099000,65099000),
        array(65100000,65999999,65999999),
        array(66000000,67999999,67999999),
        array(68000000,68899999,68899999),
        array(68900000,68929999,68929999),
        array(68930000,68999999,68999999),
        array(69000000,69099000,69099000),
        array(69100000,69299000,69299000),
        array(69300000,69339999,69339999),
        array(69340000,69399999,69399999),
        array(69400000,69899999,69899999),
        array(69900000,69920999,69920999),
        array(69921000,69999999,69999999),
        array(70000000,73699999,73699999),
        array(73700000,76799999,76799999),
        array(76800000,76834999,76834999),
        array(76835000,76999999,76999999),
        array(77000000,77299999,77299999),
        array(77300000,77999999,77999999),
        array(78000000,78169999,78169999),
        array(78170000,78899999,78899999),
        array(79000000,79124999,79124999),
        array(79125000,79999999,79999999),
        array(80000000,83729999,83729999),
        array(83730000,87999999,87999999),
        array(88000000,88139999,88139999),
        array(88140000,89999999,89999999),
        array(90000000,94999999,94999999),
        array(95000000,99999999,99999999)
    );

    protected $_ratesEsedex = array(
        array(69900000,69924999),
        array(57000000,57099999),
        array(69000000,69099999),
        array(68900000,68911999),
        array(40000000,42599999),
        array(42700000,42700999),
        array(44000000,44099999),
        array(45000000,45099999),
        array(45650000,45659999),
        array(60000000,60999999),
        array(61600000,61659999),
        array(61900000,61939999),
        array(70000000,72799999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(69900000,69924999),
        array(72860000,72869999),
        array(72880000,73499999),
        array(29000000,29099999),
        array(29100000,29129999),
        array(29130000,29139999),
        array(29140000,29159999),
        array(29160000,29184999),
        array(29200000,29229999),
        array(29300000,29320999),
        array(29700000,29719999),
        array(29900000,29919999),
        array(72800000,72859999),
        array(74000000,74799999),
        array(74800000,74999999),
        array(75000000,75149999),
        array(65000000,65099999),
        array(30000000,31999999),
        array(32000000,32399999),
        array(32400000,32499999),
        array(32500000,32899999),
        array(33000000,33199999),
        array(33200000,33299999),
        array(33400000,33499999),
        array(33600000,33699999),
        array(33800000,33950999),
        array(34000000,34299999),
        array(34500000,34799999),
        array(34800000,34989999),
        array(35000000,35099999),
        array(35160000,35164999),
        array(35500000,35504999),
        array(35700000,35704999),
        array(35960000,35968999),
        array(36000000,36099999),
        array(36500000,36509999),
        array(37000000,37109999),
        array(37115000,37119999),
        array(37150000,37150999),
        array(37260000,37260999),
        array(37443000,37444999),
        array(37470000,37470999),
        array(37540000,37540999),
        array(27550000,37555999),
        array(37640000,37640999),
        array(37700000,37719999),
        array(37900000,37904999),
        array(38000000,38099999),
        array(38180000,38184999),
        array(38400001,38439999),
        array(38500000,38509999),
        array(39400000,39409999),
        array(79000000,79124999),
        array(79800000,79849999),
        array(78000000,78099999),
        array(78110000,78164999),
        array(78700000,78750999),
        array(66000000,66999999),
        array(67000000,67199999),
        array(58000000,58099999),
        array(58400000,58439999),
        array(50000000,52999999),
        array(53000000,53399999),
        array(53400000,53499999),
        array(54000000,54499999),
        array(55000000,55099999),
        array(56300000,56334999),
        array(64000000,64099999),
        array(64600000,64608999),
        array(80000000,82999999),
        array(83000000,83149999),
        array(83200000,83249999),
        array(83300000,83319999),
        array(83320000,83349999),
        array(83400000,83417999),
        array(83535000,83539999),
        array(83600000,83640999),
        array(83700000,83724999),
        array(84000000,84099999),
        array(84600000,84600999),
        array(85000000,85109999),
        array(85500000,85513999),
        array(85800000,85820999),
        array(85825000,85825999),
        array(85850000,85871999),
        array(85960000,85979999),
        array(86000000,86099999),
        array(86150000,86159999),
        array(86180000,86199999),
        array(86200000,86209999),
        array(86300000,86309999),
        array(86600000,86609999),
        array(86700000,86709999),
        array(86800000,86819999),
        array(86845000,86847999),
        array(87000000,87099999),
        array(87110000,87119999),
        array(87200000,87209999),
        array(87300000,87314999),
        array(87485000,87489999),
        array(87500000,87515999),
        array(20500000,23799999),
        array(23900000,23959999),
        array(24000000,24399999),
        array(24400000,24799999),
        array(25000000,25499999),
        array(25500000,25599999),
        array(25600000,25779999),
        array(25800000,25829999),
        array(25950000,25995999),
        array(26000000,26099999),
        array(26100000,26199999),
        array(26200000,26299999),
        array(26500000,26549999),
        array(26550000,26599999),
        array(27200000,27299999),
        array(27300000,27399999),
        array(27500000,27549999),
        array(27600000,27699999),
        array(27900000,27979999),
        array(28000000,28099999),
        array(28600000,28636999),
        array(28900000,28924999),
        array(59000000,59139999),
        array(59140000,59161999),
        array(59600000,59649999),
        array(76800000,76834999),
        array(69300000,69339999),
        array(90000000,91999999),
        array(92000000,92449999),
        array(93000000,93179999),
        array(93200000,93249999),
        array(93250000,93299999),
        array(93300000,93599999),
        array(93700000,93799999),
        array(93800000,93879999),
        array(94000000,94399999),
        array(94400000,94729999),
        array(94800000,94889999),
        array(94900000,94999999),
        array(95000000,95124999),
        array(95630000,95630999),
        array(95700000,95700999),
        array(95880000,95884999),
        array(95900000,95914999),
        array(96000000,96099999),
        array(96180000,96180999),
        array(96200000,96219999),
        array(96800000,96849999),
        array(97000000,97119999),
        array(98400000,98400999),
        array(98800000,98824999),
        array(98960000,98969999),
        array(99000000,99099999),
        array(88000000,88099999),
        array(88100000,88123999),
        array(88130000,88138999),
        array(88160000,88179999),
        array(88215000,88215999),
        array(88240000,88259999),
        array(88300000,88319999),
        array(88330000,88339999),
        array(88340000,88340999),
        array(88350000,88359999),
        array(88390000,88394999),
        array(88495000,88499999),
        array(88500000,88529999),
        array(88700000,88709999),
        array(88750000,88750999),
        array(88800000,88819999),
        array(88865000,88869999),
        array(89000000,89099999),
        array(89107000,89107999),
        array(89108000,89109999),
        array(89110000,89114999),
        array(89160000,89169999),
        array(89200000,89239999),
        array(89250000,89269999),
        array(89280000,89293999),
        array(89520000,89529999),
        array(89700000,89729999),
        array(89800000,89816199),
        array(89868000,89869999),
        array(49000000,49098999),
        array(1000000,9999999),
        array(11000000,11249999),
        array(11250000,11299999),
        array(11300000,11399999),
        array(11400000,11499999),
        array(11500000,11599999),
        array(11700000,11729999),
        array(11900000,11900999),
        array(12000000,12119999),
        array(12200000,12248999),
        array(12280000,12299999),
        array(12300000,12349999),
        array(12400000,12449999),
        array(12500000,12524999),
        array(12570000,12579999),
        array(12600000,12614999),
        array(12630000,12689999),
        array(12700000,12759999),
        array(12900000,12929999),
        array(12940000,12954999),
        array(13000000,13139999),
        array(13140000,13140999),
        array(13170000,13182999),
        array(13183000,13189999),
        array(13200000,13219999),
        array(13230000,13239999),
        array(13240000,13249999),
        array(13250000,13259999),
        array(13270000,13279999),
        array(13280000,13280999),
        array(13300000,13314999),
        array(13320000,13329999),
        array(13330000,13349999),
        array(13400000,13427999),
        array(13432000,13432999),
        array(13450000,13459999),
        array(13460000,13464999),
        array(13465000,13479999),
        array(13480000,13489999),
        array(13500000,13507999),
        array(13560000,13577999),
        array(13600000,13609999),
        array(13610000,13624999),
        array(13630000,13644999),
        array(13670000,13670999),
        array(13760000,13769999),
        array(13820000,13820999),
        array(13840000,13855999),
        array(13900000,13909999),
        array(13920000,13920999),
        array(13930000,13930999),
        array(14000000,14114999),
        array(14160000,14179999),
        array(14300000,14339999),
        array(14340000,14340999),
        array(14350000,14389999),
        array(14400000,14414999),
        array(14700000,14718999),
        array(14780000,14789999),
        array(14800000,14811999),
        array(14940000,14940999),
        array(15000000,15099999),
        array(15290000,15290999),
        array(15350000,15354999),
        array(15400000,15400999),
        array(15800000,15819999),
        array(15900000,15900999),
        array(16000000,16129999),
        array(16200000,16209999),
        array(17000000,17109999),
        array(17190000,17190999),
        array(17200000,17220999),
        array(17300000,17319999),
        array(17340000,17340999),
        array(17500000,17529999),
        array(17560000,17560999),
        array(18000000,18109999),
        array(18110000,18119999),
        array(18200000,18215999),
        array(18270000,18282999),
        array(18600001,18619999),
        array(18680000,18687999),
        array(19000000,19109999),
        array(19400000,19409999),
        array(19560000,19560999),
        array(19800000,19819999),
        array(19880000,19899999),
        array(77000000,77249999),
        array(77400000,77469999),
        array(77800000,77834999)
    );

    protected $_offlineAvailable = array(40010,40096,40436,40444,81019,41106,41068);

    public function __construct( \Magento\Framework\Model\Context $context,
                                 \Magento\Framework\Registry $registry,
                                 \Igorludgero\Correios\Model\ResourceModel\CotacoesFactory $cotacoesFactory,
                                 \Igorludgero\Correios\Helper\Data $helper,
                                 \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                 array $data = [])
    {
        parent::__construct($context, $registry, null, null, $data);
        $this->_storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->_scopeConfig = $scopeConfig;
        $this->_helper = $helper;
        $this->_cotacoesFactory = $cotacoesFactory;
    }

    protected function _construct()
    {
        $this->_init('Igorludgero\Correios\Model\ResourceModel\Cotacoes');
    }

    /** Populate offline table */
    public function populate(){
        $postingMethods = explode(",",$this->_scopeConfig->getValue('carriers/igorludgero_correios/posting_methods',$this->_storeScope));
        if($this->getCollection()->count()>0){
            $this->_helper->logMessage("Can't populate because the db isn't empty. First you to clear the db.");
            return false;
        }
        foreach ($postingMethods as $method){
            if(in_array($method,$this->_offlineAvailable)==true){
                $maxWeight = 0;
                foreach ($this->_maxWeights as $weights){
                    if($weights["service"]==$method){
                        $maxWeight = $weights["max"];
                    }
                }
                $weights = array(0.3);
                for($i=1;$i<=$maxWeight;$i++){
                    $weights[] = $i;
                }
                foreach ($weights as $weight){
                    if($method==81019){
                        foreach ($this->_ratesEsedex as $rate){
                            $newCotacao = $this->_cotacoesFactory->create();
                            //$cotacaoValues = $this->_helper->getServiceToPopulate($method,$weight,$rate[2]);
                            $now = new \DateTime();
                            $newCotacao->setServico($method)
                                ->setPrazo(0)
                                ->setPeso($weight)
                                ->setValor(0)
                                ->setCepInicio($rate[0])
                                ->setCepFim($rate[1])
                                ->setUltimoUpdate($now->format('Y-m-d H:i:s'));
                            if($newCotacao->save()==false){
                                $this->_helper->logMessage("Erro saving the service ".$method." with the weight: ".$weight);
                            }
                        }
                    }
                    else{
                        foreach ($this->_ratesPacAndSedex as $rate){
                            $newCotacao = $this->_cotacoesFactory->create();
                            //$cotacaoValues = $this->_helper->getServiceToPopulate($method,$weight,$rate[1]);
                            $now = new \DateTime();
                            $newCotacao->setServico($method)
                                ->setPrazo(0)
                                ->setPeso($weight)
                                ->setValor(0)
                                ->setCepInicio($rate[0])
                                ->setCepFim($rate[1])
                                ->setUltimoUpdate($now->format('Y-m-d H:i:s'));
                            if($newCotacao->save()==false){
                                $this->_helper->logMessage("Erro saving the service ".$method." with the weight: ".$weight);
                            }
                        }
                    }
                }

            }
            else{
                $this->_helper->logMessage("Service ".$method." ignored because you can't store this service offline.");
            }
        }
        return true;
    }

    /** Update offline postcode tracks */
    public function updateTracks(){
        $maxNumber = $this->_scopeConfig->getValue("carriers/igorludgero_correios/max_update",$this->_storeScope);
        if($maxNumber=="")
            $maxNumber = 100;
        $updated = 0;
        $errors = 0;
        $collection = $this->getCollection()->addFilter("valor",0)->setPageSize($maxNumber)->setCurPage(1);
        if($collection->count()>0) {
            //Updating in the first time
            foreach ($collection as $cotacao) {
                $cotacaoObj = $this->load($cotacao->getId());
                $cotacaoValues = $this->_helper->getServiceToPopulate($cotacaoObj->getServico(), $cotacaoObj->getPeso(), $cotacaoObj->getCepFim());
                if ($cotacaoValues != false) {
                    $now = new \DateTime();
                    $cotacaoObj->setPrazo($cotacaoValues["prazo"])->setValor($cotacaoValues["valor"])->setUltimoUpdate($now->format('Y-m-d H:i:s'));
                    if ($cotacaoObj->save()) {
                        $updated++;
                    } else {
                        $errors++;
                    }
                } else {
                    $halfPostcode = ($cotacaoObj->getCepFim() - ($cotacaoObj->getCepFim() - $cotacaoObj->getCepInicio()) / 2);
                    $cotacaoValues = $this->_helper->getServiceToPopulate($cotacaoObj->getServico(), $cotacaoObj->getPeso(), $halfPostcode);
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
        else{
            //Trying to update manually
            $result = $this->_helper->updateOfflineTracks();
            if($result==false){
                return false;
            }
            else {
                $updated = $result[0];
                $errors = $result[1];
            }
        }
        return array($updated,$errors);
    }

}