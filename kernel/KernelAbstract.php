<?php
namespace asbamboo\framework\kernel;

use asbamboo\di\Container;
use asbamboo\di\ContainerInterface;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\di\ServiceMapping;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\framework\config\ConfigInterface;

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
        $ServiceMappingCollection   = new ServiceMappingCollection();
        $this->Container            = new Container($ServiceMappingCollection);

        $this->registerConfigs($ServiceMappingCollection);
        $this->Container->set(KernelInterface::class, $this);

        return $this->Container;
    }

    /**
     *
     * @param ServiceMappingCollectionInterface $ServiceMappingCollection
     */
    protected function registerConfigs(ServiceMappingCollectionInterface $ServiceMappingCollection) : void
    {
        $configs            = include $this->getConfigPath();
        $configure_services = [];
        foreach($configs AS $key => $config){
            if(ctype_digit((string) $key) == false && !isset($config['id'])){
                $config['id']   = $key;
            }
            if(!isset($config['class']) && class_exists($key)){
                $config['class']    = $key;
            }
            $ServiceMappingCollection->add(new ServiceMapping($config));
            if(in_array(ConfigInterface::class, class_implements($config['class']))){
                $configure_services[] = $config['id'];
            }
        }
        foreach($configure_services AS $configure_service){
            $this->Container->get($configure_service)->configure();
        }
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