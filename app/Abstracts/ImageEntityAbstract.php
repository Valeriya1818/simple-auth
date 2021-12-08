<?php

namespace app\Abstracts;

abstract class ImageEntityAbstract {

    protected string $filename;
    protected array $sources;

    public abstract function loadFromDisk();
}