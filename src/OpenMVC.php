<?php namespace Codeception\Module;

use Codeception\Util\Framework;
use Codeception\Util\Connector\Universal as BaseClient;
use Codeception\TestCase;
use Symfony\Component\BrowserKit\Response;

class OpenMVC extends Framework {

  protected $config = array(
    'locale' => 'en',
    'index' => 'public_html/index.php',
  );

  // By default use localhost IP for requests
  private $ip = '127.0.0.1';

  public function _before(TestCase $test)
  {

    $this->client = new OpenMVCClient();

    $this->client->setIndex( $this->config['index'] );

  }

  /**
     * Assigns IP address to crawler
     *
     * Example:
     *
     * ``` php
     * <?php
     * // assign Turkish IP
     * $turkishIP = '5.2.81.123';
     * $I->haveIP($turkishIP);
     * // assign Japanese IP
     * $japaneseIP = '61.24.63.40';
     * $I->haveIP($japaneseIP);
     * ?>
     * ```
     */
  public function haveIP($ip)
  {
    $this->ip = $ip;
  }

  public function amOnPage($page)
  {
    $this->crawler = $this->client->request(

      // Method
      'GET',

      // URI
      $page,

      // Request parameters
      array(
        'locale'  => $this->config['locale'],
      ),

      // Files
      array(),

      // SERVER parameters (HTTP headers are referenced with a HTTP_ prefix)
      array(
        'REMOTE_ADDR' => $this->ip,
      ),

      // Body data
      '',

      // Whether to update the history or not (used internally for back(), 
      // forward() and reload())
      true
    );
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
    if( $App->registered('ResponseHeaders') )
    {
      $php_headers = $App['ResponseHeaders']->all();
      $status = $App['ResponseHeaders']->status();
      // Prevent Headers set in current application call to appear in next one
      $App['ResponseHeaders']->unregister();
    } else {
      $status = 200;
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

