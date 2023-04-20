<?php
/**
 * Shayan IPTC Embed core functions that check and perform the embed
 */
use IHS\EasyIptc;
use CSD\Image\Image;

 
// check the file if has IPTC attribute has embed
function has_iptc_embeded( $path, $meta_type = 'iptc', $attributes = array('PhotographerName') ) {
    if ( $path == null || $path == '' ) return false;
    $iptc_details = get_option('shayan_iptc_embed_options');
    switch ($meta_type) {
        case 'iptc' :
            $img = new EasyIptc($path);
            foreach ( $attributes as $attribute ) {
                if ( $attribute == 'PhotographerName' && $img->get(IPTC_BYLINE) != $iptc_details['photographer_name'] ) return false;
                if ( $attribute == 'CreditLine' && $img->get(IPTC_CREDIT) != $iptc_details['credit'] ) return false;
                if ( $attribute == 'Copyright' && $img->get(IPTC_COPYRIGHT_STRING) != $iptc_details['copyright_notice'] ) return false;
            }
            break;
        case 'xmp' :
            $image = Image::fromFile($path);
            $xmp = $image->getXmp();
            foreach ( $attributes as $attribute ) {
                if ( $attribute == 'PhotographerName' && $xmp->getPhotographerName != $iptc_details['photographer_name'] ) return false;
                if ( $attribute == 'Copyright' && ( $xmp->getCopyright == null || $xmp->getCopyright == false || $xmp->getCopyright == '' ) ) return false;
                if ( $attribute == 'CopyrightUrl' && ( $xmp->getCopyrightUrl == null || $xmp->getCopyrightUrl == false || $xmp->getCopyrightUrl == '' ) ) return false;
            }
            break;
    }
    return true;
}


// create an array of attachement file paths (all sizes)
function sh_get_attachment_all_sizes_path($attachment_id) {
    $paths = array();
    $paths[] = get_attached_file($attachment_id);
    $pos = strrpos( get_attached_file( $attachment_id ), '/');
    $base_path = substr( get_attached_file( $attachment_id ), 0, $pos + 1);
    $metadata = wp_get_attachment_metadata($attachment_id);
    foreach ( $metadata['sizes'] as $size ) {
        $paths[] = $base_path . $size['file'];
    }
    return $paths;
}



// the core function for embeding iptc details to image
function do_iptc_embed($path) {
    if ( ! file_exists($path) ) return false;

    $iptc_details = get_option('shayan_iptc_embed_options');
    if ( ! $iptc_details ) return false;
    
    $image = Image::fromFile($path);
    if ( $image->checkXmp() ) {
        $xmp = $image->getXmp();
        $xmp->setPhotographerName($iptc_details['photographer_name']);
        $xmp->setCopyright($iptc_details['copyright_notice']);
        $xmp->setCopyrightUrl($iptc_details['copyright_url']);
        $image->save();
    }

    $img = new EasyIptc($path);
    $img->set(IPTC_COPYRIGHT_STRING,$iptc_details['copyright_notice']);
    $img->set(IPTC_CREDIT,$iptc_details['credit']);
    $img->set(IPTC_BYLINE,$iptc_details['photographer_name']);
    $response = $img->write();
	if ( $response == false ) {
	    return false;
	} else {
	    return true;
	}
}

// convert url to path
function sh_convert_url_to_path($url) {
    return str_replace( get_home_url(), get_home_path(), $url );
}
