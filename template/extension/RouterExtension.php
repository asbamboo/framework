<?php
namespace asbamboo\framework\template\extension;

use asbamboo\template\Extension;
use asbamboo\template\Functions;
use asbamboo\framework\Constant;
use asbamboo\router\RouterInterface;
use asbamboo\framework\template\Template;

/**
 * template 模板中使用的扩展[路由相关]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年8月30日
 */
class RouterExtension extends Extension
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
            new Functions('path', [$this, 'path']),
        ];
    }

    /**
     *
     * @param string $route_id
     * @param array|null $params
     * @return string
     */
    public function path($route_id, $params = null) : string
    {
        /**
         *
         * @var RouterInterface $Router
         */
        $Router = $this->Template->getContainer()->get(Constant::KERNEL_ROUTER);
        return $Router->generateUrl($route_id, $params);
    }
}