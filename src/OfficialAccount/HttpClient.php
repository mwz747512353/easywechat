<?php

declare(strict_types=1);

namespace EasyWeChat\OfficialAccount;

use EasyWeChat\Kernel\Traits\AccessTokenHttpClientDecorator;

class HttpClient implements \EasyWeChat\OfficialAccount\Contracts\HttpClient
{
    use AccessTokenHttpClientDecorator;

    protected array $defaultOptions = [
        'base_uri' => 'https://api.weixin.qq.com/',
    ];
}