<?php
namespace asbamboo\framework\_test\fixtures\controller;

// use asbamboo\http\Stream;
// use asbamboo\http\Response;
use asbamboo\framework\controller\ControllerAbstract;

class MultiWord extends ControllerAbstract
{
    public function mutilIndex($p1, $p2)
    {
        return $this->view([
            'p1' => $p1,
            'p2' => $p2,
        ], 'home/index.html.tpl');
        //         $Stream   = new Stream('php://temp', 'w+b');
        //         $Stream->write("test kernel route. p1:{$p1}, p2:{$p2}");
        //         return new Response($Stream);
    }
}