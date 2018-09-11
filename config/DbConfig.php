<?php
namespace asbamboo\framework\config;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\framework\Constant;
use asbamboo\database\Connection;
use asbamboo\database\Factory;

/**
 * 数据库配置
 *  - 数据库配置完成后通过$this->Container->get(Constant::KERNEL_DB)->getManager('connection id')来获取db manager;
 *  - 如果只有一个数据库，可以通过这个方式配置: 此时connection_id = default。
 *      [
 *          'connection'    => [
 *              'driver'    => 'pdo_sqlite',
 *              'path'      =>  dirname(__DIR__) . '/data/db.sqlite',
 *          ],'metadata'    => [
 *              'path'      => dirname(__DIR__) . '/model',
 *              'type'      => 'annotation',
 *          ],'is_dev'      => true,
 *      ]
 *  - 如果是多个数据库，可以通过这个方式配置 db1\db2\...为connection id
 *      [
 *          'db1' =>[
 *              'connection'    => [
 *                  'driver'    => 'pdo_sqlite',
 *                  'path'      =>  dirname(__DIR__) . '/data/db.sqlite',
 *              ],'metadata'    => [
 *                  'path'      => dirname(__DIR__) . '/model',
 *              ]
 *          ],
 *          'db2' =>[
 *              'connection'    => [
 *                  'driver'   => 'pdo_mysql',
 *                  'user'     => 'root',
 *                  'password' => '',
 *                  'dbname'   => 'foo',
 *              ],'metadata'    => [
 *                  'path'      => dirname(__DIR__) . '/model',
 *              ]
 *          ],
 *          ...
 *      ]
 *  - 配置项connection、metadata、is_dev参考https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/configuration.html。
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
    public function configure() : void
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
            if(!$config){
                continue;
            }
            $Connection = Connection::create($config['connection'], $config['metadata']['path'], $config['metadata']['type'] ?? Connection::MATADATA_ANNOTATION, $config['is_dev'] ?? false);
            $Factory->addConnection($Connection, $id);
        }
    }
}