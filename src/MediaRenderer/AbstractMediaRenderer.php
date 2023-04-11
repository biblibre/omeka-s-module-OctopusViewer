<?php

namespace OctopusViewer\MediaRenderer;

use Laminas\View\HelperPluginManager;

abstract class AbstractMediaRenderer implements MediaRendererInterface
{
    public function getJsDependencies(HelperPluginManager $viewHelpers): array
    {
        return [];
    }
}
