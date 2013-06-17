<?php
add_action( 'add_meta_boxes', 'add_videoUrl_metabox' );
	
// Add the Vídeos URL Meta Box
function add_videoUrl_metabox() {
    add_meta_box('videoUrl_meta', 'Url do vídeo', 'videoUrl_meta', 'noticias', 'normal', 'low');
}

// HTML do Vídeos URL Metabox
function videoUrl_meta() {
    global $post;
	
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="videoUrl_meta_noncename" id="videoUrl_meta_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    // Get the location data if its already been entered
    $video_url = get_post_meta($post->ID, 'videoUrl_post', true);

	echo '
	<div style="float:left;">
		<input type="text" id="videoUrl_post" name="videoUrl_post" style="width:400px;" value="'.$video_url.'" /><br />
		<p style="color:#aaa; font-weight:bold;">Ex.: http://www.youtube.com/watch?v=IioQZpfGwpA</p>
		<br /><br />
		<p style="color:#990000;">Se a url for válida, o vídeo aparecerá ao lado após salvar ou atualizar o post.</p>
	</div>';
	
	if(!empty($video_url))
	{
		$replace = preg_replace('#(http://www.youtube.com)?/(v/([-|~_0-9A-Za-z]+)|watch\?v\=([-|~_0-9A-Za-z]+)&?.*?)#i', '
			   		<iframe width="400" height="250" frameborder="0" allowfullscreen="" src="http://www.youtube.com/embed/$4?fs=1&amp;feature=oembed&amp;wmode=transparent&amp;rel=0"></iframe>',$video_url);
		echo '
		<div style="float:right;">
			'.$replace.'
		</div>
		';
	}
	
	echo '<div style="clear:both"></div>';
	 
}

// Save the Metabox Data
function save_videoUrl_meta($post_id, $post) {
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['videoUrl_meta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
 
    $events_meta['videoUrl_post'] = $_POST['videoUrl_post'];
 
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
 
add_action('save_post', 'save_videoUrl_meta', 1, 2); // save the custom fields
?>