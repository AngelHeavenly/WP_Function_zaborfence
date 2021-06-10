<?php 

/*
add_action( 'wp_enqueue_scripts', function(){
   //on style and script
   wp_enqueue_style('style', get_stylesheet_directory_uri().'/css/style.css', array(), time() );
   
   wp_enqueue_script('jquery' );
   
   wp_enqueue_script('6052', get_stylesheet_directory_uri().'/6052.js', array(), null, true);
   
   
 
}); */
function enqueue_versioned_script( $handle, $src = false, $deps = array(), $in_footer = false ) {
	wp_enqueue_script( $handle, get_stylesheet_directory_uri() . $src, $deps, filemtime( get_stylesheet_directory() . $src ), $in_footer );
}
 
function enqueue_versioned_style( $handle, $src = false, $deps = array(), $media = 'all' ) {
	wp_enqueue_style( $handle, get_stylesheet_directory_uri() . $src, $deps = array(), filemtime( get_stylesheet_directory() . $src ), $media );
}
 
function themename_scripts() {
	enqueue_versioned_style( 'zaborfence', '/css/style.css' );
	wp_enqueue_script('jquery' );
}
 
add_action( 'wp_enqueue_scripts', 'themename_scripts' );

add_action('after_setup_theme', function(){
   
   add_theme_support('tittle-tag'); 
});

register_nav_menus(
    
    array(
        'head_menu' => 'Меню в шапке страницы'
        )
    
    );
    
function add_additional_class_on_li($classes, $item, $args) {
    if(isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'add_additional_class_on_li', 10, 4);

class My_Walker_Nav_Menu extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = array()) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

	  /*$classes = empty( $item->classes ) ? array() : (array) $item->classes; добавляет лишние классы от WP*/
		$classes[] = 'menu-list';

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';


		// создаем HTML код элемента меню
		$output .= $indent . '<li' . $class_names .'>';
		
        //render <a> tag, and attributs, href="", value=""
		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
    if ($item->current) {
		$item_output = $args->before;
		$item_output .= '<a class="menu-link--active"'. $attributes .'>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		}
	else {
	    $item_output = $args->before;
		$item_output .= '<a class="menu-link"'. $attributes .'>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
	}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}


?>
