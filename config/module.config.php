<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

return [

    'rcmPlugin' => [
        'RcmDynamicNavigation' => [
            'type' => 'Common',
            'display' => 'Dynamic Navigation Menu',
            'tooltip' => 'An editable navigation menu',
            'icon' => '',
            'canCache'=> false,
            'editJs' => '/modules/rcm-dynamic-navigation/edit.js',
            'defaultInstanceConfig' => include __DIR__ . '/defaultInstanceConfig.php'
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'rcmDynamicLinksRenderLinks' => \RcmDynamicNavigation\View\RenderLinks::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'controllers' => [
        'factories' => [
            'RcmDynamicNavigation' => \RcmDynamicNavigation\Factory\PluginControllerFactory::class,
        ]
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-dynamic-navigation/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm/modules.css' => [
                    'modules/rcm-dynamic-navigation/rcm-dynamic-navigation.css'
                ],
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-dynamic-navigation/edit.js',
                ],
            ],
        ],
    ],
];
