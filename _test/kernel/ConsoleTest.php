<?php
namespace asbamboo\framework\_test\kernel;

use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function testRun()
    {
        ob_start();
        include '/www/asbamboo/framework/_test/fixtures/bin/console';
        $data   = ob_get_contents();
        ob_end_clean();
        $this->assertContains('asbamboo:console:help', $data);
    }
}