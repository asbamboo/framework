<?php
namespace asbamboo\framework\kernel;

use asbamboo\autoload\Autoload;
use asbamboo\di\Container;
use asbamboo\di\ContainerInterface;
use asbamboo\http\ServerRequest;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\di\ServiceMapping;
use asbamboo\router\Router;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\router\RouteCollection;
use asbamboo\template\Template;
use asbamboo\framework\Constant;
use asbamboo\framework\config\RouterConfig;
use asbamboo\database\Factory;
use asbamboo\framework\config\DbConfig;
use asbamboo\database\Connection;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月23日
 */
abstract class Kernel implements KernelInterface
{
    /**
     * 容器
     * @var ContainerInterface
     */
    protected $container;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::boot()
     */
    public function boot() : KernelInterface
    {
        $this->initAutoload();
        $this->initContainer();
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::run()
     */
    public function run() : KernelInterface
    {
        /**
         * 启用引导
         */
        $this->boot();

        return $this;
    }

    /**
     * 初始化自动加载
     *
     * @return \asbamboo\autoload\Autoload
     */
    private function initAutoload() : Autoload
    {
        return new Autoload();
    }

    /**
     * 初始化服务容器
     *
     * @return ContainerInterface
     */
    protected function initContainer() : ContainerInterface
    {
        $ServiceMappings    = $this->registerConfigs();
        $this->container    = new Container($ServiceMappings);

        $this->container->get(Constant::KERNEL_DB_CONFIG)->configure();
        $this->container->set(Constant::KERNEL, $this);

        return $this->container;
    }
    
    /**
     * 配置文件路径
     *
     * @return string
     */
    abstract function getConfigPath(): string;

    /**
     * 获取项目的根目录
     *
     * @return string
     */
    abstract function getProjectDir(): string;
}