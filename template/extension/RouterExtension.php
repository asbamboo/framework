<?php
namespace asbamboo\framework\template\extension;

use asbamboo\template\Extension;
use asbamboo\template\Functions;
use asbamboo\router\RouterInterface;
use asbamboo\framework\template\Template;
use asbamboo\router\RouteCollectionInterface;
use asbamboo\framework\Constant;

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
            new Functions('is_current', [$this, 'isCurrent']),
        ];
    }

    /**
     *
     * @param string $route_id
     * @param array|null $params
     * @return string
     */
    public function path(string $route_id, array $params = null) : string
    {
        /**
         *
         * @var RouterInterface $Router
         */
        $Router = $this->Template->getContainer()->get(RouterInterface::class);
        return $Router->generateUrl($route_id, $params);
    }

    /**
     * 判断一个路由是否时当前正在请求的路由
     *
     * @param string $route_id
     * @return boolean
     */
    public function isCurrent(string $route_id) : bool
    {
        /**
         *
         * @var RouteCollectionInterface $RouteCollection
         */
        $RouteCollection    = $this->Template->getContainer()->get(RouterInterface::class)->getRouteCollection();
        return !empty($RouteCollection->get($route_id)->getOptions(Constant::IS_CURRENT_ROUTE));
    }
}