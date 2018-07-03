<?php
namespace asbamboo\framework\kernel;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\_test\fixtures\HttpKernel;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月23日
 */
class HttpKernelTest extends TestCase
{
    public function testRun()
    {
        $_SERVER['REQUEST_URI'] = '/';

        $kernel = new HttpKernel();
        $this->assertNotEmpty($kernel->run());
    }
}