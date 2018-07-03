<?php
use asbamboo\router\loader\LoaderByArray;
use asbamboo\framework\_test\fixtures\controller\Home;

// $RouteCollection    = new RouteCollection();
// $RouteCollection->add(new Route('home', '/', function($p1, $p2){
//     return "test kernel route. p1:{$p1}, p2:{$p2}";
// }));
// $RouteCollection->add(new Route('home', '/', [new Home(), 'index']));
return (new LoaderByArray)->parse([
    ['id' => 'home', 'path' => '/' , 'callback' => Home::class . ':index'],
]);