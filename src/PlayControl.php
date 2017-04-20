<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli;

class PlayControl
{
    protected $client;

    protected $revisionNumber = 1;

    public function __construct(RemoteClient $client)
    {
        $this->client = $client;
    }

    public function play()
    {
        $this->run('playpause');
    }

    public function pause()
    {
        $this->run('playpause');
    }

    public function last()
    {
        $this->run('previtem');
    }

    public function next()
    {
        $this->run('nextitem');
    }

    public function repeat($repeat)
    {
        $this->run('setproperty', [
            'query' => [
                'dacp.repeatstate' => $repeat,
            ],
        ]);
    }

    public function shuffle($shuffle)
    {
        $this->run('setproperty', [
            'query' => [
                'dacp.shufflestate' => $shuffle,
            ],
        ]);
    }

    public function getVolume()
    {
        $response = $this->getProperty(['dmcp.volume']);

        return $response->getElements()->one('cmgt')->one('cmvo')->getValue();
    }

    public function setVolume($value)
    {
        if ( ! is_int($value) || $value < 0 || $value > 100) {
            throw new Exception('Error volume value range');
        }
        $this->run('setproperty', [
            'query' => [
                'dmcp.volume' => $value,
            ],
        ]);
    }

    public function getProperty($properties = [])
    {
        $query =  ['revision-number' => $this->makeRevisionNumber()];
        if ( ! empty($properties)) {
            $query['properties'] = implode(',', $properties);
        }
        $response = $this->run('getproperty', [
            'query' => $query,
        ]);

        return $response;
    }

    public function getPlayStatus()
    {
        $status = $this->run('playstatusupdate', [
            'query' => [
                'revision-number' => $this->makeRevisionNumber()
            ]
        ]);

        return new PlayStatus($status);
    }

    protected function run($action, $option = [])
    {
        $uri = '/ctrl-int/1/' . $action;

        return $this->client->get($uri, $option);
    }

    protected function makeRevisionNumber()
    {
        return $this->revisionNumber++;
    }
}