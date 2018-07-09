<?php

namespace Districts\Service;


class CurlMultiManager implements DataTransferInterface
{
    private $multiHandler;
    private $curlHandlers = [];
    private $results = [];
    private $total = 0;

    public function init()
    {
        $this->multiHandler = curl_multi_init();
        $this->curlHandlers = [];
        $this->results = [];
        $this->total = 0;
    }

    public function create(string $url, array $headers = [], array $options = [])
    {
        if ($this->multiHandler === null) {
            $this->multiHandler = curl_multi_init();
        }
        if (count($options) < 1) {
            $options = [
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true
            ];
        }
        if (count($headers) > 0) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        $this->curlHandlers[$this->total] = curl_init($url);
        curl_setopt_array($this->curlHandlers[$this->total], $options);
        curl_multi_add_handle($this->multiHandler, $this->curlHandlers[$this->total]);
        $this->total++;
    }

    public function execute()
    {
        $active = null;

        do {
            $mrc = curl_multi_exec($this->multiHandler, $active);
        } while ($mrc === CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc === CURLM_OK) {
            if (curl_multi_select($this->multiHandler) === -1) {
                usleep(1);
            }

            do {
                $mrc = curl_multi_exec($this->multiHandler, $active);
            } while ($mrc === CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($this->curlHandlers as $number => $ch) {
            $this->results[$number] = curl_multi_getcontent($ch);
            curl_multi_remove_handle($this->multiHandler, $ch);
        }

        curl_multi_close($this->multiHandler);
        $this->multiHandler = null;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}