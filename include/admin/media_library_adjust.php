<?php
/**
 * Shayan IPTC Embed add a column containing a button to media library
 */
use IHS\EasyIptc;
use CSD\Image\Image;


// add column to media library
function column_iptc_embed($columns) {
    $columns['iptc_embed'] = __('Embed IPTC');
    return $columns;
} 
add_filter( 'manage_media_columns', 'column_iptc_embed' );
 
function column_iptc_embed_row($columnName, $media_id){
    if($columnName == 'iptc_embed'){
        if ( get_post_mime_type($media_id) == 'image/jpeg' ) {
            echo '<div class="sh-iptc-embed-button-general sh-iptc-embed-button" data-image-id="' . $media_id . '">Embed IPTC</div>';
            $attachment_thumb_path = sh_convert_url_to_path( wp_get_attachment_thumb_url($media_id) );
            // $image = Image::fromFile(get_attached_file($media_id));
            // if ( $image->checkXmp() ) {
            //     $xmp = $image->getXmp();
            //     print_r( $xmp->getPhotographerName() . ' - ' . $xmp->getCopyright() . ' - ' . $xmp->getCopyrightUrl() );
            // }
        }
    }
}
add_filter( 'manage_media_custom_column', 'column_iptc_embed_row', 10, 2 );


// ajax js function to handle button click and get response, for IPTC Embed demands
add_action( 'admin_footer', 'admin_footer_javascripts' ); // Write our JS below here
function admin_footer_javascripts() { ?>
	<script type="text/javascript" >
	jQuery('.sh-iptc-embed-button').click(function($){
    ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>'; // get ajaxurl
		var data = {
			'action': 'handle_sh_iptc_embed_action',
			'image_id': jQuery(this).data('image-id')
		};
		var button = jQuery(this);
        button.addClass("sh-wait-for-response");
        button.removeClass("sh-iptc-embed-button");
		
		jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {
                if ( response == null || response == 0 || response == 400 ) {
                    button.text("<?php echo __( 'Failed to embed', 'shayan-iptc-embed' ); ?>");
                } else if ( response == 401 ) {
                    button.text("<?php echo __( 'Unable to embed this format', 'shayan-iptc-embed' ); ?>"); // only embed jpeg files
                } else if ( response == 402 ) {
                    button.text("<?php echo __( 'Unable to embed this size', 'shayan-iptc-embed' ); ?>"); // won't embed large files
                } else if ( response == 200 ) {
                    button.text("<?php echo __( 'Successfully embeded', 'shayan-iptc-embed' ); ?>");
                } else if ( response == 201 ) {
                    button.text("<?php echo __( 'Already embeded', 'shayan-iptc-embed' ); ?>");
                }
                button.addClass("sh-grey-out");
                button.removeClass("sh-wait-for-response");
            }
        });
	});
	</script><?php
}

// ajax callback action that get and check image sizes, check the format, check embed iptc in thumbnail as a sample, and fire iptc embed function
function sh_ajax_functions() {
	if ( is_admin() ) {
		add_action( 'wp_ajax_handle_sh_iptc_embed_action', 'handle_sh_iptc_embed_action_callback' );
		add_action( 'wp_ajax_nopriv_handle_sh_iptc_embed_action', 'handle_sh_iptc_embed_action_callback' );
	}
}
add_action( 'admin_init', 'sh_ajax_functions' );
function handle_sh_iptc_embed_action_callback() {
    $attachment_id = $_POST['image_id'];
    $type = get_post_mime_type($attachment_id);
    if ( $type != 'image/jpeg' ) { 
        echo 401;
        wp_die();
        return;
    }
    
    $filesize = wp_get_attachment_metadata( $attachment_id )['filesize'];
    if ( $filesize > 5*1000*1000 ) { 
        echo 402;
        wp_die();
        return;
    }
    
    $attachment_thumb_path = sh_convert_url_to_path(wp_get_attachment_thumb_url($attachment_id));
    if ( has_iptc_embeded( get_attached_file($media_id), 'iptc', array('PhotographerName', 'Copyright', 'CreditLine') ) ) {
        echo 201;
        wp_die();
        return;
    }

    // fire the iptc embed function on all image sizes path
    $attachment_sizes = sh_get_attachment_all_sizes_path($attachment_id);
    foreach ( $attachment_sizes as $path ) {
        $is_done = do_iptc_embed($path);
        if ( ! $is_done ) {
            echo 400;
            wp_die();
            return;
        }
    }
    echo 200;
    wp_die();
}


