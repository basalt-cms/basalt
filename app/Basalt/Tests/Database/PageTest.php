<?php

namespace Basalt\Tests\Database;

use Basalt\Database\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Basalt\Exceptions\ValidationException
     */
    public function testValidationException()
    {
        $page = new Page;
        $page->validate();
    }
}
 