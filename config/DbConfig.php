<?php
namespace asbamboo\framework\config;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\framework\Constant;
use asbamboo\database\Connection;

/**
 * 路由配置
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月5日
 */
class DbConfig
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
        /**
         * @var Factory $Factory
         */
        $Factory        = $this->Container->get(Constant::KERNEL_DB);
        
        /**
         * 如果只配置了一个数据库，增加数组纬度。
         */
        if(isset($this->configs['connection']) && isset($this->configs['metadata'])){
            $this->configs  = ['default' => $this->configs];
        }

        /**
         * 如果没有default离开连接，默认$this->configs当前索引位置的数据库连接是default
         */
        if(!in_array('default', array_keys($this->configs))){
            $this->configs['default']   = current($this->configs);            
        }
        
        /**
         * 添加到db factory
         */
        foreach($this->configs AS $id => $config){
            $Connection = Connection::create($config['connection'], $config['metadata']['path'], $config['metadata']['type'], $config['is_dev']);
            $Factory->addConnection($Connection, $id);
        }
    }
}