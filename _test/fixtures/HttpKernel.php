<?php
namespace asbamboo\framework\_test\fixtures;

use asbamboo\framework\kernel\HttpKernel as BaseHttpKernel;

/**
 * 用于测试 asbamboo\framework\kernel\HttpKernel
 * 
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月24日
 */
class HttpKernel extends BaseHttpKernel
{
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\HttpKernel::getProjectDir()
     */
    public function getProjectDir(): string
    {
        return __DIR__;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\HttpKernel::getConfigPath()
     */
    public function getConfigPath() : string
    {
        return __DIR__ . '/config/config.php' ;
    }
}