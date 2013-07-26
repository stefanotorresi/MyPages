<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyPagesTest;

use MyPages\Module;
use PHPUnit_Framework_TestCase;

class ModuleTest extends PHPUnit_Framework_TestCase
{
    public function testOptionsAreLoaded()
    {
        $module = new Module();
        $this->assertInternalType('array', $module->getOptions());
    }

}
