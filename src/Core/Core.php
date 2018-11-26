<?php

declare(strict_types=1);

namespace TrollAndToad\Sellbrite\Core;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

use TrollAndToad\Sellbrite\Interfaces\ApiCallInterface;

/**
 * @implements ApiCallInterface
 */
abstract class Core implements ApiCallInterface
{
    /**
     * @var string $apiEndpoint
     */
    protected $apiEndpoint;

    /**
     * @var array
     */
    protected $baseApiHeaders;

    /**
     * @var ClientInterface $httpClient
     */
    protected $httpClient;

    /**
     * @param ClientInterface
     */
    public function __construct(string $accountToken, string $secretKey, ?ClientInterface $httpClient = null)
    {
        // Base64 encode the username:password for Basic HTTP Authentication
        $auth = \base64_encode($accountToken . ':' . $secretKey);

        // Build the basic API headers. This contains the basic authorization
        $this->baseApiHeaders = [
            'headers' => [
                'Authorization' => ['Basic ' . $auth]
            ]
        ];

        $this->httpClient  = $httpClient !== null && $httpClient instanceof ClientInterface ?
            $httpClient : new Client();
    } // End public function __construct
} // End abstract class Core
