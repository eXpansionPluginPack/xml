<?php

namespace Manialib\XML\Rendering\Drivers;

use DOMDocument;
use Manialib\XML\FragmentInterface;
use Manialib\XML\NodeInterface;
use Manialib\XML\Rendering\DriverInterface;
use Manialib\XML\Rendering\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DOMDocumentDriver implements DriverInterface
{

    /**
     * @var DOMDocument
     */
    protected $document;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    function __construct()
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    function getXML(NodeInterface $root)
    {
        $this->document->appendChild($this->getElement($root));
        return $this->document->saveXML();
    }

    function appendXML($xml)
    {
        $fragment = $this->document->createDocumentFragment();
        $fragment->appendXML($xml);
        return $fragment;
    }

    protected function getElement(NodeInterface $node)
    {
        $this->eventDispatcher->dispatch(Events::preRender($node));

        // XML fragment?
        if ($node instanceof FragmentInterface) {
            return $this->appendXML($node->getNodeValue());
        }

        // Create
        $element = $this->document->createElement($node->getNodeName());

        // Value
        if ($node->getNodeValue() !== null) {
            $element->appendChild($this->document->createTextNode($node->getNodeValue()));
        }

        // Attributes
        foreach ($node->getAttributes() as $name => $value) {
            $element->setAttribute($name, $value);
        }

        // Children
        foreach ($node->getChildren() as $child) {
            $subelement = $this->getElement($child);
            $element->appendChild($subelement);
        }

        $this->eventDispatcher->dispatch(Events::postRender($node));

        return $element;
    }

}
