<?php

namespace Judopay;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Judopay\Exception\ApiException;

class Request
{
    /** @var Configuration */
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Sets the headers and the authentication
     * @return array
     */
    public function getHeaders()
    {
        return [
            'api-version'   => $this->configuration->get('apiVersion'),
            'Accept'        => 'application/json; charset=utf-8',
            'Content-Type'  => 'application/json',
            'User-Agent'    => $this->configuration->get('userAgent'),
            'Authorization' => $this->getRequestAuthenticationHeader()
        ];
    }

    /**
     * Make a GET request to the specified resource path
     * @throws ApiException
     */
    public function get(string $resourcePath): Response
    {
        try {
            return Http::baseUrl($this->configuration->get('endpointUrl'))
                ->withHeaders($this->getHeaders())
                ->get($resourcePath)
                ->throw();
        } catch (RequestException $e) {
            throw ApiException::factory($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Make a POST request to the specified resource path and the provided data
     * @throws ApiException
     */
    public function post(string $resourcePath, array $data = []): Response
    {
        try {
            return Http::baseUrl($this->configuration->get('endpointUrl'))
                ->withHeaders($this->getHeaders())
                ->post($resourcePath, $data)
                ->throw();
        } catch (RequestException $e) {
            throw ApiException::factory($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Make a PUT request to the specified resource path and the provided data
     * @throws ApiException
     */
    public function put(string $resourcePath, array $data = []): Response
    {
        try {
            return Http::baseUrl($this->configuration->get('endpointUrl'))
                ->withHeaders($this->getHeaders())
                ->put($resourcePath, $data)
                ->throw();
        } catch (RequestException $e) {
            throw ApiException::factory($e);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /*
     * Gets 'Authorization' header value
     */
    private function getRequestAuthenticationHeader()
    {
        // Make sure we have all the required fields
        $this->configuration->validate();
        $oauthAccessToken = $this->configuration->get('oauthAccessToken');

        // Do we have an oAuth2 access token?
        if (!empty($oauthAccessToken)) {
            return 'Bearer ' . $oauthAccessToken;
        }

        // Otherwise, use basic authentication
        $basicAuth =  $this->configuration->get('apiToken'). ":" . $this->configuration->get('apiSecret');
        return 'Basic ' . base64_encode($basicAuth);
    }

    /**
     * Configuration getter
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
