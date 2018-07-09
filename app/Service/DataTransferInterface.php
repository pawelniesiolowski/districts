<?php

namespace Districts\Service;


interface DataTransferInterface
{
    public function init();
    public function create(string $url, array $headers = [], array $options = []);
    public function execute();
    public function getResults(): array;
}