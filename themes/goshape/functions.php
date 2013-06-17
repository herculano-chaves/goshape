<?php 
require_once('functions/index.php');



if ( ! function_exists( 'post_is_in_descendant_category' ) ) {
	function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			$descendants = get_term_children( (int) $cat, 'category' );
			if ( $descendants && in_category( $descendants, $_post ) )
				return true;
		}
		return false;
	}
}
// SUPORTE DE TEMA ----------------------------------------------------------//
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'tamanho', 325, 269, true );
}

//FORMATAÇAO DE CONTEUDO -----------------------------------------------------//

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
 
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}

add_filter('excerpt_length', 'my_excerpt_length');

function my_excerpt_length($length) {
	return 300; 
}

//MENUS ----------------------------------------------------------------------//

register_nav_menu( 'menu_primary', __( 'Menu Principal', 'goshape' ) );
register_nav_menu( 'menu_category', __( 'Menu Categorias', 'goshape' ) );
register_nav_menu( 'menu_footer', __( 'Menu Rodape', 'goshape' ) );


//FORMATAÇAO DE DATA ---------------------------------------------------------//
function formataData($datetime, $formato = "nacional", $tipo = "")
{
  date_default_timezone_set('America/Sao_Paulo');
    
  if(strstr($datetime,"-"))
  {
    $datetime = split(' ', $datetime);
    $data = split('-',$datetime[0]);
    $hora = split(':', $datetime[1]);
    
    $dia = $data[2];
    $mes = $data[1];
    $ano = $data[0];
    
    $h = $hora[0];
    $m = $hora[1];
    $s = $hora[2];
  }
  else if(strstr($datetime,"/"))
  {
    $datetime = split(' ', $datetime);
    $data = split('/',$datetime[0]);
    $hora = split(':', $datetime[1]);
    
    $dia = $data[0];
    $mes = $data[1];
    $ano = $data[2];
    
    $h = $hora[0];
    $m = $hora[1];
    $s = $hora[2];
  }
  
  switch ($tipo)
  {
    case "ano":
      return $ano;
      break;
      
    case "mes":
      return $mes;
      break;
      
    case "dia":
      return $dia;
      break;
      
    case "hora":
      return $h;
      break;
      
    case "minuto":
      return $m;
      break;
      
    case "segundo":
      return $s;
      break;
      
    default:
      $meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');  
      if($formato == "nacional")
      {
        return $dia . '/' . $mes . '/' . $ano /*. " " . $h . ':' . $m . ':' . $s*/;
      }
      else if($formato == "nacionalcomhora")
      {
        return $dia . '/' . $mes . '/' . $ano . " " . $h . 'h' . $m;
      }
      else if($formato == "americano")
      {
        return $ano . '-' . $mes . '-' . $dia;
      }
      else if($formato == "datacompleta")
      {
        return $dia . ' de ' . __($meses[$mes-1]) . ' de ' . $ano;
      }
      else if($formato == "hora")
      {
        return $h . 'h';
      }
      else if($formato == "dia")
      {
        return $dia;
      }
      else if($formato == "mes")
      {
        return __($meses[$mes-1]);
      }
      
      break;
  }

}
//BREADCRUMB -----------------------------------------------------------------//
function the_breadcrumb() {
  if (!is_home()) {
    echo '<a href="';
    echo get_option('home');
    echo '">';
    echo 'Home';
    echo "</a> > ";
    if (is_category() || is_single()) {
      the_category('>');
      if (is_single()) {
        echo " > ";
        the_title();
      }
    } elseif (is_page()) {
      echo the_title();
    }
  }
}
// Removendo itens de menu (padrão do sistema, mantendo somente os customizados) ---//
function remove_admin_menu_item() {
  global $menu;

  $remove = array(
    __('Dashboard'),
    __('Links'),
    __('Pages'),
    __('Midia'),
    __('Profile'),
    __('Tools'),
    __('Comments'),
  );

  foreach ($menu as $key => $item) {
    foreach ($remove as $its) {
      if ($item[0] == $its) {
        unset($menu[$key]);
      }
    }
  }
}
add_action('admin_menu', 'remove_admin_menu_item');


?>