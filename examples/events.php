<?php

use Manialib\XML\Node;
use Manialib\XML\Rendering\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

require_once __DIR__.'/../vendor/autoload.php';

if(!class_exists('MyCustomNode'))
{

    // Let's create a custom node
    class MyCustomNode extends Node
    {

        function __construct()
        {
            // We can set stuff in the constructor...
            $this->setNodeName('MyCustomNode');
        }

        // Override this method to register your listeners.
        protected function registerListeners(EventDispatcherInterface $dispatcher)
        {
            // $dispatcher->addListener takes two parameters: an event name, and a callable
            // Default events names can be found in Manialib\XML\Rendering\Events
            // There are some "global" events
            $dispatcher->addListener(Events::PRE_RENDER, array($this, 'preGlobalRender'));

            // But also some events related to the current instance.
            // Manialib\XML\Rendering\Events provides shortcuts to generate instance-related event names
            $dispatcher->addListener(Events::preRender($this), array($this, 'preThisRender'));

            // Full documetation of Event Dispatcher at
            // http://symfony.com/doc/current/components/event_dispatcher/index.html
        }

        function preGlobalRender()
        {
            // This is called before the rendering of the tree begins
            $this->setAttribute('foo', 'bar');
        }

        function preThisRender()
        {
            // This is called before the XML element related to this instance is created
            $this->setAttribute('hello', 'world');
        }

    }

}

return new MyCustomNode();

