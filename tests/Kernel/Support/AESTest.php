<?php

declare(strict_types=1);

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\Tests\Kernel\Support;

use EasyWeChat\Kernel\Support\Aes;
use EasyWeChat\Tests\TestCase;

class AESTest extends TestCase
{
    public function testEncrypt()
    {
        $key = 'abcdefghijklmnopabcdefghijklmnop';
        $iv = substr($key, 0, 16);

        $expected = openssl_encrypt('foo', 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        $this->assertSame($expected, Aes::encrypt('foo', $key, $iv, OPENSSL_RAW_DATA));

        $this->assertSame('foo', Aes::decrypt($expected, $key, $iv, OPENSSL_RAW_DATA));
    }

    public function keyCases()
    {
        return [[12], [13], [15], [16], [18], [20], [21], [24], [26], [31], [32], [33]];
    }

    /**
     * @dataProvider keyCases
     */
    public function testValidKey($length)
    {
        try {
            $result = Aes::assertKeyIsValid(str_repeat('x', $length));
            if (in_array($length, [16, 24, 32], true)) {
                $this->assertNull($result);
            } else {
                $this->fail('No expected exception thrown.');
            }
        } catch (\Exception $e) {
            $this->assertSame(sprintf('Key length must be 16, 24, or 32 bytes; got key len (%s).', $length), $e->getMessage());
        }
    }

    public function IvCases()
    {
        return [[12], [13], [15], [16], [18], [20], [21], [24], [26], [31], [32], [33]];
    }

    /**
     * @dataProvider IvCases
     */
    public function testValidateIv($length)
    {
        try {
            $result = Aes::assertIvIsValid(str_repeat('x', $length));
            if (16 === $length) {
                $this->assertNull($result);
            } else {
                $this->fail('No expected exception thrown.');
            }
        } catch (\Exception $e) {
            $this->assertSame('IV length must be 16 bytes.', $e->getMessage());
        }
    }
}
