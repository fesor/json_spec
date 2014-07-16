<?php

namespace JsonSpec\Behat\Wrapper;

use GuzzleHttp\Client;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

/**
 * This is wrapper around Guzzle HTTP Client
 * Wrapping is necessary because we need access to last succeeded response
 *
 * Class GuzzleClient
 * @package JsonSpec\Behat\Wrapper
 */
class GuzzleClient extends Client
{

    /**
     * @var ResponseInterface
     */
    private $lastResponse;

    /**
     * @return ResponseInterface
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }


    public function send(RequestInterface $request)
    {
        $this->lastResponse = parent::send($request);

        return $this->lastResponse;
    }


}
