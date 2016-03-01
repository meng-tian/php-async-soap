<?php

namespace Meng\AsyncSoap;

interface SoapClientInterface
{
    /**
     * Magic method to simplify SOAP call. This method is asynchronous.
     *
     * @param string $name Operation name
     * @param array $arguments Operation arguments
     * @return Promise instance with a then method
     */
    public function __call($name, $arguments);

    /**
     * Synchronous SOAP call.
     *
     * @param string $name Operation name
     * @param array $arguments Operation arguments
     * @param array $options Options
     * @param mixed $inputHeaders Input SOAP headers
     * @param array $output_headers Output SOAP headers
     * @return mixed
     */
    public function call($name, array $arguments, array $options = null, $inputHeaders = null, array &$output_headers = null);

    /**
     * Asynchronous SOAP call.
     *
     * @param string $name Operation name
     * @param array $arguments Operation arguments
     * @param array $options Options
     * @param mixed $inputHeaders Input SOAP headers
     * @param array $output_headers Output SOAP headers
     * @return Promise instance with a then method
     */
    public function callAsync($name, array $arguments, array $options = null, $inputHeaders = null, array &$output_headers = null);
}