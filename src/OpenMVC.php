<?php namespace Codeception\Module;

use \Codeception\Util\Framework;

class OpenMVC extends Framework {

    public function _before() {

      $this->client = new \Codeception\Util\Connector\Universal();

      $this->client->setIndex('public_html/index.php');

    }

}
