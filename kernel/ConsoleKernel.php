<?php
namespace asbamboo\framework\kernel;

use asbamboo\di\ServiceMappingCollection;
use asbamboo\di\ServiceMapping;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\framework\Constant;
use asbamboo\database\Factory;
use asbamboo\framework\config\DbConfig;
use asbamboo\console\Processor;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\framework\config\RouterConfig;
use asbamboo\router\RouteCollection;
use asbamboo\router\Router;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月31日
 */
abstract class ConsoleKernel extends Kernel
{
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
        parent::run();

        $this->container->get(Constant::KERNEL_CONSOLE)->exec();

        return $this;
    }

    /**
     *  注册配置信息
     *
     * @return ServiceMappingCollectionInterface
     */
    protected function registerConfigs() : ServiceMappingCollectionInterface
    {
        $ServiceMappings                            = new ServiceMappingCollection();
        $default_configs                            = [
            Constant::KERNEL_DB                     => ['class' => Factory::class],
            Constant::KERNEL_DB_CONFIG              => ['class' => DbConfig::class],
            Constant::KERNEL_CONSOLE                => ['class' => Processor::class],
            Constant::KERNEL_ROUTER_CONFIG          => ['class' => RouterConfig::class],
            Constant::KERNEL_ROUTE_COLLECTION       => ['class' => RouteCollection::class],
            Constant::KERNEL_ROUTER                 => ['class' => Router::class, 'init_params' => ['RouteCollection' => '@' . Constant::KERNEL_ROUTE_COLLECTION]],
            Constant::KERNEL_EVENT_LISTENER_CONFIG  => ['class' => EventListenerConfig::class],
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