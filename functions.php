<?php

function get_parent_styles() {

	//Opcional: Cargo los estilos del padre.
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

function wp_custom_styles()
{

	//Opcional:
	// Elimino la carga por defecto de style.css en el hijo.
	// Usar solo si no se cargan estilos locales en el hijo o bien se quiere cambiar la ruta por defecto.
	wp_deregister_style( 'twentyseventeen-style' );
	wp_dequeue_style('twentyseventeen-style');

	//Elimino tipografía de Google del tema.
	wp_deregister_style( 'twentyseventeen-fonts' );
	wp_dequeue_style('twentyseventeen-fonts');

	//Cargo estilos personalizados ya comprimidos.
	wp_enqueue_style( 'style', get_theme_file_uri( '/css/style.min.css' ), null, null, 'all');

}

function wp_custom_scripts()
{

	//Eliminamos de la pila de llamadas los ficheros JS que no deseamos.
	wp_deregister_script('comment-reply');
	wp_dequeue_script('comment-reply');
	wp_deregister_script('wp-embed');
	wp_dequeue_script('wp-embed');
	wp_deregister_script('jquery');
	wp_dequeue_script('jquery');
	wp_deregister_script('twentyseventeen-skip-link-focus-fix');
	wp_dequeue_script('twentyseventeen-skip-link-focus-fix');
	wp_deregister_script('twentyseventeen-navigation');
	wp_dequeue_script('twentyseventeen-navigation');
	wp_deregister_script('twentyseventeen-global');
	wp_dequeue_script('twentyseventeen-global');
	wp_deregister_script('jquery-scrollto');
	wp_dequeue_script('jquery-scrollto');
	wp_deregister_script('html5');
	wp_dequeue_script('html5');

	// Añadirmos a la cola de llamadas nuestro fichero.
	wp_enqueue_script( 'js', get_theme_file_uri( '/js/js.min.js' ), null, null, true );


	// Es posible añadirlo a js.js y eliminar esta llamada.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.min.js', null, null, true);
	}

	//Necesario por el tema, adaptar en cada caso.
	customs_twentyseventeen();

}

// Modificamos las llamadas a ficheros JS y las convetimos en diferidas ("defer") para evitar bloqueos en el proceso del DOM.
function wp_defer_scripts( $tag, $handle, $src )
{

	if ( 'js' != $handle && 'comment-reply' != $handle) {
		return $tag;
	}
	return str_replace( '<script', '<script defer', $tag );

}

// Eliminamos la versión de WordPress usada.
function wp_hide_generator( $generator_type, $type)
{

	return '';

}

// Necesario para el tema. Adaptar en cada caso.
// Inicialización de variables.
function customs_twentyseventeen(){
	$twentyseventeen_l10n = array(
		'quote'          => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
		);
	if ( has_nav_menu( 'top' ) ) {
		$twentyseventeen_l10n['expand']         = __( 'Expand child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['collapse']       = __( 'Collapse child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['icon']           = twentyseventeen_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) );
	}

	wp_localize_script( 'js', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );
}



//Llamadas
//add_action('wp_print_styles', 'get_parent_styles' ); //opcional: para cargar los estilos del padre.
add_action('wp_print_scripts', 'wp_custom_scripts' );
add_filter('script_loader_tag', 'wp_defer_scripts', 10, 3 );
add_filter('the_generator', 'wp_hide_generator', 10, 2);
add_action('wp_print_styles', 'wp_custom_styles');
//
//Desactivar JS emojis
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles', 'print_emoji_styles' );


