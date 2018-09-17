<?php
namespace asbamboo\framework\_test\config;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\Constant;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\router\RouteCollection;
use asbamboo\di\Container;
use asbamboo\framework\config\RouterConfig;
use asbamboo\di\ServiceMapping;
use asbamboo\router\RouterInterface;

class RouterConfigTest extends TestCase
{
    public function testConfigure()
    {
        $ServiceMappings    = new ServiceMappingCollection();
        $Container          = new Container($ServiceMappings);
//         $ServiceMappings->add(new ServiceMapping(['id' => 'kernel.route.collection', 'class' => RouteCollection::class]));

        $RouterConfig       = new RouterConfig();
        $RouterConfig->setContainer($Container);
        $RouterConfig->configure();

        $RouterConfig       = new RouterConfig([
            ['id' => 'test1', 'path' => '/test1' , 'callback' => 'asbamboo\\framework\\_test\\config\\RouteClass:index'],
            ['id' => 'test2', 'path' => '/test2' , 'callback' => 'asbamboo\\framework\\_test\\config\\RouteClass:index', 'default_params' => ['def_param' => 1], 'options' => ['dev'=>true]],
        ]);
        $RouterConfig->setContainer($Container);
        $RouterConfig->configure();

        $this->assertTrue($Container->get(RouterInterface::class)->getRouteCollection()->has('test1'));
        $this->assertTrue($Container->get(RouterInterface::class)->getRouteCollection()->has('test2'));
        $this->assertEquals(2, $Container->get(RouterInterface::class)->getRouteCollection()->count());
    }
}

class RouteClass
{
    public function index($def_param = 0)
    {

    }
}