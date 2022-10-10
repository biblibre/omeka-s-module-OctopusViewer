<?php

namespace MediaViewer\MediaRenderer;

use Omeka\ServiceManager\AbstractPluginManager;

class Manager extends AbstractPluginManager
{
    protected $instanceOf = MediaRendererInterface::class;
}
