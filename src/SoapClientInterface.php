<?php

namespace Meng\AsyncSoap;

interface SoapClientInterface
{
    /**
     * Synchronous SOAP call
     *
     * @param string $name
     * @param array $arguments
     * @param array $options
     * @param mixed $inputHeaders
     * @param array $output_headers
     * @return mixed
     */
    public function call($name, array $arguments, array $options = null, $inputHeaders = null, array &$output_headers = null);

    /**
     * Asynchronous SOAP call
     *
     * @param string $name
     * @param array $arguments
     * @param array $options
     * @param mixed $inputHeaders
     * @param array $output_headers
     * @return a promise instance with a then method
     */
    public function callAsync($name, array $arguments, array $options = null, $inputHeaders = null, array &$output_headers = null);
}