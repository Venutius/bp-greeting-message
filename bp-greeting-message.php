<?php
/**
 * Plugin Name:       BuddyPress Greeting Message
 * Plugin URI:        https://wordpress.org/plugins/bp-greeting-message/
 * Description:       Display a Greeting Message on BuddyPress Activity Screen.
 * Version:           1.0.3
 * Author:            xarbo
 * Author URI:        https://xarbo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 4.0
 * Tested up to:      4.5.3
 *
 * Text Domain:      bp-greeting-message
 * Domain Path:      /lang/
 *
 * @link              https://xarbo.com/
 * @since             1.0.0
 * @package           BP_Greeting_Message
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files
require_once( 'includes/class-bp-greeting-message.php' );
require_once( 'includes/class-bp-greeting-message-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-bp-greeting-message-admin-api.php' );
require_once( 'includes/class-bp-greeting-message-frontend.php' );

/**
 * Returns the main instance of BP_Greeting_Message to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object BP_Greeting_Message
 */
function BP_Greeting_Message() {
	$instance = BP_Greeting_Message::instance( __FILE__, '1.0.3' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = BP_Greeting_Message_Settings::instance( $instance );
	}

	return $instance;
}

BP_Greeting_Message();

new BP_Greeting_Message_Frontend( __FILE__, '1.0.3' );