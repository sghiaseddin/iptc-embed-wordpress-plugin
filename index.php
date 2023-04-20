<?php
/**
 * Plugin Name: Shayan IPTC Embed
 * Plugin URI: https://sghiaseddin.com/
 * Description: This plugin get the global parameters of photo copywrite and add them to photos in the library on demand.
 * Version: 1.0.0
 * Author: Shayan Ghiaseddin
 * Author URI: https://sghiaseddin.com
 * Text Domain: shayan-iptc-embed
 * Requires at least: 5.9
 * Requires PHP: 7.4
 *
 */

defined( 'ABSPATH' ) || exit;

DEFINE('IPTC_OBJECT_NAME', '005');
DEFINE('IPTC_EDIT_STATUS', '007');
DEFINE('IPTC_PRIORITY', '010');
DEFINE('IPTC_CATEGORY', '015');
DEFINE('IPTC_SUPPLEMENTAL_CATEGORY', '020');
DEFINE('IPTC_FIXTURE_IDENTIFIER', '022');
DEFINE('IPTC_KEYWORDS', '025');
DEFINE('IPTC_RELEASE_DATE', '030');
DEFINE('IPTC_RELEASE_TIME', '035');
DEFINE('IPTC_SPECIAL_INSTRUCTIONS', '040');
DEFINE('IPTC_REFERENCE_SERVICE', '045');
DEFINE('IPTC_REFERENCE_DATE', '047');
DEFINE('IPTC_REFERENCE_NUMBER', '050');
DEFINE('IPTC_CREATED_DATE', '055');
DEFINE('IPTC_CREATED_TIME', '060');
DEFINE('IPTC_ORIGINATING_PROGRAM', '065');
DEFINE('IPTC_PROGRAM_VERSION', '070');
DEFINE('IPTC_OBJECT_CYCLE', '075');
DEFINE('IPTC_BYLINE', '080');
DEFINE('IPTC_BYLINE_TITLE', '085');
DEFINE('IPTC_CITY', '090');
DEFINE('IPTC_PROVINCE_STATE', '095');
DEFINE('IPTC_COUNTRY_CODE', '100');
DEFINE('IPTC_COUNTRY', '101');
DEFINE('IPTC_ORIGINAL_TRANSMISSION_REFERENCE', '103');
DEFINE('IPTC_HEADLINE', '105');
DEFINE('IPTC_CREDIT', '110');
DEFINE('IPTC_SOURCE', '115');
DEFINE('IPTC_COPYRIGHT_STRING', '116');
DEFINE('IPTC_CAPTION', '120');
DEFINE('IPTC_LOCAL_CAPTION', '121');

//function shayan_iptc_embed_include_files() {
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/ImageInterface.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Image.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/JPEG.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/JPEG/Segment.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/PNG.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/PNG/Chunk.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/PSD.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/PSD/IRB.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/WebP.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/WebP/Chunk.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Format/WebP/VP8XChunk.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Metadata/Aggregate.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Metadata/Exif.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Metadata/Exif2.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Metadata/Iptc.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Metadata/UnsupportedException.php' );
require_once( plugin_dir_path( __FILE__ ) . 'vendor/CSD/image/src/Metadata/Xmp.php' );
use CSD\Image\Image;

require_once( plugin_dir_path( __FILE__ ) . 'vendor/IHS/EasyIptc.php' );
use IHS\EasyIptc;

require_once( plugin_dir_path( __FILE__ ) . 'include/admin/settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'include/functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'include/admin/media_library_adjust.php' );


function shayan_iptc_embedder_admin_style() {
    wp_enqueue_style( 'sh-iptc-embed', plugins_url( 'include/admin/sh-iptc-embed-styles.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'shayan_iptc_embedder_admin_style');

