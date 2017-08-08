<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Chururgie\Controller\Chururgie' => 'Chururgie\Controller\ChururgieController'
				)
		),
		'router' => array (
				'routes' => array (
						'chururgie' => array (
								'type' => 'segment',
								'options' => array (
										'route' => '/chururgie[/][:action][/:id][/:val]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'val' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Chururgie\Controller\Chururgie',
												'action' => 'liste-patient'
										)
								)
						)
				)
		),
		'view_manager' => array (
				'template_map' => array (
						'layout/chururgie' => __DIR__ .'/../view/layout/chururgie.phtml',
						'layout/menugauche' => __DIR__ .'/../view/layout/menugauche.phtml',
						'layout/piedpage' => __DIR__ .'/../view/layout/piedpage.phtml'
				),
				'template_path_stack' => array (
						'chururgie' => __DIR__ .'/../view'
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		)
);