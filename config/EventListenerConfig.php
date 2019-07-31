<?php
namespace asbamboo\framework\config;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\event\EventListener;

/**
 * 设置事件监听器
 *  - $config应该是一个这样的数组
 *      [
 *          ['name' => 'test_listener1', 'class' => get_class($TestListenerClass1), 'method' => 'onAdd', 'construct_params' => [1, 1]],
 *          ['name' => 'test_listener2', 'class' => get_class($TestListenerClass1), 'method' => 'onMulti', 'construct_params' => [1, 1]],
 *      ]
 *  - name 监听器 id
 *  - class 监听器执行的class
 *  - method 监听器执行的method
 *  - construct_params 监听器初始化时传入的参数
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月3日
 */
class EventListenerConfig implements ConfigInterface
{
    use ContainerAwareTrait;

    /**
     *
     * @var array
     */
    private $configs    = [];

    /**
     *
     * @param array $configs
     */
    public function __construct(array $configs = [])
    {
        $this->configs  = $configs;
    }

    /**
     *
     */
    public function configure() : void
    {
        foreach($this->configs AS $config)
        {
            if(isset($config['construct_params']) && is_array($config['construct_params'])){
                foreach($config['construct_params'] AS $key => $param){
                    if(is_string($param) && strncmp($param, '@', 1) === 0){
                        $server_id                          = substr($param, 1);
                        $config['construct_params'][$key]   = $this->Container instanceof $server_id ? $this->Container : $this->Container->get($server_id);
                    }
                }
            }
            EventListener::instance()->set($config['name'], $config['class'], $config['method'], $config['construct_params'] ?? [], $config['priority'] ?? null);
        }
    }
}