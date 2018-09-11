<?php
namespace asbamboo\framework\_test\fixtures\extensions;

use asbamboo\template\Extension;
use asbamboo\template\Functions;

class TemplateExtensions extends Extension
{
    /**
     *
     * {@inheritDoc}
     * @see Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new Functions('test_extension', [$this, 'testExtension']),
        ];
    }

    public function testExtension()
    {
        echo 'testExtension';
    }
}
