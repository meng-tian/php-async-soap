<?php

namespace Meng\AsyncSoap;

interface SoapClientInterface
{
    /**
     * Magic method to simplify SOAP call. This method is asynchronous.
     *
     * @param string $name              The name of the SOAP function to call.
     * @param array  $arguments         An array of the arguments to pass to the function.
     * @return mixed                    A promise object whose behavior conforms to https://promisesaplus.com.
     */
    public function __call($name, $arguments);

    /**
     * Synchronous SOAP call.
     *
     * @param string $name              The name of the SOAP function to call.
     * @param array  $arguments         An array of the arguments to pass to the function.
     * @param array  $options           An associative array of options to pass to the client.
     *                                  The location option is the URL of the remote Web service.
     *                                  The uri option is the target namespace of the SOAP service.
     *                                  The soapaction option is the action to call.
     * @param mixed  $inputHeaders      An array of headers to be sent along with the SOAP request.
     * @param array  $outputHeaders     If supplied, this array will be filled with the headers from the SOAP response.
     * @return mixed                    The eventual value of promise returned from callAsync.
     */
    public function call($name, array $arguments, array $options = null, $inputHeaders = null, array &$outputHeaders = null);

    /**
     * Asynchronous SOAP call.
     *
     * @param string $name              The name of the SOAP function to call.
     * @param array  $arguments         An array of the arguments to pass to the function.
     * @param array  $options           An associative array of options to pass to the client.
     *                                  The location option is the URL of the remote Web service.
     *                                  The uri option is the target namespace of the SOAP service.
     *                                  The soapaction option is the action to call.
     * @param mixed  $inputHeaders      An array of headers to be sent along with the SOAP request.
     * @param array  $outputHeaders     If supplied, this array will be filled with the headers from the SOAP response.
     * @return mixed                    A promise object whose behavior conforms to https://promisesaplus.com.
     */
    public function callAsync($name, array $arguments, array $options = null, $inputHeaders = null, array &$outputHeaders = null);
}