<?php
namespace asbamboo\framework\exception;

/**
 * 异常处理程序
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月5日
 */
interface HandlerInterface
{
    /**
     * 异常信息输出格式
     *
     * @var string
     */
    const FORMAT_HTML   = 'html';

    /**
     * 设置一个异常
     * 应该在捕获[catch]到一个异常信息时，使用这个方法。
     *
     * @param \Throwable $Exception
     * @return HandlerInterface
     */
    public function setException(\Throwable $Exception) : HandlerInterface;

    /**
     * 输出异常信息
     * 将捕获的异常信息输出
     *
     * @param string $format 输出格式暂时只支持html
     */
    public function print(string $format = self::FORMAT_HTML) : void;
}