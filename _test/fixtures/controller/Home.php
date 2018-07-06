<?php
namespace asbamboo\framework\_test\fixtures\controller;

// use asbamboo\http\Stream;
// use asbamboo\http\Response;
use asbamboo\framework\controller\ControllerAbstract;

class Home extends ControllerAbstract
{
    public function index($p1, $p2)
    {
        return $this->view([
            'p1' => $p1,
            'p2' => $p2,
        ]);
//         $Stream   = new Stream('php://temp', 'w+b');
//         $Stream->write("test kernel route. p1:{$p1}, p2:{$p2}");
//         return new Response($Stream);
    }
}