<?php

namespace Modules\ModHairWorld\Handlers;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Config;

class RequestClient {

    public function create($isAuthen = true): \GuzzleHttp\Client
    {
        $options = [
            'base_uri' => env('API_STG_SERVICE_URL'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Tenant-Id' => env('API_TENANT_ID'),
            ],
        ];
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest($this->addHost()));
        if ($isAuthen) {
            $stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
            $stack->push(Middleware::mapRequest($this->addAccessToken()));
            $options['headers']['X-Token'] = 'Add';
        }
        $options['handler'] = $stack;
        $client = new Client($options);
        return $client;
    }

    private function addHost() {
        return function (Request $request) {
            return $request->withHeader('Host', env('API_STG_SERVICE_HOST'));
        };
    }

    private function addAccessToken() {
        return function (Request $request) {
            if ($request->hasHeader('X-Token', 'Add')) {
                return $request->withHeader('Authorization', 'Bearer ' . $this->getClientAccessToken());
            }
            return $request;
        };
    }

    private function retryDecider() {
        return function(
            $retries,
            Request $request,
            Response $response = null,
            $exception = null
        ) {
            $maxRetries = 1;
            if ($retries > $maxRetries) {
                return false;
            }

            if ($response && $response->getStatusCode() === 401) {
                // received 401, so we need to refresh the token
                // this should call your custom function that requests a new token and stores it somewhere (cache)
                $this->refreshClientAccessToken();
                return true;
            }
            return false;
        };
    }

    public function retryDelay()
    {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }

    private function getClientAccessToken() {
        return Config::get('shared.stg_token');
    }

    private function setClientAccessToken($accessToken) {
        Config::set('shared.stg_token', $accessToken);
    }

    private function refreshClientAccessToken() {
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest($this->addHost()));
        $options = [
            'base_uri' => env('API_STG_SERVICE_URL'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Tenant-Id' => env('API_TENANT_ID'),
            ],
            'handler' => $stack,
        ];
        $client = new Client($options);
        $body = '{
            "tenantId":"' . env('API_TENANT_ID') . '",
            "password":"' . env('API_PASS') . '",
            "grantType":"client_credentials",
            "profileType":"ADMIN"
        }';
        try {
            $getBearer = $client->request('POST', '/uaa/user/login', [
                'body' => $body,
            ]);
            $apiToken = json_decode($getBearer->getBody())->data->access_token;
            $this->setClientAccessToken($apiToken);
            return $apiToken;
        } catch (\Exception $ex) {
            \Log::info('SYNC process stoped by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
            if (isset($user) && $user) {
                \Log::info('post data:');
                \Log::info(json_encode($user));
            }
        }
        return null;
    }
}