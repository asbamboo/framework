<?php
namespace asbamboo\framework\_test\exeception;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\_test\fixtures\TestKernel;
use asbamboo\framework\kernel\Http;

class HandlerTest extends TestCase
{
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
