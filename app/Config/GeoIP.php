<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GeoIP extends BaseConfig
{
    public string $ipstack_api_key = '2584ea70617bbbed5565afb98718cf2b';
    public string $ipstack_api_url = 'http://api.ipstack.com/';
}
