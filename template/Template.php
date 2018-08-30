<?php
namespace asbamboo\framework\template;

use asbamboo\template\Template AS BaseTemplate;
use asbamboo\di\ContainerAwareTrait;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年8月30日
 */
class Template extends BaseTemplate
{
    use ContainerAwareTrait;

    /**
     *
     * @param array $template_dir
     * @param boolean $cache_dir
     */
    public function __construct($template_dir = [], $cache_dir = false)
    {
        parent::__construct($template_dir = [], $cache_dir = false);
        $this->initExtensions();
    }

    /**
     * 添加框架内部内置的模板扩展
     */
    public function initExtensions()
    {
        $extension_dir      = __DIR__ . DIRECTORY_SEPARATOR . 'extension';
        $extension_files    = array_diff(scandir($extension_dir), ['.' , '..']);
        foreach($extension_files AS $extension_file){
            $class      = __NAMESPACE__ . '\\extension\\' . substr($extension_file, 0, -4/*.php*/);
            $Extension  = new $class;
            if(method_exists($Extension, 'setContainer')){
                $Extension->setContainer($this->Container);
            }
            $this->addExtension($Extension);
        }
    }
}