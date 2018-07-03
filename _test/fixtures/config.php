<?php
use asbamboo\router\RouteCollection;
use asbamboo\router\Route;
use asbamboo\router\Router;
use asbamboo\http\Stream;
use asbamboo\http\Response;

$RouteCollection    = new RouteCollection();
// $RouteCollection->add(new Route('home', '/', function($p1, $p2){
//     return "test kernel route. p1:{$p1}, p2:{$p2}";
// }));
$RouteCollection->add(new Route('home', '/', [new Home(), 'index']));


return [
    'kernel.router' => ['class' => Router::class, 'init_params' => ['RouteCollection' => $RouteCollection]],
];

class Home
{
    public function index($p1, $p2)
    {
        $Stream   = new Stream('php://temp', 'w+b');
        $Stream->write("test kernel route. p1:{$p1}, p2:{$p2}");
        return new Response($Stream);
    }
}