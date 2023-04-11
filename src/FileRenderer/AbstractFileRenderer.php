<?php

namespace OctopusViewer\FileRenderer;

use Laminas\View\HelperPluginManager;

abstract class AbstractFileRenderer implements FileRendererInterface
{
    public function getJsDependencies(HelperPluginManager $viewHelpers): array
    {
        return [];
    }
}
