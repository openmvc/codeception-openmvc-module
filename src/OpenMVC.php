<?php namespace Codeception\Module;

use \Codeception\Util\Framework;

class OpenMVC extends Framework {

  protected $config = array(
    'locale' => 'en',
    'index' => 'public_html/index.php'
  );

  public function _before()
  {

    $this->client = new \Codeception\Util\Connector\Universal();

    $this->client->setIndex( $this->config['index'] );

  }

  public function amOnPage($page)
  {
    $this->crawler = $this->client->request('GET', $page, array('locale'  => $this->config['locale']));
    $this->debugResponse();
  }

}
