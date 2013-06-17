<?php
require('../../../../../wp-blog-header.php');

$area = $_POST['area'];
$date = $_POST['date'];

#Retorna 3 posts referente ao dia
$query  = "
		SELECT p.* FROM wp_posts p, wp_postmeta pm
		WHERE p.ID = pm.post_id
		AND p.post_type = 'eventos'
		AND (p.post_status = 'publish' OR p.post_status = 'future')
		AND pm.meta_key = 'data_inicio_meta'
		AND DATE(pm.meta_value) = '$date'
		";

$resultados = $wpdb->get_results($query, OBJECT);

if(count($resultados)>0)
{
	if($area == 'home')
	{
		$dataInicio = get_post_meta(reset($resultados)->ID, 'data_inicio_meta', true);
		$dataFim = get_post_meta(reset($resultados)->ID, 'data_fim_meta', true);
		
		if($dataFim)
		{
			if(formataData($dataInicio.' 00:00:00', 'mes') != formataData($dataFim.' 00:00:00', 'mes')) $mesInicio = ' de '.formataData($dataInicio.' 00:00:00', 'mes');
			$periodo = formataData($dataInicio.' 00:00:00', 'dia').$mesInicio.' a '.formataData($dataFim.' 00:00:00', 'dia').' de '.formataData($dataFim.' 00:00:00', 'mes');
		}
		
		echo '
			<a href="">
				<div class="evento-data left">'.formataData($dataInicio.' 00:00:00', 'dia').' <span>'.substr(formataData($dataInicio.' 00:00:00', 'mes'),0,3).'</span></div>
				
				<div class="evento-conteudo left">
					<div class="periodo"><strong>'.$periodo.'</strong></div>
					<h2>'.reset($resultados)->post_title.'</h2>
					<p>'.reset($resultados)->post_excerpt.'</p>
				</div>
			</a>	
		';
	} else {
		foreach($resultados as $p)
		{
			$dataInicio = get_post_meta($p->ID, 'data_inicio_meta', true);
			$dataFim = get_post_meta($p->ID, 'data_fim_meta', true);
			
			if($dataFim)
			{
				if(formataData($dataInicio.' 00:00:00', 'mes') != formataData($dataFim.' 00:00:00', 'mes')) $mesInicio = ' de '.formataData($dataInicio.' 00:00:00', 'mes');
				$periodo = formataData($dataInicio.' 00:00:00', 'dia').$mesInicio.' a '.formataData($dataFim.' 00:00:00', 'dia').' de '.formataData($dataFim.' 00:00:00', 'mes');
			}
			
			echo '
				<li>
					<a href="'.get_permalink($p->ID).'">
						
						<div class="resumo">
							<div class="periodo"><strong>'.$periodo.'</strong></div>
							<span class="titulo">'.$p->post_title.'</span>
							'.$p->post_excerpt.'
						</div>
					</a>
				</li>
			';
		}
	}
} else {
	echo "<li>Não existem eventos nesta data.</li>";	
}
?>