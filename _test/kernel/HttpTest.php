<?php
namespace asbamboo\framework\_test\kernel;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\_test\fixtures\TestKernel;
use asbamboo\framework\kernel\Http;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月23日
 */
class HttpTest extends TestCase
{
    public function testRun1()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $get                    = $_GET;
        $_GET['p1']             = '1';

        ob_start();
        $TestKernel     = new TestKernel(true);
        $TestKernel     = (new Http())->run($TestKernel);
        $data           = ob_get_contents();
        ob_end_clean();
//         echo $data;
        $this->assertNotEmpty($data);
        $this->assertContains('test kernel', $data);
        $_GET               = $get;
//         exit;
    }

    public function testRun2()
    {
        $_SERVER['REQUEST_URI'] = '/multi_word';
        $get                    = $_GET;
        $_GET['p1']             = '1';
        $_GET['p2']             = '2';

        ob_start();
        $TestKernel     = new TestKernel(true);
        $TestKernel     = (new Http())->run($TestKernel);
        $data           = ob_get_contents();
        ob_end_clean();
        $this->assertContains('test kernel',$data);
        $_GET               = $get;
    }

    public function test404()
    {
        $_SERVER['REQUEST_URI'] = '/404';
        ob_start();
        $TestKernel     = new TestKernel(true);
        $TestKernel     = (new Http())->run($TestKernel);
        $data           = ob_get_contents();
        ob_end_clean();
//                 echo $data;
        $this->assertContains('404', $data);
    }
}