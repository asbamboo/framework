<?php
namespace asbamboo\framework\kernel;

use asbamboo\di\ContainerInterface;
use asbamboo\http\ServerRequest;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\di\ServiceMapping;
use asbamboo\router\Router;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\router\RouteCollection;
use asbamboo\framework\Constant;
use asbamboo\framework\config\RouterConfig;
use asbamboo\database\Factory;
use asbamboo\framework\config\DbConfig;
use asbamboo\session\Session;
use asbamboo\framework\template\Template;
use asbamboo\http\Response;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\security\gurad\authorization\Authenticator;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\gurad\authorization\AuthenticatorInterface;
use asbamboo\http\ResponseInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\router\RouterInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月23日
 */
abstract class HttpKernel extends Kernel
{

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::run()
     */
    public function run(bool $debug = false) : KernelInterface
    {
        try{
            parent::run($debug);

            /**
             *
             * @var RouterInterface $Router
             * @var ServerRequestInterface $Request
             * @var ResponseInterface $Response
             * @var AuthenticatorInterface $Authenticator
             * @var UserTokenInterface $UserToken
             */
            $Request        = $this->container->get(Constant::KERNEL_REQUEST);
            $Router         = $this->container->get(Constant::KERNEL_ROUTER);
            $Authenticator  = $this->container->get(Constant::KERNEL_GURAD_AUTHENTICATOR);
            $UserToken      = $this->container->get(Constant::KERNEL_USER_TOKEN);
            $Authenticator->validate($UserToken->getUser(), $Request);
            $Response       = $Router->matchRequest($Request);
            $Response->send();
        }catch(\Throwable $e){
            $this->container->get(Constant::KERNEL_EXCEPTION_HANDLER)->setException($e)->print();
        }

        return $this;
    }

    /**
     * 初始化服务容器
     *
     * @return ContainerInterface
     */
    protected function initContainer() : ContainerInterface
    {
        parent::initContainer();

        $this->container->get(Constant::KERNEL_ROUTER_CONFIG)->configure();

        return $this->container;
    }
}