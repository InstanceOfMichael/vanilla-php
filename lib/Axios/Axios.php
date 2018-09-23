<?php

namespace Axios;

// http://php.net/manual/en/curl.examples.php
class Axios {
    public $baseUrl;
    public $headers;
    public $curlOptions;
    
    public function setBaseUrl(string $baseUrl = null) {
        $this->baseUrl = $baseUrl;
        $this->headers = [];
        $this->curlOptions = [];
    }

    public function setHeader($name, $value) {
        $this->headers[strtolower($name)] = $value;
    }

    public function setCurlOption($name, $value) {
        $this->curlOptions[$name] = $value;
    }

    public function get(string $url, array $options = []): AxiosResponse {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        
        foreach ($this->curlOptions as $name => $value) {
            curl_setopt($ch, $name, $value);
        }

        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $body = substr($output, $header_size);
        $headers = array_reduce(explode("\n", $headers), function(array $carry, string $str) {
            $str = trim($str);
            if ($str && ($colon = strpos($str, ':')) && $colon !== false) {
                $key = trim(substr(strtolower($str), 0, $colon));
                $value = trim(substr($str, $colon + 1));
                $carry[$key] = $value;
                // print_r(compact('colon', 'str', 'key', 'value'));
            }
            return $carry;
        }, []);

        curl_close($ch);
        return new AxiosResponse($body, $status, $headers);
    }
}
