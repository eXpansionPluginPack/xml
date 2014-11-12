<?php

namespace Manialib\XML\Tests;

class DriverIterator implements \Iterator
{

    protected $drivers      = array();
    protected $currentIndex = 0;

    function __construct()
    {
        $this->drivers = array(
            new \Manialib\XML\Rendering\Drivers\XMLWriterDriver(),
            new \Manialib\XML\Rendering\Drivers\DOMDocumentDriver(),
        );
    }

    public function current()
    {
        return $this->drivers[$this->currentIndex];
    }

    public function key()
    {
        return $this->currentIndex;
    }

    public function next()
    {
        $this->currentIndex++;
    }

    public function rewind()
    {
        $this->currentIndex = 0;
    }

    public function valid()
    {
        return array_key_exists($this->currentIndex, $this->drivers);
    }

}
