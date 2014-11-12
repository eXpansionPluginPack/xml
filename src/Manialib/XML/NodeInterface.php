<?php

namespace Manialib\XML;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface NodeInterface extends EventSubscriberInterface
{

    /**
     * @return \static
     */
    public static function create();

    /**
     * @return \static
     */
    function getClone();

    /**
     * @return \static
     */
    function setNodeName($nodeName);

    /**
     * @return string
     */
    function getNodeName();

    /**
     * @return \static
     */
    function setNodeValue($value);

    /**
     * @return \static
     */
    function appendNodeValue($value);

    function getNodeValue();

    /**
     * @return \static
     */
    function setAttribute($name, $value);

    /**
     * @return \static
     */
    function appendAttribute($name, $value);

    function attributeExists($name);

    function getAttribute($name, $default = null);

    function getAttributes();

    /**
     * @return \static
     */
    function deleteAttribute($name);

    /**
     * @return \static
     */
    function setParent(NodeInterface $node);

    /**
     * @return \static
     */
    function deleteParent();

    /**
     * @return NodeInterface
     */
    function getParent();

    /**
     * @return \static
     */
    function appendChild(NodeInterface $child);

    /**
     * @return \static
     */
    function appendTo(NodeInterface $parent);

    /**
     * @return \static
     */
    function removeChild(NodeInterface $child);

}
