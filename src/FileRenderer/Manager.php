<?php

namespace OctopusViewer\FileRenderer;

use Omeka\ServiceManager\AbstractPluginManager;

class Manager extends AbstractPluginManager
{
    protected $instanceOf = FileRendererInterface::class;
}
