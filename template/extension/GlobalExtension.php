<?php
namespace asbamboo\framework\template\extension;

use asbamboo\template\Extension;
use asbamboo\framework\template\Template;
use asbamboo\framework\Constant;
use asbamboo\template\ExtensionGlobalsInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\http\ServerRequestInterface;

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
        $UserToken  = $this->Template->getContainer()->get(Constant::KERNEL_USER_TOKEN);
        return $UserToken->getUser();
    }

    /**
     * 当前请求
     *
     * @return \asbamboo\security\user\UserInterface
     */
    public function request()
    {
        /**
         *
         * @var ServerRequestInterface  $ServerRequest
         */
        $ServerRequest  = $this->Template->getContainer()->get(Constant::KERNEL_REQUEST);
        return $ServerRequest;
    }
}