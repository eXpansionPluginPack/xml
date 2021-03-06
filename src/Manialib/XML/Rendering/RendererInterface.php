<?php

namespace Manialib\XML\Rendering;

use Manialib\XML\NodeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface RendererInterface
{

    function setDriver(DriverInterface $driver);

    /**
     * @return DriverInterface
     */
    function getDriver();

    function setEventDispatcher(EventDispatcherInterface $eventDispatcher);

    /**
     * @return EventDispatcherInterface
     */
    function getEventDispatcher();

    function getXML(NodeInterface $node);
}
