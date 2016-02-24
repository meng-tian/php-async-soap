<?php

namespace Meng\AsyncSoap\Guzzle;

use GuzzleHttp\ClientInterface;
use Meng\Soap\Interpreter;
use Psr\Http\Message\ResponseInterface;

class Factory
{
    public function create(ClientInterface $client, $wsdl, array $options = [])
    {
        $interpreterPromise = \GuzzleHttp\Promise\promise_for(
            $client->requestAsync('GET', $wsdl)->then(
                function (ResponseInterface $response) use ($options) {
                    $wsdl = $response->getBody()->getContents();
                    return new Interpreter('data://text/plain;base64,' . base64_encode($wsdl), $options);
                }
            )
        );
        return new SoapClient($client, $interpreterPromise);
    }
}