<?php

namespace Manialib\XML\Rendering;

use Manialib\XML\NodeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface DriverInterface
{

    function setEventDispatcher(EventDispatcherInterface $eventDispatcher);

    function getXML(NodeInterface $root);

    function appendXML($xml);
}
