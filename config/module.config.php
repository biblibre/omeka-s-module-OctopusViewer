<?php

namespace OctopusViewer;

return [
    'controllers' => [
        'invokables' => [
            'OctopusViewer\Controller\Pdfjs' => Controller\PdfjsController::class,
            'OctopusViewer\Controller\Site\Media' => Controller\Site\MediaController::class,
        ],
        'factories' => [
            'OctopusViewer\Controller\Site\Viewer' => Service\Controller\Site\ViewerControllerFactory::class,
        ],
    ],
    'js_translate_strings' => [
        'Download', // @translate
        'Toggle fullscreen', // @translate
        'Previous', // @translate
        'Next', // @translate
    ],
    'octopusviewer_file_renderers' => [
        'factories' => [
            'image' => Service\FileRenderer\ImageFactory::class,
        ],
        'invokables' => [
            'pdf' => FileRenderer\Pdf::class,
            'fallback' => FileRenderer\Fallback::class,
        ],
        'aliases' => [
            'image/jp2' => 'image',
            'image/jpeg' => 'image',
            'image/png' => 'image',
            'image/svg+xml' => 'image',
            'image/tiff' => 'image',
            'image/webp' => 'image',
            'application/pdf' => 'pdf',
        ],
    ],
    'octopusviewer_media_renderers' => [
        'factories' => [
            'file' => Service\MediaRenderer\FileFactory::class,
        ],
        'invokables' => [
            'iiif' => MediaRenderer\Iiif::class,
            'fallback' => MediaRenderer\Fallback::class,
        ],
    ],
    'resource_page_block_layouts' => [
        'invokables' => [
            'octopusViewer' => Site\ResourcePageBlockLayout\OctopusViewer::class,
            'octopusViewerMediaSelector' => Site\ResourcePageBlockLayout\OctopusViewerMediaSelector::class,
            'octopusViewerMediaView' => Site\ResourcePageBlockLayout\OctopusViewerMediaView::class,
            'octopusViewerMediaInfo' => Site\ResourcePageBlockLayout\OctopusViewerMediaInfo::class,
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'octopusviewer' => [
                        'type' => \Laminas\Router\Http\Literal::class,
                        'options' => [
                            'route' => '/octopusviewer',
                            'defaults' => [
                                '__NAMESPACE__' => 'OctopusViewer\Controller\Site',
                            ],
                        ],
                        'child_routes' => [
                            'default' => [
                                'type' => \Laminas\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/:controller[/:action]',
                                    'defaults' => [
                                        'action' => 'browse',
                                    ],
                                ],
                            ],
                            'default-id' => [
                                'type' => \Laminas\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/:controller/:id/:action',
                                ],
                            ],
                            // This route is useful to add a Content-Security-Policy
                            // header to the response and to inject a custom
                            // CSS stylesheet.
                            // Other than that, it only renders the pdfjs
                            // viewer.html file
                            'pdfjs-viewer' => [
                                'type' => \Laminas\Router\Http\Literal::class,
                                'options' => [
                                    'route' => '/pdf.js/web/viewer',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'OctopusViewer\Controller',
                                        'controller' => 'pdfjs',
                                        'action' => 'viewer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'OctopusViewer\MediaRendererManager' => Service\MediaRenderer\ManagerFactory::class,
            'OctopusViewer\FileRendererManager' => Service\FileRenderer\ManagerFactory::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'octopusViewer' => Service\ViewHelper\OctopusViewerFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
];
