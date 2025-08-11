<?php
/**
 * @file   : ArrayHelperTest.php
 * @time   : 15:49
 * @date   : 2021/9/8
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\data;

use Hiland\Web\HttpUtilHelper;
use PHPUnit\Framework\TestCase;

class HttpUtilHelperTest extends TestCase
{
    /**
     * 测试 stripDomain 方法的正常情况
     */
    public function testStripDomainHappyPath(): void
    {
        $this->assertEquals('/path/to/resource?query=1#fragment', HttpUtilHelper::stripDomain('http://example.com/path/to/resource?query=1#fragment'));
        $this->assertEquals('/another/path', HttpUtilHelper::stripDomain('https://another-example.org:8080/another/path'));
        $this->assertEquals('/', HttpUtilHelper::stripDomain('ftp://ftp.example.com/'));
    }

    /**
     * 测试 stripDomain 方法的边界情况
     */
    public function testStripDomainEdgeCases(): void
    {
        // 空字符串
        $this->assertEquals('/', HttpUtilHelper::stripDomain(''));
        // 无效 URL
        $this->assertEquals('invalid-url', HttpUtilHelper::stripDomain('invalid-url'));
        // 仅域名
        $this->assertEquals('/', HttpUtilHelper::stripDomain('http://example.com'));
        // 仅路径
        $this->assertEquals('/path/to/resource', HttpUtilHelper::stripDomain('/path/to/resource'));
        // 仅查询参数
        $this->assertEquals('?query=1', HttpUtilHelper::stripDomain('?query=1'));
        // 仅片段标识符
        $this->assertEquals('#fragment', HttpUtilHelper::stripDomain('#fragment'));
    }

    /**
     * 测试 getDomain 方法的正常情况
     */
    public function testGetDomainHappyPath(): void
    {
        $this->assertEquals('http://example.com', HttpUtilHelper::getDomain('http://example.com/path/to/resource?query=1#fragment'));
        $this->assertEquals('https://another-example.org:8080', HttpUtilHelper::getDomain('https://another-example.org:8080/another/path'));
        $this->assertEquals('ftp://ftp.example.com', HttpUtilHelper::getDomain('ftp://ftp.example.com/'));
    }

    /**
     * 测试 getDomain 方法的边界情况
     */
    public function testGetDomainEdgeCases(): void
    {
        // 空字符串
        $this->assertEquals('', HttpUtilHelper::getDomain(''));
        // 无效 URL
        $this->assertEquals('', HttpUtilHelper::getDomain('invalid-url'));
        // 无协议 URL
        $this->assertEquals('', HttpUtilHelper::getDomain('example.com'));
        // 无路径 URL
        $this->assertEquals('http://example.com', HttpUtilHelper::getDomain('http://example.com'));
        // 无端口 URL
        $this->assertEquals('http://example.com:80', HttpUtilHelper::getDomain('http://example.com:80'));

        $this->assertEquals('cdn.site.net', HttpUtilHelper::getDomain('//cdn.site.net/static/js/app.js?ver=1.2'));
    }
}
