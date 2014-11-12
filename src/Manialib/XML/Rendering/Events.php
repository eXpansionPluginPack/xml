<?php

namespace Manialib\XML\Rendering;

use Manialib\XML\NodeInterface;

abstract class Events
{

    /**
     * Fired when root the Node subscribes to the Event Dispatcher
     * Allow for lazy loading of all listeners in the Node tree
     */
    const ADD_SUBSCRIBERS = 'manialib.xml.rendering.add_subscribers';

    /**
     * Fired before the rendering of the tree begins
     */
    const PRE_RENDER = 'manialib.xml.rendering.pre_render';

    /**
     * Fired when the rendering of the tree is finished
     */
    const POST_RENDER = 'manialib.xml.rendering.post_render';

    /**
     * Fired before the given $node is rendered
     */
    static public function preRender(NodeInterface $node)
    {
        return 'manialib.xml.rendering.' . spl_object_hash($node) . '.pre_create';
    }

    /**
     * Fired after the given $node is rendered
     */
    static public function postRender(NodeInterface $node)
    {
        return 'manialib.xml.rendering.' . spl_object_hash($node) . '.post_create';
    }

}
