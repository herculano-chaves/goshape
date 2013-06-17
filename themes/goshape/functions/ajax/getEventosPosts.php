<?php
require('../../../../../wp-blog-header.php');

$area = $_POST['area'];
$date = new DateTime( $_POST['date'] );

# Mes retorna mes anterior e posterior
if( ($date->format( 'm' )+1)<10 ) { $nextMonth = '0'.($date->format( 'm' )+1); } else { $nextMonth = ($date->format( 'm' )+1); }	
if( ($date->format( 'm' )+1)<10 ) { $prevMonth = '0'.($date->format( 'm' )-1); } else { $prevMonth = ($date->format( 'm' )-1); }
						

# RETORNA AS DAS DE INICIO e FIM
$dayswithpostsIF = $wpdb->get_results("
						SELECT p.ID, pm.meta_value as data_inicio,
							(SELECT pm.meta_value
							FROM wp_postmeta pm
							WHERE p.ID = pm.post_id
							AND pm.meta_key = 'data_fim_meta'
							) as data_fim
						FROM wp_posts p, wp_postmeta pm
						WHERE p.ID = pm.post_id
						AND p.post_type = 'eventos'
						AND (p.post_status = 'publish' OR p.post_status = 'future')
						AND pm.meta_key = 'data_inicio_meta'
						AND ( 
							(pm.meta_key = 'data_inicio_meta' AND pm.meta_value >= '".$date->format( 'Y' )."-".$date->format( 'm' )."-01' AND pm.meta_value <= '".$date->format( 'Y' )."-06-31') 
							OR (pm.meta_key = 'data_inicio_meta' AND pm.meta_value >= '".$date->format( 'Y' )."-".$date->format( 'm' )."-01' AND pm.meta_value <= '2012-{$nextMonth}-31')  
							OR (pm.meta_key = 'data_inicio_meta' AND pm.meta_value >= '".$date->format( 'Y' )."-{$prevMonth}-01' AND pm.meta_value <= '".$date->format( 'Y' )."-".$date->format( 'm' )."-31')  
						)
						ORDER BY MONTH(data_inicio), DAY(data_inicio)
						");
						
$cont = 0;
$verificador = NULL;	
foreach($dayswithpostsIF as $dIF)	
{					
	$data_inicial = new DateTime( $dIF->data_inicio );
	$data_final   = new DateTime( $dIF->data_fim );
	
	if($verificador != $dIF->ID) :
	
	while( $data_inicial <= $data_final ) {
	
			if($data_inicial->format( 'd' ) == $date->format( 'd' ) and $data_inicial->format( 'm' ) == $date->format( 'm' )) {
				
				# Se existir um post que as datas entre inicio e fim se coincidam, retorne-o
				$query  = "
						SELECT DISTINCT p.* FROM wp_posts p, wp_postmeta pm
						WHERE p.ID = pm.post_id
						AND p.ID = ".$dIF->ID."
						AND p.post_type = 'eventos'
						AND (p.post_status = 'publish' OR p.post_status = 'future')
						AND DATE(pm.meta_value) = '".$dIF->data_inicio."'
						";
				
				$resultados = $wpdb->get_results($query, OBJECT);			
				
				# Imprime os resultados
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
							';
						
						if(empty($teste))
						{	
						$teste = $data_inicial->format( 'd' );
						echo '
								<div class="evento-data left">'.$data_inicial->format( 'd' ).' <span>'.substr(formataData($dataInicio.' 00:00:00', 'mes'),0,3).'</span></div>
						';
						}
						
						echo '								
								<div class="evento-conteudo left">
									<div class="periodo"><strong>'.$periodo.'</strong></div>
									<h2>'.truncate(reset($resultados)->post_title, 4).'</h2>
							
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
					
					break;
				} else {
					echo "<li>Não existem eventos nesta data.</li>";	
				}
				# FIM Imprime os resultados
				
				
			}
			$data_inicial->add( DateInterval::createFromDateString( '1 days' ) );
	}
	
	endif;

}


?>