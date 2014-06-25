<?php namespace Codeception\Module;

use Codeception\Util\Framework;
use Codeception\Util\Connector\Universal as BaseClient;
use Symfony\Component\BrowserKit\Response;

class OpenMVC extends Framework {

  protected $config = array(
    'locale' => 'en',
    'index' => 'public_html/index.php'
  );

  public function _before()
  {

    $this->client = new OpenMVCClient();

    $this->client->setIndex( $this->config['index'] );

  }

  public function amOnPage($page)
  {
    $this->crawler = $this->client->request('GET', $page, array('locale'  => $this->config['locale']));
    $this->debugResponse();
  }

}


class OpenMVCClient extends BaseClient
{
  public function doRequest($request) {
    if ($this->mockedResponse) {
      $response = $this->mockedResponse;
      $this->mockedResponse = null;
      return $response;
    }

    $_COOKIE = $request->getCookies();
    $_SERVER = $request->getServer();
    $_FILES = $request->getFiles();

    $uri = str_replace('http://localhost','',$request->getUri());

    if (strtoupper($request->getMethod()) == 'GET') {
      $_GET = $request->getParameters();
    } else {
      $_POST = $request->getParameters();
    }
    $_REQUEST = $request->getParameters();

    $_SERVER['REQUEST_METHOD'] = strtoupper($request->getMethod());
    $_SERVER['REQUEST_URI'] = strtoupper($uri);

    ob_start();
    include $this->index;

    $content = ob_get_contents();
    ob_end_clean();

    $headers = array();
    if( isset($App['ResponseHeaders']) )
    {
      $php_headers = $App['ResponseHeaders']->all();
      $status = $App['ResponseHeaders']->status();
      // Prevent Headers set in current application call to appear in next one
      $App['ResponseHeaders']->unregister();
    } else {
      $php_headers = array();
    }

    foreach ($php_headers as $key => $value) {
      if( $value !== null )
      {
        $headers[$key] = $value;
      }
    }
    $headers['Content-type'] = isset($headers['Content-type']) ? $headers['Content-type']: "text/html; charset=UTF-8";

    $response = new Response($content, $status, $headers);
    return $response;
  }
}
