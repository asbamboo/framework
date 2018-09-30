<?php
namespace asbamboo\framework\template\extension;

use asbamboo\template\Extension;
use asbamboo\framework\template\Template;
use asbamboo\template\ExtensionGlobalsInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\framework\kernel\KernelInterface;

/**
 * 添加模板使用的全局变量
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月4日
 */
class GlobalExtension extends Extension implements ExtensionGlobalsInterface
{
    /**
     *
     * @var Template
     */
    private $Template;

    /**
     *
     * @param Template $Template
     */
    public function __construct(Template $Template)
    {
        $this->Template = $Template;
    }

    /**
     *
     * @return \asbamboo\framework\template\extension\GlobalExtension[][]|string[][]
     */
    public function getGlobals()
    {
        return ['app'=>[
            'user'      => $this->user(),
            'request'   => $this->request(),
            'is_debug'  => $this->isDebug(),
        ]];
    }

    /**
     * 当前用户
     *
     * @return \asbamboo\security\user\UserInterface
     */
    public function user()
    {
        /**
         *
         * @var UserTokenInterface $UserToken
         */
        $UserToken  = $this->Template->getContainer()->get(UserTokenInterface::class);
        return $UserToken->getUser();
    }

    /**
     *
     * @return \asbamboo\http\ServerRequestInterface
     */
    public function request()
    {
        /**
         *
         * @var ServerRequestInterface  $ServerRequest
         */
        $ServerRequest  = $this->Template->getContainer()->get(ServerRequestInterface::class);
        return $ServerRequest;
    }

    /**
     * 是否时debug模式
     *
     * @return boolean
     */
    public function isDebug()
    {
        /**
         * @var KernelInterface $Kernel
         */
        $Kernel  = $this->Template->getContainer()->get(KernelInterface::class);
        return $Kernel->getIsDebug();
    }
}