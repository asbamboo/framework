<?php
namespace asbamboo\framework\_test\config;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\config\DbConfig;
use asbamboo\di\Container;
use asbamboo\di\ServiceMapping;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\framework\Constant;
use asbamboo\database\Factory;
use asbamboo\database\ManagerInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月11日
 */
class DbConfigTest extends TestCase
{
    public function testConfigure()
    {
        $ServiceMappings    = new ServiceMappingCollection();
        $Container          = new Container($ServiceMappings);
        $Container->set(Constant::KERNEL_DB, new Factory());

        // 试试看如果没有配置信息，是否会报错。
        $DbConfig   = new DbConfig();
        $DbConfig->setContainer($Container);
        $DbConfig->configure();

        // 测试只有一个默认数据库
        $DbConfig   = new DbConfig([
            'connection'    => [
                'driver'    => 'pdo_sqlite',
                'path'      =>  dirname(__DIR__) . '/data/db.sqlite',
            ],'metadata'    => [
                'path'      => dirname(__DIR__) . '/model',
                'type'      => 'annotation',
            ],'is_dev'      => true,
        ]);
        $DbConfig->setContainer($Container);
        $DbConfig->configure();
        $this->assertInstanceOf(ManagerInterface::class, $Container->get(Constant::KERNEL_DB)->getManager());


        // 测试只有多个数据库
        $DbConfig   = new DbConfig([
            'db1' =>[
                'connection'    => [
                    'driver'    => 'pdo_sqlite',
                    'path'      =>  dirname(__DIR__) . '/data/db.sqlite',
                ],'metadata'    => [
                    'path'      => dirname(__DIR__) . '/model',
                ]
            ],
            'db2' =>[
                'connection'    => [
                    'driver'   => 'pdo_mysql',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'foo',
                ],'metadata'    => [
                    'path'      => dirname(__DIR__) . '/model',
                ]
            ],
            'db3' =>[
                'connection'    => [
                    'driver'   => 'pdo_mysql',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'bar',
                ],'metadata'    => [
                    'path'      => dirname(__DIR__) . '/model',
                ]
            ],
        ]);
        $DbConfig->setContainer($Container);
        $DbConfig->configure();
        $this->assertInstanceOf(ManagerInterface::class, $Container->get(Constant::KERNEL_DB)->getManager('db1'));
        $this->assertInstanceOf(ManagerInterface::class, $Container->get(Constant::KERNEL_DB)->getManager('db2'));
        $this->assertInstanceOf(ManagerInterface::class, $Container->get(Constant::KERNEL_DB)->getManager('db3'));
    }
}