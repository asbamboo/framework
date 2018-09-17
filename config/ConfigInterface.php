<?php
namespace asbamboo\framework\config;

interface ConfigInterface
{
    /**
     * 构造方法
     *
     * @param array $configs 配置信息
     */
    public function __construct(array $configs = []);

    /**
     * 配置执行的具体方法
     */
    public function configure() : void;
}