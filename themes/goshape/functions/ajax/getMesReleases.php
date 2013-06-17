<?php
require('../../../../../wp-blog-header.php');

$mesCorrente = $_POST['mesCorrente'];
$direcao = $_POST['direcao'];
$postId = $_POST['postID'];

if($direcao == 'prev')
{
	
	$prev = $mesCorrente-1;
	$next = $mesCorrente+1;
	$mesSelecionado = $meses[($mesCorrente-1)];

} else {
	$prev = $mesCorrente-1;
	$next = $mesCorrente+1;
	$mesSelecionado = $meses[($mesCorrente-1)];
}
?>						

  <h3 class="titulo-releases">Escolha um mês</h3>
	
	<div id="navigation">
    <?php if($prev>0){ ?>
	  <a href="javascript:getMesReleases(<?php echo $prev; ?>, <?php echo $postId; ?>, 'prev');" class="replace back-release"></a>
    <?php } ?>
		  <span class="mes-atual"><?php echo $mesSelecionado; ?></span>
    <?php if($next<13){ ?>
	  <a href="javascript:getMesReleases(<?php echo $next; ?>, <?php echo $postId; ?>, 'next');" class="replace next-release"></a>
	<?php } ?>
    </div>
	
	<ul id="lista_releases">
		<?php 
		
		#Retorna os anexos do post CLIPPING
		$query = "
				SELECT  DISTINCT p.* 
				FROM wp_postmeta pm, wp_posts p
				WHERE p.ID = pm.post_id
				AND MONTH(p.post_date) = $mesCorrente 
				AND p.post_type = 'attachment'
				AND p.post_parent = $postId
				ORDER BY pm.meta_value DESC
				";
				
		$attachments = $wpdb->get_results($query, OBJECT);
		
		
		if ($attachments) {
			foreach ($attachments as $attachment) {
		?>
		<li>
			<span class="replace obj-pdf"></span>
			<div class="resumo">
				<span class="data-titulo"><?php echo formataData($attachment->post_date); ?> - <?php echo $attachment->post_content; ?></span>
				<div class="texto"><?php echo $attachment->post_title; ?></div>
				<a href="<?php echo $attachment->guid; ?>" class="download" target="_blank">Download</a>
			</div>
		</li>
		<?php
			}
		} else {
		?>
			<li>
                <div class="resumo">
               
                    <div class="texto">Não existem documentos no momento.</div>
                 
                </div>
            </li>
		<?php	
		}
		?>
	</ul>										

