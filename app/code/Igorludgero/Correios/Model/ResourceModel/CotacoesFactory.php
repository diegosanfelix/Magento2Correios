<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 11/09/16
 * Time: 21:13
 */

namespace Igorludgero\Correios\Model;

class CotacoesFactory
{
    protected $objectManager = null;
    protected $instanceName = null;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = '\\Igorludgero\\Correios\\Model\\Cotacoes'
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    public function create(array $data = [])
    {
        return $this->objectManager->create($this->instanceName, $data);
    }
}
