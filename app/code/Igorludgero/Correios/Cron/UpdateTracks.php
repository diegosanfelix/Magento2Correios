<?php
/**
 * @package     Igorludgero_Correios
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
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