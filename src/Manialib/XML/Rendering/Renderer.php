<?php

namespace Manialib\XML\Rendering;

use Manialib\XML\Exception;
use Manialib\XML\NodeInterface;
use Manialib\XML\Rendering\Drivers\XMLWriterDriver;

class Renderer implements RendererInterface
{

    /**
     * @var NodeInterface
     */
    protected $root;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    function setRoot(NodeInterface $node)
    {
        $this->root = $node;
    }

    public function getRoot()
    {
        return $this->root;
    }

    function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    function getDriver()
    {
        if (!$this->driver) {
            $driver = new XMLWriterDriver();
            $driver->setEventDispatcher($this->getEventDispatcher());
            $this->setDriver($driver);
        }
        return $this->driver;
    }

    public function setEventDispatcher(\Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getEventDispatcher()
    {
        if (!$this->eventDispatcher) {
            $this->setEventDispatcher(new \Symfony\Component\EventDispatcher\EventDispatcher());
        }
        return $this->eventDispatcher;
    }

    function getXML(NodeInterface $node)
    {
        $this->getEventDispatcher()->addSubscriber($node);
        $this->getEventDispatcher()->dispatch(Events::ADD_SUBSCRIBERS);

        $this->getEventDispatcher()->dispatch(Events::PRE_RENDER);
        $xml = $this->getDriver()->getXML($node);
        $this->getEventDispatcher()->dispatch(Events::POST_RENDER);

        $this->getEventDispatcher()->removeSubscriber($node);

        return $xml;
    }

}
