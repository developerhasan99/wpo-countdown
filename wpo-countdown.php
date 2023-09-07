<?php
/**
 * Plugin Name
 *
 * @package           WPO Countdown
 * @author            Mehedi Hasan
 * @copyright         2023 Mehedi Hasan
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WP Offer Countdown - Sells booster
 * Plugin URI:        #
 * Description:       A Simple plugin that helps you to boost sells by adding an offer countdown to your site globally.
 * Version:           1.0.0
 * Requires at least: 6
 * Requires PHP:      7.4
 * Author:            Mehedi Hasan
 * Author URI:        https://www.upwork.com/freelancers/~01e0d306e3c729d12e
 * Text Domain:       wpo-countdown
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Add the administration menu for the wpo Countdown Bar plugin.
 *
 * This function adds a submenu page under the 'themes.php' parent menu
 * for managing the wpo Countdown Bar plugin settings.
 *
 * @return void
 */
function wpo_add_admin_menu() {
	add_submenu_page(
		'themes.php',                  // Parent menu slug.
		'WPO Countdown',           // Page title.
		'WPO Countdown',           // Menu title.
		'manage_options',              // Capability required.
		'wpo-countdown-bar',           // Menu slug.
		'wpo_render_admin_page'        // Callback function to render the page.
	);
}
add_action( 'admin_menu', 'wpo_add_admin_menu' );

/**
 * Initialize the settings for the wpo Countdown Bar plugin.
 *
 * This function sets up the settings fields, sections, and registration
 * for the wpo Countdown Bar plugin settings.
 *
 * @return void
 */
function wpo_initialize_settings() {
	$settings_fields   = array(
		'countdown_date'            => 'Countdown Date',
		'countdown_heading_text'    => 'Countdown Heading Text',
		'countdown_subheading_text' => 'Countdown Subheading Text',
		'day_label'                 => 'Day Label',
		'hour_label'                => 'Hour Label',
		'minute_label'              => 'Minute Label',
		'second_label'              => 'Second Label',
		'action_btn_text'           => 'Action Button Text',
		'action_btn_link'           => 'Action Button Link',
	);
	$appearance_fields = array(
		'banner_background_color'     => 'Banner Background Color',
		'banner_texts_color'          => 'Banner Texts Color',
		'action_btn_texts_color'      => 'Action Button Texts Color',
		'action_btn_background_color' => 'Action Button Background Color',
	);

	// Add the settings section for the Countdown Bar settings.
	add_settings_section( 'wpo_countdown_bar_settings_section', 'Countdown Bar Settings', '', 'wpo-countdown-bar' );

	// Loop through fields and add settings fields for each.
	foreach ( $settings_fields as $field => $label ) {
		add_settings_field( $field, $label, 'wpo_render_field', 'wpo-countdown-bar', 'wpo_countdown_bar_settings_section', $field );
	}

	// Add the settings section for Countdown Bar Appearance.
	add_settings_section( 'wpo_countdown_bar_appearance_section', 'Countdown Bar Appearance', '', 'wpo-countdown-bar' );

	// Add the settings section for the Countdown Bar settings.
	foreach ( $appearance_fields as $field => $label ) {
		add_settings_field( $field, $label, 'wpo_render_field', 'wpo-countdown-bar', 'wpo_countdown_bar_appearance_section', $field );
	}

	// Register the plugin settings group and fields.
	register_setting( 'wpo_countdown_bar_settings_group', 'wpo_countdown_bar_settings' );
}
add_action( 'admin_init', 'wpo_initialize_settings' );

/**
 * Render a settings field input.
 *
 * This function generates and outputs an HTML input field element for a specific settings field.
 *
 * @param string $args Name of the setting field.
 *
 * @return void
 */
