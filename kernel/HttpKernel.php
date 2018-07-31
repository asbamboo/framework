<?php
namespace asbamboo\framework\kernel;

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
    public function run() : KernelInterface
    {
        parent::run();

        /**
         *
         * @var Router $Router
         * @var ServerRequest $Request
         * @var Response $Response
         */
        $Request    = $this->container->get('kernel.request');
        $Router     = $this->container->get('kernel.router');
        $Response   = $Router->matchRequest($Request);

        echo $Response->getBody();

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

    /**
     *  注册配置信息
     *
     * @return ServiceMappingCollectionInterface
     */
    protected function registerConfigs() : ServiceMappingCollectionInterface
    {
        $ServiceMappings                        = new ServiceMappingCollection();
        $default_configs                        = [
            Constant::KERNEL_DB                 => ['class' => Factory::class],
            Constant::KERNEL_DB_CONFIG          => ['class' => DbConfig::class],
            Constant::KERNEL_REQUEST            => ['class' => ServerRequest::class],
            Constant::KERNEL_ROUTER_CONFIG      => ['class' => RouterConfig::class],
            Constant::KERNEL_ROUTE_COLLECTION   => ['class' => RouteCollection::class],
            Constant::KERNEL_ROUTER             => ['class' => Router::class, 'init_params' => ['RouteCollection' => '@' . Constant::KERNEL_ROUTE_COLLECTION]],
            Constant::KERNEL_TEMPLATE           => ['class' => Template::class],
        ];
        $custom_configs                     = include $this->getConfigPath();
        $configs                            = array_merge($default_configs, $custom_configs);

        foreach($configs AS $key => $config){
            if(ctype_digit((string) $key) == false && !isset($config['id'])){
                $config['id']   = $key;
            }
            $ServiceMappings->add(new ServiceMapping($config));
        }
        return $ServiceMappings;
    }
}