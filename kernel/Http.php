<?php
namespace asbamboo\framework\kernel;

use asbamboo\router\RouterInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\http\ResponseInterface;
use asbamboo\security\gurad\authorization\AuthenticatorInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\framework\exception\HandlerInterface;

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
             * @var ResponseInterface $Response
             * @var AuthenticatorInterface $Authenticator
             * @var UserTokenInterface $UserToken
             */
            $Request        = $Kernel->getContainer()->get(ServerRequestInterface::class);
            $Router         = $Kernel->getContainer()->get(RouterInterface::class);
            $Authenticator  = $Kernel->getContainer()->get(AuthenticatorInterface::class);
            $UserToken      = $Kernel->getContainer()->get(UserTokenInterface::class);
            $Authenticator->validate($UserToken->getUser(), $Request);
            $Response       = $Router->matchRequest($Request);
            $Response->send();
        }catch(\Throwable $e){
            $Kernel->getContainer()->get(HandlerInterface::class)->setException($e)->print();
        }
    }
}