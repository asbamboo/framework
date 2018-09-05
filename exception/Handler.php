<?php
namespace asbamboo\framework\exception;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\framework\Constant;
use asbamboo\framework\template\Template;
use asbamboo\http\Response;
use asbamboo\http\Stream;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月5日
 */
class Handler implements HandlerInterface
{
    use ContainerAwareTrait;

    /**
     *
     * @var \Throwable
     */
    private $Exception = [];

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\exception\HandlerInterface::addExcetion()
     */
    public function setException(\Throwable $Exception) : HandlerInterface
    {
        $this->Exception = $Exception;
        return $this;
    }

    public function print(string $format = self::FORMAT_HTML) : void
    {
        /**
         * @var Template $Template
         */
        $Exception                  = $this->Exception;
        $Template                   = $this->Container->get(Constant::KERNEL_TEMPLATE);
        $code                       = $this->Exception->getCode()?:'500';
        $is_custom_exception_view   = false;
        $view_content               = '';

        /**
         * 如果是自定义exception view路径，那么读取自定义的文件路径
         * @var string $is_custom_exception_view
         */
        foreach($Template->getLoader()->getPaths() AS $tpl_dir){
            $custom_exception_dir       = rtrim($tpl_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '_exception';
            if(is_dir($custom_exception_dir)){
                $custom_exception_view_tpl_path     = $code . '.' . $format . '.tpl';
                $custom_exception_view_full_path    = $custom_exception_dir . DIRECTORY_SEPARATOR . $custom_exception_view_tpl_path;
                if(is_readable($custom_exception_view_full_path)){
                    $view_content               = $Template->render("_exception/{$custom_exception_view_tpl_path}", ['Exception' => $Exception]);
                    $is_custom_exception_view   = true;
                    break;
                }
                $custom_exception_view_tpl_path     = 'exception' . '.' . $format . '.tpl';
                $custom_exception_view_full_path    = $custom_exception_dir . DIRECTORY_SEPARATOR . $custom_exception_view_tpl_path;
                if(is_readable($custom_exception_view_full_path)){
                    $view_content               = $Template->render("_exception/{$custom_exception_view_tpl_path}", ['Exception' => $Exception]);
                    $is_custom_exception_view   = true;
                    break;
                }
            }
        }

        /**
         * 非自定义exception view 路径，那么读取系统默认文件路径
         */
        if($is_custom_exception_view == false){
            $system_exception_dir   = __DIR__ . DIRECTORY_SEPARATOR . 'view';
            $Template->getLoader()->addPath($system_exception_dir, 'exception');
            $system_exception_view_tpl_path     = "@exception/exception.{$code}.{$format}.tpl";
            $system_exception_view_full_path    = $system_exception_dir . DIRECTORY_SEPARATOR . 'exception.' . $code . '.' . $format . '.tpl';
            if(!is_readable($system_exception_view_full_path)){
                $system_exception_view_tpl_path = "@exception/exception.{$format}.tpl";
            }
            $view_content   = $Template->render($system_exception_view_tpl_path, ['Exception' => $Exception]);
        }

        $Stream     = new Stream('php://temp', 'w+b');
        $Stream->write($view_content);
        (new Response($Stream, $code))->send();
    }
}