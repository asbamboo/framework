<?php
namespace asbamboo\framework\kernel;

use asbamboo\router\RouterInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\gurad\authorization\AuthenticatorInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\framework\exception\HandlerInterface;
use asbamboo\event\EventScheduler;
use asbamboo\framework\Event;
use asbamboo\router\RouteInterface;
use asbamboo\di\exception\NotFoundExceptionInterface;
use asbamboo\framework\Constant;
use asbamboo\router\Route;

/**
 * 通过http请求，访问程序是，从这个类的实例开始
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月6日
 */
class Http implements ApplicationInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\ApplicationInterface::run()
     */
    public function run(KernelInterface $Kernel) : void
    {
        try{
            /**
             *
             * @var RouterInterface $Router
             * @var ServerRequestInterface $Request
             * @var \asbamboo\http\ResponseInterface $Response
             * @var AuthenticatorInterface $Authenticator
             * @var UserTokenInterface $UserToken
             */
            $Request        = $Kernel->getContainer()->get(ServerRequestInterface::class);
            $Router         = $Kernel->getContainer()->get(RouterInterface::class);
            $Authenticator  = $Kernel->getContainer()->get(AuthenticatorInterface::class);
            $UserToken      = $Kernel->getContainer()->get(UserTokenInterface::class);
            EventScheduler::instance()->trigger(Event::KERNEL_HTTP_REQUEST, [$this, $Kernel]);
            $Authenticator->validate($UserToken->getUser(), $Request);
            $Route          = $this->matchCurrentRoute($Kernel);
            $Response       = $Router->call($Route, $Request);
            $Response->send();
            EventScheduler::instance()->trigger(Event::KERNEL_HTTP_RESPONSE, [$this, $Kernel, $Response]);
        }catch(\Throwable $e){
            $Kernel->getContainer()->get(HandlerInterface::class)->setException($e)->print();
        }
    }

    /**
     * 传入当前request匹配的route
     *
     * @param KernelInterface $Kernel
     * @param RouteInterface $Route
     * @return RouteInterface
     */
    private function matchCurrentRoute(KernelInterface $Kernel) : RouteInterface
    {
        /**
         * 匹配route
         *
         * @var RouterInterface $Router
         */
        $Request        = $Kernel->getContainer()->get(ServerRequestInterface::class);
        $Router         = $Kernel->getContainer()->get(RouterInterface::class);
        $Route          = $Router->match($Request);

        /**
         * 重置Route
         *  - 未设置默认参数（default_params）的参数如果在container中找到相应的服务，那么配置为默认参数。
         *  - options属性，添加is_current = true选项表示当前匹配的路由.
         *
         * @var \ReflectionParameter $RefParam
         * @var array $RefParams
         */
        $callback       = $Route->getCallback();
        $default_params = $Route->getDefaultParams();
        $options        = $Route->getOptions();

        if(is_array($callback)){
            $r  = new \ReflectionMethod(implode('::', [get_class($callback[0]), $callback[1]]));
        }else{
            $r  = new \ReflectionFunction($callback);
        }

        $RefParams    = $r->getParameters();
        foreach($RefParams AS $RefParam){
            $n                  = $RefParam->getName();
            if(isset($default_params[$n])){
                continue;
            }
            if($RefParamClass = $RefParam->getClass()){
                try{
                    $ref_param_class    = $RefParamClass->getName();
                    $default_params[$n] = $Kernel->getContainer()->get($ref_param_class);
                }catch(NotFoundExceptionInterface $e){
                    // container 如果没有这个服务，那么 default params 不改变。
                }
            }
        }
        $options[Constant::IS_CURRENT_ROUTE]    = true;

        $Route          = new Route($Route->getId(), $Route->getPath(), $callback, $default_params, $options);
        $Router->getRouteCollection()->add($Route);

        /**
         * 返回route
         */
        return $Route;
    }
}