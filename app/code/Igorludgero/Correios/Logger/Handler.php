<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 05/09/16
 * Time: 16:29
 */

namespace Igorludgero\Correios\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = \Monolog\Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/ilcorreios.log';
}