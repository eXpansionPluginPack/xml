<?php

namespace Manialib\XML\Rendering\Drivers;

use Manialib\XML\FragmentInterface;
use Manialib\XML\NodeInterface;
use Manialib\XML\Rendering\DriverInterface;
use Manialib\XML\Rendering\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use XMLWriter;

class XMLWriterDriver implements DriverInterface
{

    /**
     * @var XMLWriter
     */
    protected $writer;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    function __construct()
    {
        $this->writer = new XMLWriter();
        $this->writer->openMemory();
        $this->writer->startDocument('1.0', 'UTF-8');
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    function getXML(NodeInterface $root)
    {
        $this->getElement($root);
        $this->writer->endDocument();
        return $this->writer->outputMemory(true);
    }

    function appendXML($xml)
    {
        $this->writer->writeRaw($xml);
    }

    protected function getElement(NodeInterface $node)
    {
        $this->eventDispatcher->dispatch(Events::preRender($node));

        // XML fragment?
        if ($node instanceof FragmentInterface) {
            return $this->appendXML($node->getNodeValue());
        }

        // Create
        $this->writer->startElement($node->getNodeName());

        // Attributes
        // With XMLWriter, attributes must be written prior to content and children
        // See http://www.php.net/manual/en/function.xmlwriter-write-attribute.php#103498
        foreach ($node->getAttributes() as $name => $value) {
            $this->writer->writeAttribute($name, $value);
        }

        // Value
        if ($node->getNodeValue() !== null) {
            $this->writer->text($node->getNodeValue());
        }

        // Children
        foreach ($node->getChildren() as $child) {
            $this->getElement($child);
        }

        // End create
        $this->writer->endElement();

        $this->eventDispatcher->dispatch(Events::postRender($node));
    }

}
