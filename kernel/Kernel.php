<?php
namespace asbamboo\framework\kernel;

use asbamboo\autoload\Autoload;
use asbamboo\di\Container;
use asbamboo\di\ContainerInterface;
use asbamboo\framework\Constant;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\di\ServiceMapping;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\database\Factory;
use asbamboo\framework\config\DbConfig;
use asbamboo\console\Processor;
use asbamboo\framework\config\RouterConfig;
use asbamboo\router\RouteCollection;
use asbamboo\router\Router;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\http\ServerRequest;
use asbamboo\session\Session;
use asbamboo\security\gurad\authorization\Authenticator;
use asbamboo\security\user\provider\MemoryUserProvider;
use asbamboo\security\user\token\UserToken;
use asbamboo\security\user\login\BaseLogin;
use asbamboo\security\user\login\BaseLogout;
use asbamboo\framework\template\Template;
use asbamboo\framework\exception\Handler;

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
     * 是否时debug模式
     *
     * @var bool
     */
    protected $is_debug;

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
    public function run(bool $debug = false) : KernelInterface
    {
        /**
         * 是否开启debug模式
         */
        $this->is_debug = $debug;

        /**
         * 启用引导
         */
        $this->boot();

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::getIsDebug()
     */
    public function getIsDebug() : bool
    {
        return $this->is_debug;
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
        $this->container->get(Constant::KERNEL_EVENT_LISTENER_CONFIG)->configure();
        $this->container->set(Constant::KERNEL, $this);

        return $this->container;
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
            Constant::KERNEL_REQUEST                => ['class' => ServerRequest::class],
            Constant::KERNEL_SESSION                => ['class' => Session::class],
            Constant::KERNEL_ROUTER_CONFIG          => ['class' => RouterConfig::class],
            Constant::KERNEL_ROUTE_COLLECTION       => ['class' => RouteCollection::class],
            Constant::KERNEL_ROUTER                 => ['class' => Router::class, 'init_params' => ['RouteCollection' => '@' . Constant::KERNEL_ROUTE_COLLECTION]],
            Constant::KERNEL_USER_PROVIDER          => ['class' => MemoryUserProvider::class],
            Constant::KERNEL_USER_TOKEN             => ['class' => UserToken::class, 'init_params' => ['Session' => '@' . Constant::KERNEL_SESSION]],
            Constant::KERNEL_USER_LOGIN             => [
                                                        'class' => BaseLogin::class,
                                                        'init_params' => [
                                                            'UserProvider'=>'@' . Constant::KERNEL_USER_PROVIDER,
                                                            'UserToken'=>'@' . Constant::KERNEL_USER_TOKEN]
                                                        ],
            Constant::KERNEL_USER_LOGOUT            => ['class' => BaseLogout::class, 'init_params' => ['UserToken'=>'@' . Constant::KERNEL_USER_TOKEN]],
            Constant::KERNEL_GURAD_AUTHENTICATOR    => ['class' => Authenticator::class],
            Constant::KERNEL_TEMPLATE               => [
                                                        'class' => Template::class,
                                                        'init_params' => ['template_dir' => $this->getProjectDir() . DIRECTORY_SEPARATOR . 'view']
                                                        ],
            Constant::KERNEL_EVENT_LISTENER_CONFIG  => ['class' => EventListenerConfig::class],
            Constant::KERNEL_EXCEPTION_HANDLER      => ['class' => Handler::class],
        ];
        $custom_configs                     = include $this->getConfigPath();
        $configs                            = array_replace_recursive($default_configs, $custom_configs);
        foreach($configs AS $key => $config){
            if(ctype_digit((string) $key) == false && !isset($config['id'])){
                $config['id']   = $key;
            }
            $ServiceMappings->add(new ServiceMapping($config));
        }
        return $ServiceMappings;
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