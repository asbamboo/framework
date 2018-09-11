<?php
namespace asbamboo\framework\_test\template;

use PHPUnit\Framework\TestCase;
use asbamboo\framework\kernel\Http;
use asbamboo\framework\_test\fixtures\TestKernel;
use asbamboo\framework\Constant;
use asbamboo\framework\template\extension\AuthorizationExtension;
use asbamboo\security\user\Role;
use asbamboo\framework\template\extension\GlobalExtension;
use asbamboo\security\user\UserInterface;
use asbamboo\http\ServerRequest;
use asbamboo\http\ServerRequestInterface;
use asbamboo\framework\template\extension\RouterExtension;
use asbamboo\framework\_test\fixtures\extensions\TemplateExtensions;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月11日
 */
class TemplateTest extends TestCase
{
    public function testAuthorizationExtension()
    {
        $TestKernel     = new TestKernel(true);
        $Template       = $TestKernel->getContainer()->get(Constant::KERNEL_TEMPLATE);
        $Extension      = $Template->getExtension(AuthorizationExtension::class);
        $this->assertTrue($Extension->hasRoles(Role::ANONYMOUS));
    }

    public function testGlobalExtension()
    {
        $TestKernel     = new TestKernel(true);
        $Template       = $TestKernel->getContainer()->get(Constant::KERNEL_TEMPLATE);
        $Extension      = $Template->getExtension(GlobalExtension::class);
        $this->assertInstanceOf(UserInterface::class, $Extension->user());
        $this->assertInstanceOf(ServerRequestInterface::class, $Extension->request());
        $this->assertTrue($Extension->isDebug());
    }

    public function testRouterExtension()
    {
        $TestKernel     = new TestKernel(true);
        $Template       = $TestKernel->getContainer()->get(Constant::KERNEL_TEMPLATE);
        $Extension      = $Template->getExtension(RouterExtension::class);
        $this->assertEquals('/', $Extension->path('home'));
        $this->assertFalse($Extension->isCurrent('home'));
    }

    public function testUserCustomExtension()
    {
        $TestKernel     = new TestKernel(true);
        $Template       = $TestKernel->getContainer()->get(Constant::KERNEL_TEMPLATE);
        $this->assertArrayHasKey(TemplateExtensions::class, $Template->getExtensions());
    }
}