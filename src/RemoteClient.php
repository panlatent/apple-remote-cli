<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli;

use GuzzleHttp\Client;
use Panlatent\AppleRemoteCli\Support\DigitalAudioResponse as Response;

class RemoteClient
{
    protected $host;

    protected $port;

    protected $sessionId;

    protected $httpClient;

    protected $login = false;

    public function __construct($host = '127.0.0.1', $port = 3689)
    {
        $this->host = $host;
        $this->port = $port;
        $this->httpClient = new Client([
            'base_uri' => $this->host . ':' . $this->port,
        ]);
    }

    public function login($authorizationCode)
    {
        $response = $this->httpClient->get('/login', [
            'query' => [
                'pairing-guid' => $authorizationCode,
            ],
            'headers' => $this->makeHttpHeaders(),
        ]);
        $doc = Response::create($response->getBody());
        $this->sessionId = $doc->getElements()->one('mlog')->one('mlid')->getValue();
        $this->login = true;
    }

    public function logout()
    {

    }

    public function isLogin()
    {

    }

    public function getServerInfo()
    {

    }

    /**
     * @param string        $uri
     * @param array         $option
     * @return \Panlatent\DigitalAudio\Document
     * @throws \Panlatent\AppleRemoteCli\Exception
     */
    public function get($uri, $option)
    {
        if ( ! $this->login) {
            throw new Exception('Remote need login');
        }

        $option = array_merge_recursive([
            'query' => [
                'session-id' => $this->sessionId,
            ],
            'headers' => $this->makeHttpHeaders(),
        ], $option);

        $response = $this->httpClient->get($uri, $option);
        return Response::create($response->getBody());
    }

    protected function makeHttpHeaders()
    {
        $headers = [
            'Connection'         => 'keep-alive',
            'User-Agent'         => 'Remote',
            'Viewer-Only-Client' => '1',
        ];

        if ( ! $this->login) {
            return $headers;
        }

        return array_merge($headers, [
            'Client-ATV-Sharing-Version'    => '1.2',
            'Client-DAAP-Version'           => '3.12',
            'Client-DAAP-Validation'        => '',
            'Client-iTunes-Sharing-Version' => '3.10',
        ]);
    }
}