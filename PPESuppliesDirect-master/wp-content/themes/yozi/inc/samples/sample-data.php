<?php

$path_dir = get_template_directory() . '/inc/samples/data/';
$path_uri = get_template_directory_uri() . '/inc/samples/data/';

if ( is_dir($path_dir) ) {
	$demo_datas = array(
		'home1'               => array(
			'data_dir'      => $path_dir . 'home1',
			'title'         => esc_html__( 'Home 1', 'yozi' ),
		),
		'home234'               => array(
			'data_dir'      => $path_dir . 'home234',
			'title'         => esc_html__( 'Home 2,3,4,7,8', 'yozi' ),
		),
		'home5'               => array(
			'data_dir'      => $path_dir . 'home5',
			'title'         => esc_html__( 'Home 5', 'yozi' ),
		),
		'home6'               => array(
			'data_dir'      => $path_dir . 'home6',
			'title'         => esc_html__( 'Home 6', 'yozi' ),
		)
	);
}