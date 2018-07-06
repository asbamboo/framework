<?php
namespace asbamboo\framework\_test\kernel;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\_test\fixtures\HttpKernel;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月23日
 */
class HttpKernelTest extends TestCase
{
    public function testRun1()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $get                    = $_GET;
        $_GET['p1']             = '1';

        $kernel = new HttpKernel();
        ob_start();
        $kernel     = $kernel->run();
        $data       = ob_get_contents();
        ob_end_clean();
//         echo $data;
        $this->assertNotEmpty($data);
        $_GET               = $get;
        
    }
    
    public function testRun2()
    {
        $_SERVER['REQUEST_URI'] = '/multi_word';
        $get                    = $_GET;
        $_GET['p1']             = '1';
        $_GET['p2']             = '2';
        
        $kernel = new HttpKernel();
        ob_start();
        $kernel     = $kernel->run();
        $data       = ob_get_contents();
        ob_end_clean();
//         echo $data;
        $this->assertNotEmpty($data);
        $_GET               = $get;
    }
}