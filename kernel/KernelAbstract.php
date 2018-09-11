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
abstract class KernelAbstract implements KernelInterface
{
    /**
     * 容器
     * @var ContainerInterface
     */
    protected $Container;

    /**
     * 是否时debug模式
     *
     * @var bool
     */
    protected $is_debug;

    /**
     *
     * @param bool $is_debug
     */
    public function __construct(bool $is_debug)
    {
        $this->setIsDebug($is_debug);
        $this->boot();
    }

    /**
     * 设置是否以debug模式运行
     *
     * @param bool $is_debug
     */
    public function setIsDebug(bool $is_debug) : self
    {
        $this->is_debug = $is_debug;
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
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::getContainer()
     */
    public function getContainer() : ContainerInterface
    {
        return $this->Container;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::boot()
     */
    public function boot() : KernelInterface
    {
        $this->initContainer();
        return $this;
    }

    /**
     * 初始化服务容器
     *
     * @return ContainerInterface
     */
    protected function initContainer() : ContainerInterface
    {
        $ServiceMappings    = $this->registerConfigs();
        $this->Container    = new Container($ServiceMappings);

        $this->Container->get(Constant::KERNEL_DB_CONFIG)->configure();
        $this->Container->get(Constant::KERNEL_EVENT_LISTENER_CONFIG)->configure();
        $this->Container->get(Constant::KERNEL_ROUTER_CONFIG)->configure();
        $this->Container->set(Constant::KERNEL, $this);

        return $this->Container;
    }

    /**
     *  注册配置信息
     *
     * @return ServiceMappingCollectionInterface
     */
    protected function registerConfigs() : ServiceMappingCollectionInterface
    {
        $ServiceMappings    = new ServiceMappingCollection();
        $default_configs    = [
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
        $custom_configs     = include $this->getConfigPath();
        $configs            = array_replace_recursive($default_configs, $custom_configs);
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