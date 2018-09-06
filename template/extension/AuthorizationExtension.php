<?php
namespace asbamboo\framework\template\extension;

use asbamboo\framework\template\Template;
use asbamboo\template\Extension;
use asbamboo\template\Functions;
use asbamboo\framework\Constant;
use asbamboo\security\user\token\UserTokenInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月4日
 */
class AuthorizationExtension extends Extension
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
     * {@inheritDoc}
     * @see Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new Functions('has_roles', [$this, 'hasRoles']),
        ];
    }

    /***
     * 判断当前用户是否包含这些角色
     *
     * @param string ...$roles
     * @return boolean
     */
    public function hasRoles(string ...$roles)
    {
        /**
         *
         * @var UserTokenInterface $UserToken
         */
        $UserToken      = $this->Template->getContainer()->get(Constant::KERNEL_USER_TOKEN);
        $user_roles     = $UserToken->getUser()->getRoles();
        $diffed_roles   = array_diff($user_roles, $roles);
        return count($user_roles) > count($diffed_roles);
    }
}