function wpo_render_field( $args ) {
	$settings = get_option( 'wpo_countdown_bar_settings' );
	$value    = isset( $settings[ $args ] ) ? esc_attr( $settings[ $args ] ) : '';

	$input_type = 'countdown_date' === $args ? 'datetime-local' : 'text';

	$field_class = str_contains( $args, 'color' ) ? 'color-picker' : '';

	echo '<input type="' . esc_attr( $input_type ) . '" name="' . esc_attr( "wpo_countdown_bar_settings[$args]" ) . '" value="' . esc_attr( $value ) . '" class="regular-text ' . esc_attr( $field_class ) . '" />';
}

/**
 * Render the administration page for the wpo Countdown Bar settings.
 *
 * This function outputs the HTML content for the administration page, including
 * the form fields for settings, sections, and a submit button.
 *
 * @return void
 */
function wpo_render_admin_page() {
	$settings = get_option( 'wpo_countdown_bar_settings' );
	?>
<div class="wrap">
    <h2>WP Offer Countdown - Sells booster</h2>
    <div class="notice"
        style="padding: 0.5rem 1rem; border-color: #0046ff; display: grid; grid-template-columns: 1fr 111px; align-items: center; gap: 15px; flex-wrap: wrap;">
        <div class="notice-content">
            <h3>Looking for a WordPress Developer?</h3>
            <p>Hello! I'm Mehedi Hasan, a freelance developer specializing in WordPress and React.js. The mind behind
                the "wp offer countdown" plugin, I'm always eager for new opportunities. If you're in need of an
                experienced developer, feel free to reach out.
            </p>
        </div>
        <a target="_blank" href="https://www.upwork.com/freelancers/~01e0d306e3c729d12e"
            style="text-decoration: none; color: #fff; background-color: #0046ff; border-radius: 5px; padding: 12px 25px; font-size: 16px;">Hire
            Me</a>
    </div>
    <form method="post" action="options.php">
        <?php settings_fields( 'wpo_countdown_bar_settings_group' ); ?>
        <?php do_settings_sections( 'wpo-countdown-bar' ); ?>
        <?php submit_button(); ?>
    </form>
</div>
<?php
}

/**
 * Insert the Countdown Bar into the website footer.
 *
 * This function retrieves settings from the plugin options, and then
 * generates and outputs the Countdown Bar HTML based on the retrieved settings.
 *
 * @return void
 */
function wpo_insert_countdown_bar() {
	$settings = get_option( 'wpo_countdown_bar_settings' );

	$ending_date                 = isset( $settings['countdown_date'] ) ? gmdate( 'm-j-Y, g:i A', strtotime( $settings['countdown_date'] ) ) : 'Set ending date in dashboard!';
	$countdown_heading_text      = isset( $settings['countdown_heading_text'] ) ? esc_attr( $settings['countdown_heading_text'] ) : 'Enter your heading in dashboard!';
	$countdown_subheading_text   = isset( $settings['countdown_subheading_text'] ) ? esc_attr( $settings['countdown_subheading_text'] ) : 'Enter your subheading in dashboard!';
	$action_btn_text             = isset( $settings['action_btn_text'] ) ? esc_attr( $settings['action_btn_text'] ) : 'Grab the offer!';
	$action_btn_link             = isset( $settings['action_btn_link'] ) ? esc_attr( $settings['action_btn_link'] ) : '#';
	$banner_background_color     = isset( $settings['banner_background_color'] ) ? esc_attr( $settings['banner_background_color'] ) : '#102179';
	$banner_texts_color          = isset( $settings['banner_texts_color'] ) ? esc_attr( $settings['banner_texts_color'] ) : '#eeeeee';
	$action_btn_texts_color      = isset( $settings['action_btn_texts_color'] ) ? esc_attr( $settings['action_btn_texts_color'] ) : '#ffffff';
	$action_btn_background_color = isset( $settings['action_btn_background_color'] ) ? esc_attr( $settings['action_btn_background_color'] ) : '#dc111b';
	?>
<div class="woc-wrapper" style="--background-color: <?php echo esc_attr( $banner_background_color ); ?>; z-index: 99;">
    <div class="woc-inner-wrapper">
        <button class="woc-toggle-btn"
            style="--color: <?php echo esc_attr( $banner_texts_color ); ?>; --background-color: <?php echo esc_attr( $banner_background_color ); ?>;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        <div class="woc-container">
            <div class="woc-titles-wrapper">
                <h3 style="--color: <?php echo esc_attr( $banner_texts_color ); ?>">
                    <?php echo esc_html( esc_attr( $countdown_heading_text ) ); ?></h3>
                <p style="--color: <?php echo esc_attr( $banner_texts_color ); ?>">
                    <?php echo esc_html( esc_attr( $countdown_subheading_text ) ); ?></p>
            </div>
            <div class="woc-countdown-wrapper">
                <div class="woc-flipdown-wrapper">
                    <div id="woc-countdown"
                        style="--heading-font-color: <?php echo esc_attr( $banner_texts_color ); ?>; --separator-color: #eeeeee; --flipper-color: #eeeeee; --font-color: #050608;"
                        class="flipdown"></div>
                </div>
                <a href="<?php echo esc_url( $action_btn_link ); ?>" class="woc-action-button"
                    style="--color: <?php echo esc_attr( $action_btn_texts_color ); ?>; --background-color: <?php echo esc_attr( $action_btn_background_color ); ?>;">
                    <?php echo esc_html( $action_btn_text ); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
}
add_action( 'wp_footer', 'wpo_insert_countdown_bar' );


