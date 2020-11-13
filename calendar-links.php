<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Calendar_Links
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Calendar Links
 * Plugin URI:        http://example.com/calendar-links-uri/
 * Description:       Using this plugin you can generate links to add events to calendar systems like Microsoft Outlook, Google Calendar and Apple Calendar
 * Version:           1.0.0
 * Author:            Ricardo Viana
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       calendar-links
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-calendar-links-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calendar-links-activator.php';
	Calendar_Links_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-calendar-links-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calendar-links-deactivator.php';
	Calendar_Links_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-calendar-links.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0

function run_calendar_links() {
	global $calendar_links;

	// Instantiate only once.
	if(!isset($calendar_links)) {
		$calendar_links = new Calendar_Links();
		$calendar_links->run();
	}
	return $calendar_links;

}
run_calendar_links();
 */

/**
 * The classes responsible to generate calendar links
 * https://github.com/spatie/calendar-links
 */
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Link.php';
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Generator.php';
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Exceptions/InvalidLink.php';
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Generators/Google.php';
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Generators/Ics.php';
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Generators/WebOutlook.php';
require_once plugin_dir_path( __FILE__ )  . 'vendor/php-calendar-links/src/Generators/Yahoo.php';

function calendar_links($title, $from, $to, $description = '', $address ='', $btnClass='btn btn--full btn--blue calendar-links') {

	try {
		$link = \Spatie\CalendarLinks\Link::create($title, $from, $to)
          ->description($description)
          ->address($address);

		return '<a
		             data-calendar-link-apple="'.$link->ics().'"
		             data-calendar-link-google="'.$link->google().'"
		             data-calendar-link-outlook="'.$link->webOutlook().'"
		             data-calendar-link-yahoo="'.$link->yahoo().'"
		             class="'.$btnClass.'" >
		            <span class="icon-calendar"></span> Agendar</a>';

		/*return '<ul class="calendar-links-items" style="display: none">
        <li class="link"><a target="_blank" href="'.$link->ics().'">
        	<i class="icon-apple"></i> Apple  </a></li>
        <li class="link"><a target="_blank" href="'.$link->google().'">
        	<i class="icon-google"></i> Google <em>(online)</em> </a></li>
        <li class="link"><a target="_blank" href="'.$link->webOutlook().'">
        	<i class="icon-outlook"></i> Outlook.com <em>(online)</em> </a></li>
        <li class="link"><a target="_blank" href="'.$link->yahoo().'">
        	<i class="icon-yahoo"></i> Yahoo <em>(online)</em> </a></li>
      </ul>';*/

	} catch (\Exception $e) {
		return '';
	}
}
