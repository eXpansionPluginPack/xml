<?php

namespace Manialib\XML\Tests;

use Manialib\XML\NodeInterface;
use Manialib\XML\Rendering\DriverInterface;
use Manialib\XML\Rendering\Renderer;
use PHPUnit_Framework_TestCase;

class ExamplesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Renderer
     */
    protected $renderer;

    protected function setUp()
    {
        $this->renderer = new Renderer();
    }

    public function examplesAndDriversProvider()
    {
        $tests = array();
        foreach (new ExamplesDirectoryIterator () as $name => $examples) {
            foreach (new DriverIterator () as $driver) {
                $tests[$name.' '.get_class($driver)] = array_merge(array($driver), $examples);
            }
        }
        return $tests;
    }

    /**
     * @dataProvider examplesAndDriversProvider
     */
    public function testExample(DriverInterface $driver, NodeInterface $node, $expectedResult)
    {
        $driver->setEventDispatcher($this->renderer->getEventDispatcher());
        $this->renderer->setDriver($driver);
        $this->assertXmlStringEqualsXmlFile($expectedResult, $this->renderer->getXML($node));
    }
}
