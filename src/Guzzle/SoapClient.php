<?php

namespace Meng\AsyncSoap\Guzzle;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Meng\AsyncSoap\SoapClientInterface;
use Meng\Soap\Interpreter;
use Psr\Http\Message\ResponseInterface;

class SoapClient implements SoapClientInterface
{
    private $interpreter;
    private $client;

    public function __construct(ClientInterface $client, PromiseInterface $interpreterPromise)
    {
        $this->interpreter = $interpreterPromise;
        $this->client = $client;
    }

    public function call($name, array $arguments, array $options = null, $inputHeaders = null, array &$outputHeaders = null)
    {
        $response = null;
        $callPromise = $this->callAsync($name, $arguments, $options, $inputHeaders, $outputHeaders)->then(
            function ($result) use (&$response) {
                $response = $result;
            }
        );
        $callPromise->wait();
        return $response;
    }

    public function callAsync($name, array $arguments, array $options = null, $inputHeaders = null, array &$output_headers = null)
    {
        return $this->prepareRequest($name, $arguments, $options, $inputHeaders)
            ->then(
                function (array $request) {
                    list($endpoint, $headers, $body) = $request;
                    return $this->client->requestAsync('POST', $endpoint, ['headers'=>$headers, 'body'=>$body]);
                }
            )
            ->then(
                function (ResponseInterface $response) use ($output_headers) {
                    return $this->interpreter->then(
                        function (Interpreter $interpreter) use ($response, $output_headers) {
                            return $interpreter->response($response->getBody()->getContents(), $outputHeaders);
                        }
                    );
                }
            );
    }

    /**
     * @param string $name
     * @param array $arguments
     * @param array $options
     * @param mixed $inputHeaders
     * @return PromiseInterface
     */
    private function prepareRequest($name, array $arguments, array $options = null, $inputHeaders = null)
    {
        return $this->interpreter->then(
            function (Interpreter $interpreter) use ($name, $arguments, $options, $inputHeaders) {
                $request = $interpreter->request($name, $arguments, $options, $inputHeaders);
                $headers = $this->prepareHeaders($request['Version'], $request['SoapAction'], $request['Envelope']);
                return [$request['Endpoint'], $headers, $request['Envelope']];
            }
        );

    }

    /**
     * @param string $version
     * @param string $soapAction
     * @param string $body
     * @return array
     *
     * @link https://www.w3.org/TR/soap12-part0/#L4697
     */
    private function prepareHeaders($version, $soapAction, $body)
    {
        $headers = [
            'Content-Length' => strlen($body)
        ];

        if ($version === '1') {
            $headers['SOAPAction'] = $soapAction;
            $headers['Content-Type'] = 'text/xml; charset="utf-8"';
        } else {
            // SOSPAction header is removed in SOAP 1.2
            // Content-type header should be "application/soap+xml"
            $headers['Content-Type'] = 'application/soap+xml; charset="utf-8"';
        }

        return $headers;
    }
}