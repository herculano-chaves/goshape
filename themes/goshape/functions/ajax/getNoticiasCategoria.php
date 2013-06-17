<?php
require('../../../../../wp-blog-header.php');

$catId = $_POST['catId'];

$args = array(
				'post_type' => 'post',
				'cat' => $catId 
			);
query_posts( $args );

$i = 1;
while ( have_posts() ) : the_post(); 
	if($i!=1 and $i % 3 == 0) { $marginRight = 'style="margin-right:0;"'; } else { $marginRight = ''; }
	
	$category = get_the_category( $post->ID );
	
	$video_url = get_post_meta($post->ID, 'videoUrl_post', true);
?>
  <div class="item" <?php echo $marginRight; ?>>
    <a href="<?php echo get_permalink( $post->ID ); ?>">
      <div class="box-topo categoria-<?php echo end($category)->slug; ?>"><?php echo end($category)->name; ?></div>  
        <div class="conteudo">
          <span class="data"><?php echo formataData($post->post_date, 'datacompleta'); ?></span>
          <h3 class="titulo"><?php echo truncateLetter($post->post_title, 60); ?></h3>
            
            <?php 
                if($video_url)
                {
                    getVideoYoutube($video_url, '323', '200');
                } 
                elseif(get_the_post_thumbnail( $post->ID, 'noticias-lista', $attr )) 
                {
                    $attr = array(
                        'alt'	=> trim(strip_tags( $post->post_title )),
                        'title'	=> trim(strip_tags( $post->post_title )),
                        'class'	=> "noticias-large"
                    );
                    echo get_the_post_thumbnail( $post->ID, 'noticias-lista', $attr ); 
                    
                } else {
                    echo '<br />';
                    echo $post->post_excerpt;
                }
            ?>
            
        </div>
        </a>
        <?php 
        # ADD SHARE
        $midiasUrl = urlencode(get_permalink($post->ID));
        $midiasTitle = urlencode($post->post_title);
        $midiasW = 358;
        include( '../../share.php' );
        ?>
        <div class="clear"></div>
    </div>
  <?php 
	$i++;
  endwhile; // end of the loop. 
  ?>