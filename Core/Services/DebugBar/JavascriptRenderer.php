<?php


namespace Core\Services\DebugBar;


class JavascriptRenderer
{
    public array $data;
    private DebugBar $debugBar;


    public function __construct(DebugBar $debugBar)
    {
        $this->debugBar = $debugBar;
    }

    public function render()
    {

        $this->data = $this->debugBar->getData();

        return $this;
    }

}
