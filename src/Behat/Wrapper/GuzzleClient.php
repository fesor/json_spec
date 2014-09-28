<?php

namespace JsonSpec\Behat\Wrapper;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\ResponseInterface;

/**
 * This is wrapper around Guzzle HTTP Client
 * Wrapping is necessary because we need access to last succeeded response
 *
 * Class GuzzleClient
 * @package JsonSpec\Behat\Wrapper
 */
class GuzzleClient implements ClientInterface
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseInterface
     */
    private $lastResponse;


    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @inheritdoc
     */
    public function send(RequestInterface $request)
    {
        return $this->rememberResponse($this->client->send($request));
    }


    /**
     * @inheritdoc
     */
    public function createRequest($method, $url = null, array $options = [])
    {
        return $this->client->createRequest($method, $url, $options);
    }

    /**
     * @inheritdoc
     */

    public function get($url = null, $options = [])
    {
        return $this->rememberResponse($this->client->get($url, $options));
    }

    /**
     * @inheritdoc
     */
    public function head($url = null, array $options = [])
    {
        // json_spec doesn't support HEAD requests
        return $this->client->get($url, $options);
    }

    /**
     * @inheritdoc
     */
    public function delete($url = null, array $options = [])
    {
        return $this->rememberResponse($this->client->get($url, $options));
    }

    /**
     * @inheritdoc
     */
    public function put($url = null, array $options = [])
    {
        return $this->rememberResponse($this->client->get($url, $options));
    }

    /**
     * @inheritdoc
     */
    public function patch($url = null, array $options = [])
    {
        return $this->rememberResponse($this->client->patch($url, $options));
    }

    /**
     * @inheritdoc
     */
    public function post($url = null, array $options = [])
    {
        return $this->rememberResponse($this->client->post($url, $options));
    }

    /**
     * @inheritdoc
     */

    public function options($url = null, array $options = [])
    {
        // json_spec doesn't support OPTIONS requests
        return $this->client->options($url, $options);
    }

    /**
     * @inheritdoc
     */
    public function sendAll($requests, array $options = [])
    {
        // json_spec doesn't support multiple response processing
        return $this->client->sendAll($requests, $options);
    }

    /**
     * @inheritdoc
     */
    public function getDefaultOption($keyOrPath = null)
    {
        $this->client->getDefaultOption($keyOrPath);
    }

    /**
     * @inheritdoc
     */

    public function setDefaultOption($keyOrPath, $value)
    {
        return $this->client->getDefaultOption($keyOrPath, $value);
    }

    /**
     * @inheritdoc
     */

    public function getBaseUrl()
    {
        return $this->client->getBaseUrl();
    }

    /**
     * @inheritdoc
     */
    public function getEmitter()
    {
        return $this->client->getEmitter();
    }

    private function rememberResponse(Response $response)
    {
        $this->lastResponse = $response;

        return $response;
    }


}
