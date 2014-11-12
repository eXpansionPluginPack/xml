<?php

namespace Manialib\XML;

use Manialib\XML\Rendering\Events;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Node implements NodeInterface
{

    private $listenersRegistered = false;

    /**
     * @var string
     */
    protected $nodeName;

    /**
     * @var string
     */
    protected $nodeValue;

    /**
     * @var mixed[]
     */
    protected $attributes = array();

    /**
     * @var Node[]
     */
    protected $children = array();

    /**
     * @var Node
     */
    protected $parent;

    /**
     * @var callable[][]
     */
    protected $callbacks = array();

    public static function create()
    {
        return new static;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::ADD_SUBSCRIBERS => array(
                'onAddSubscribers'
            )
        );
    }

    function __clone()
    {
        $this->deleteParent();

        foreach ($this->children as $child) {
            $cloned = clone $child;
            foreach ($this as $propertyName => $propertyValue) {
                if ($child === $propertyValue) {
                    $this->$propertyName = $cloned;
                }
            }
            $this->removeChild($child);
            $this->appendChild($cloned);
        }
    }

    function getClone()
    {
        return clone $this;
    }

    function setNodeName($nodeName)
    {
        $this->nodeName = $nodeName;
        return $this;
    }

    function getNodeName()
    {
        return $this->nodeName;
    }

    function setNodeValue($value)
    {
        $this->nodeValue = $value;
        return $this;
    }

    function appendNodeValue($value)
    {
        $this->nodeValue .= $value;
        return $this;
    }

    function getNodeValue()
    {
        return $this->nodeValue;
    }

    function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    function appendAttribute($name, $value)
    {
        $this->attributes[$name] .= $value;
        return $this;
    }

    function attributeExists($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    function getAttribute($name, $default = null)
    {
        return $this->attributeExists($name) ? $this->attributes[$name] : $default;
    }

    function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return \static
     */
    function deleteAttribute($name)
    {
        unset($this->attributes[$name]);
        return $this;
    }

    function setParent(NodeInterface $node)
    {
        $this->parent = $node;
        return $this;
    }

    function deleteParent()
    {
        $this->parent = null;
        return $this;
    }

    function getParent()
    {
        return $this->parent;
    }

    function getChildren()
    {
        return $this->children;
    }

    function appendChild(NodeInterface $child)
    {
        if ($child->getParent() instanceof Node) {
            throw new Exception('Cannot append a child: it already has a parent Node.');
        }
        $this->children[] = $child;
        $child->setParent($this);
        return $this;
    }

    function appendTo(NodeInterface $parent)
    {
        $parent->appendChild($this);
        return $this;
    }

    function removeChild(NodeInterface $child)
    {
        $key = array_search($child, $this->children);
        if ($key === false) {
            throw new Exception('Cannot remove a child: it does not exist.');
        }
        $this->children[$key]->deleteParent();
        unset($this->children[$key]);
        return $this;
    }

    function onAddSubscribers(Event $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        if (!$this->listenersRegistered) {
            $this->registerListeners($dispatcher);
            foreach ($this->children as $child) {
                $dispatcher->addSubscriber($child);
                $child->onAddSubscribers($event, $eventName, $dispatcher);
            }
            $this->listenersRegistered = true;
        }
    }

    protected function registerListeners(EventDispatcherInterface $dispatcher)
    {

    }

}