/**
 * Enqueue admin styles and scripts
 *
 * @return void
 */
function wpo_countdown_bar_enqueue_admin_scripts() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'wpo-countdown-bar-style', plugin_dir_url( __FILE__ ) . 'style.css', array(), '1.0', 'all' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script( 'wpo-countdown-bar-script', plugin_dir_url( __FILE__ ) . 'admin-script.js', array( 'jquery', 'jquery-ui-datepicker', 'wp-color-picker' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'wpo_countdown_bar_enqueue_admin_scripts' );

/**
 * Enqueue styles and scripts for the wpo Countdown Bar plugin.
 *
 * This function enqueues the necessary styles and scripts, including
 * localization for plugin options used in JavaScript.
 *
 * @return void
 */
function wpo_countdown_bar_enqueue_scripts() {
	wp_enqueue_style( 'flipdown-style', plugin_dir_url( __FILE__ ) . 'flipdown.css', array(), '1.0', 'all' );
	wp_enqueue_style( 'woc-style', plugin_dir_url( __FILE__ ) . 'style.css', array(), '1.0', 'all' );
	wp_enqueue_script( 'flipdown-scripts', plugin_dir_url( __FILE__ ) . 'flipdown.js', '', array(), '1.0' );
	wp_enqueue_script( 'woc-script', plugin_dir_url( __FILE__ ) . 'script.js', '', array( 'flipdown' ), '1.0' );

	// Localize strings for woc options.
	$settings     = get_option( 'wpo_countdown_bar_settings' );
	$ending_date  = isset( $settings['countdown_date'] ) ? $settings['countdown_date'] : 0;
	$day_label    = isset( $settings['day_label'] ) ? $settings['day_label'] : 'Days';
	$hour_label   = isset( $settings['hour_label'] ) ? $settings['hour_label'] : 'Hours';
	$minute_label = isset( $settings['minute_label'] ) ? $settings['minute_label'] : 'Minutes';
	$second_label = isset( $settings['second_label'] ) ? $settings['second_label'] : 'Seconds';

	wp_localize_script(
		'woc-script',
		'woc_options',
		array(
			'countdown_date' => $ending_date,
			'day_label'      => $day_label,
			'hour_label'     => $hour_label,
			'minute_label'   => $minute_label,
			'second_label'   => $second_label,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'wpo_countdown_bar_enqueue_scripts' );