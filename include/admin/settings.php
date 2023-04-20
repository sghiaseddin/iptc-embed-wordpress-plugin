<?php
/**
 * Shayan IPTC Embed settings page and form
 */
 
// add menu item for settings page
function shayan_iptc_embed_register_options_page() {
  add_options_page('Shayan IPTC Embed Settings', 'IPTC Settings', 'manage_options', 'shayan-iptc-embed', 'shayan_iptc_embed_options_form_callback');
}
add_action('admin_menu', 'shayan_iptc_embed_register_options_page');


// settings page callback function for its content
function shayan_iptc_embed_options_form_callback() { ?>
    <style>
        body.settings_page_shayan-iptc-embed table.form-table {
            width: 100%;
            max-width: 1024px;
        }
        body.settings_page_shayan-iptc-embed table.form-table * {
            text-align: inherit;
        }
        body.settings_page_shayan-iptc-embed table.form-table th {
            width: 280px;
        }
        body.settings_page_shayan-iptc-embed table.form-table td > input {
            width: 100%;
        }
    </style>
    <div>
        <h2>Photographer Details</h2>
        <form action="options.php" method="post">
            <?php 
            settings_fields( 'shayan_iptc_embed_options' ); 
            do_settings_sections( 'shayan_iptc_embed_plugin' ); 
            ?>
            <input
              type="submit"
              name="submit"
              class="button button-primary"
              value="<?php esc_attr_e( 'Save' ); ?>"
            />
        </form>
    </div><?php
}


// register settings for plugin
function shayan_iptc_embed_settings_init() {
	register_setting( 'shayan_iptc_embed_options', 'shayan_iptc_embed_options', 'shayan_iptc_embed_validate_plugin_settings'  );
    add_settings_section( 'section_one', '', 'sh_iptc_section_one_text', 'shayan_iptc_embed_plugin' );
    add_settings_field( 'shayan_iptc_embed_plugin_photographer_name', 'Photographer Name / Creator', 'shayan_iptc_embed_plugin_photographer_name', 'shayan_iptc_embed_plugin', 'section_one' );
    add_settings_field( 'shayan_iptc_embed_plugin_credit', 'Organization or Agancy / Credit Line', 'shayan_iptc_embed_plugin_credit', 'shayan_iptc_embed_plugin', 'section_one' );
    add_settings_field( 'shayan_iptc_embed_plugin_copyright_notice', 'Copyright Notice', 'shayan_iptc_embed_plugin_copyright_notice', 'shayan_iptc_embed_plugin', 'section_one' );
    add_settings_field( 'shayan_iptc_embed_plugin_copyright_url', 'Web Statement of Rights / Copyright Info URL', 'shayan_iptc_embed_plugin_copyright_url', 'shayan_iptc_embed_plugin', 'section_one' );
}
add_action( 'admin_init', 'shayan_iptc_embed_settings_init' );


// define the fields
function sh_iptc_section_one_text() {
  echo '<p>Thses details will be used to attach to images as IPTC and XMP metadata schema.</p>';
}
function shayan_iptc_embed_plugin_photographer_name() {
    $options = get_option( 'shayan_iptc_embed_options' );
    echo '<input id="shayan_iptc_embed_plugin_photographer_name" name="shayan_iptc_embed_options[photographer_name]" type="text" value="' . esc_attr( $options['photographer_name'] ) . '" />';
}
function shayan_iptc_embed_plugin_credit() {
    $options = get_option( 'shayan_iptc_embed_options' );
    echo '<input id="shayan_iptc_embed_plugin_credit" name="shayan_iptc_embed_options[credit]" type="text" value="' . esc_attr( $options['credit'] ) . '" />';
}
function shayan_iptc_embed_plugin_copyright_notice() {
    $options = get_option( 'shayan_iptc_embed_options' );
    echo '<input id="shayan_iptc_embed_plugin_copyright_notice" name="shayan_iptc_embed_options[copyright_notice]" type="text" value="' . esc_attr( $options['copyright_notice'] ) . '" />';
}
function shayan_iptc_embed_plugin_copyright_url() {
    $options = get_option( 'shayan_iptc_embed_options' );
    echo '<input id="shayan_iptc_embed_plugin_copyright_url" name="shayan_iptc_embed_options[copyright_url]" type="text" value="' . esc_attr( $options['copyright_url'] ) . '" />';
}


// validate inputs
function shayan_iptc_embed_validate_plugin_settings( $input ) {
    $output['photographer_name'] = sanitize_text_field( $input['photographer_name'] );
    $output['credit'] = sanitize_text_field( $input['credit'] );
    $output['copyright_notice'] = sanitize_text_field( $input['copyright_notice'] );
    $output['copyright_url'] = sanitize_text_field( $input['copyright_url'] );
    return $output;
}