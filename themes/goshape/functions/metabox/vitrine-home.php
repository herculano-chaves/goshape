<?php
add_action( 'add_meta_boxes', 'add_link_metabox' );
	
// Add the Projetos Meta Box
function add_link_metabox() {
    add_meta_box('link_meta', 'URL de destino', 'link_meta', 'vitrine_home', 'normal', 'default');
}

// HTML do Projetos Metabox
function link_meta() {
    global $post;
	global $wpdb;
	
	# Verifica se está num diretório local
	if($_SERVER['HTTP_HOST'] == 'localhost')
	{
		$pastaLocal = '/plataforma';
	} else {
		$pastaLocal = '';
	}
	
	$postType = 'p.post_type = "noticias"';
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="link_meta_noncename" id="link_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $link = get_post_meta($post->ID, 'link_post', true);
	$link_ID = get_post_meta($post->ID, 'id_post_selected', true);
    $vitrineSelect = get_post_meta($post->ID, 'post_selected', true);
	
 
	echo '<p>Selecione o post desejado abaixo ou digite uma URL no campo <strong>"Apontar para"</strong>.
		  </p>';
	
	
	/* ARRAY DE MESES */ 
	$meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	
	# SELECTBOX CATEGORIA
	echo'
	<select id="categoria" name="categoria" class="default">
		<option value="">Filtrar por categoria</option>
		';
		# Retorna os meses e anos dos posts da categoria noticias (9)
		$query = "
			SELECT DISTINCT p.post_type
			FROM ". $wpdb->base_prefix ."posts p
			WHERE $postType
			AND p.post_status = 'publish'
			ORDER BY p.post_date DESC
			"; 
		
		$resultados = $wpdb->get_results($query, OBJECT);
		
		foreach($resultados as $cat)
		{
			?>
			<option value="<?php echo $cat->post_type; ?>"><?php echo $cat->post_type; ?></option>';
            <?php
		}
	echo '
	</select>
	';
	# FIM SELECTBOX CATEGORIA
	
	# SELECTBOX DATAS
	echo'
	<select id="data" name="data" class="default">
		<option value="">Filtrar por data</option>
		';
		# Retorna os meses e anos dos posts da categoria noticias (9)
		$query = "
			SELECT DISTINCT MONTH(p.post_date) as mes, YEAR(p.post_date) as ano
			FROM ". $wpdb->base_prefix ."posts p
			WHERE $postType
			AND p.post_status = 'publish'
			ORDER BY p.post_date DESC
			"; 
		
		$resultados = $wpdb->get_results($query, OBJECT);
		
		foreach($resultados as $periodo)
		{
			?>
			<option value="<?php echo $periodo->mes.'-'.$periodo->ano; ?>"><?php echo $meses[($periodo->mes-1)].'-'.$periodo->ano; ?></option>';
            <?php
		}
	echo '
	</select>
	';
	# FIM SELECTBOX DATAS
	
	echo '<input type="button" value="Ok" style="cursor:pointer;" onclick="filtraPosts();" />';
	
	echo '<br /><br />';
	
	# SELECTBOX POSTS
	# retorna todos os posts
	$querystr = "
		SELECT p.ID, p.post_title, p.post_date, p.post_type, tm.name as categoria_post
		FROM ". $wpdb->base_prefix ."posts p
		WHERE $postType
		AND p.post_status = 'publish'
		ORDER BY p.post_date DESC
	";
	
	$resultados = $wpdb->get_results($querystr, OBJECT);
	
	echo '<select id="post_selected" name="post_selected" class="widefat" onchange="urlTargetSelected(this.value);">';
		echo '<option value="">Selecione um Post</option>';
	
	foreach($resultados as $p)
	{
		echo '<option value="'.$p->ID.'|'.get_permalink($p->ID).'">['.$p->categoria_post.']&nbsp;&nbsp;&nbsp;  '.$p->post_title.' &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;'.formataData($p->post_date).'</option>';
	}
	echo '</select>';
	
	echo '<br /><div id="resposta"></div>';
	
	# Verifica o option selecionado
	echo '
  	<script type="text/javascript">
		for(i=0; i<document.getElementById("post_selected").options.length; i++){
			if(document.getElementById("post_selected").options[i].value=="'.$vitrineSelect.'"){
				document.getElementById("post_selected").options[i].selected = true;		
			}
		}
	';
	
	echo "
		function urlTargetSelected(url)
		{
			if(url!='')
			{
				url = url.split('|');
				document.getElementById('link_post').value = url[1];
				document.getElementById('id_post_selected').value = url[0];
			}
		}
		
		function limpaUrlTargetSelected()
		{
			document.getElementById('link_post').value = '';
			document.getElementById('id_post_selected').value = '';
		}
		";
	
	echo '
	</script> 
	';
	# FIM SELECTBOX POSTS
	
	echo '<br /><br /><br />';
	
	echo 'Apontar para: <br />
		<span style="color:#666">ID:</span> <input type="text" id="id_post_selected" name="id_post_selected" style="width:30px;" value="'.$link_ID.'" />
		<span style="color:#666; margin-left:10px;">URL:</span> <input type="text" id="link_post" name="link_post" style="width:500px;" value="'.$link.'" />
		
		<input type="button" onclick="limpaUrlTargetSelected();" style="width:590px; cursor:pointer;" value="Limpar URL" />
	';
	
	echo "
		<script type='text/javascript'>
		// [ADMIN] Retorna os filtros selecionados na vitrine
		function filtraPosts()
		{
			jQuery(document).ready(function() { 
					
					jQuery('#resposta').html( 'Carregando ...' );
					
					var categoria = jQuery('#categoria').val();
					var data = jQuery('#data').val();
					
					jQuery.ajax({
					  type: 'POST',
					  url: '".$pastaLocal."/wp-content/themes/sergiocabral/functions/ajax/getPostsVitrine.php',
					  data: { categoria: categoria, data: data },
					  success: function( results ) {
						jQuery('#post_selected').html( results );
						
						jQuery('#resposta').html( '' );
					  }
					});
				
			});
		}
		</script>
	";
	
}

	 
// Save the Metabox Data
function wpt_save_link_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['link_meta_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    $events_meta['link_post'] = $_POST['link_post'];
	$events_meta['post_selected'] = $_POST['post_selected'];
	$events_meta['id_post_selected'] = $_POST['id_post_selected'];
 
    // Add values of $events_meta as custom fields
 
    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
 
}
 
add_action('save_post', 'wpt_save_link_meta', 1, 2); // save the custom fields
?>