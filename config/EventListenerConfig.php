<?php
namespace asbamboo\framework\config;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\event\EventListener;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月3日
 */
class EventListenerConfig
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
    public function configure()
    {
        foreach($this->configs AS $config)
        {
            foreach($config['construct_params'] AS $key => $param){
                if(is_string($param) && strncmp($param, '@', 1) === 0){
                    $server_id                          = substr($param, 1);
                    $config['construct_params'][$key]   = $this->Container->get($server_id);
                }
            }
            EventListener::instance()->set($config['name'], $config['class'], $config['method'], $config['construct_params']);
        }
    }
}