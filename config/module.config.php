<?php

namespace MediaViewer;

return [
    'controllers' => [
        'invokables' => [
            'MediaViewer\Controller\Site\Media' => Controller\Site\MediaController::class,
        ],
        'factories' => [
            'MediaViewer\Controller\Site\Viewer' => Service\Controller\Site\ViewerControllerFactory::class,
        ],
    ],
    'mediaviewer_file_renderers' => [
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
            'image/tiff' => 'image',
            'image/webp' => 'image',
            'application/pdf' => 'pdf',
        ],
    ],
    'mediaviewer_media_renderers' => [
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
            'mediaViewer' => Site\ResourcePageBlockLayout\MediaViewer::class,
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'mediaviewer' => [
                        'type' => \Laminas\Router\Http\Literal::class,
                        'options' => [
                            'route' => '/mediaviewer',
                            'defaults' => [
                                '__NAMESPACE__' => 'MediaViewer\Controller\Site',
                            ],
                        ],
                        'child_routes' => [
                            'default' => [
                                'type' => \Laminas\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/:controller/:id/:action',
                                ],
                            ],
                            'viewer' => [
                                'type' => \Laminas\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/viewer/:action',
                                    'defaults' => [
                                        'controller' => 'viewer',
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
            'MediaViewer\MediaRendererManager' => Service\MediaRenderer\ManagerFactory::class,
            'MediaViewer\FileRendererManager' => Service\FileRenderer\ManagerFactory::class,
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
            'mediaViewer' => Service\ViewHelper\MediaViewerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
];
