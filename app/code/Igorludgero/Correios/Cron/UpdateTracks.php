<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 14/09/16
 * Time: 20:31
 */

namespace Igorludgero\Correios\Cron;

class UpdateTracks
{

    protected $_helper;

    public function __construct(\Igorludgero\Correios\Helper\Data $_helper)
    {
        $this->_helper = $_helper;
    }

    public function execute()
    {
        $this->_helper->logMessage("Cron job updateTracks executed.");
        $this->_helper->updateOfflineTracks();
        return $this;
    }
}