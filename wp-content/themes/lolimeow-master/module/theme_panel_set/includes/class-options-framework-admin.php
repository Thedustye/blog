<?php
/**
 * @package   Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

class Options_Framework_Admin {

	/**
     * Page hook for the options screen
     *
     * @since 1.7.0
     * @type string
     */
    protected $options_screen = null;

    /**
     * Hook in the scripts and styles
     *
     * @since 1.7.0
     */
    public function init() {

		// Gets options to load
    	$options = & Options_Framework::_optionsframework_options();

		// Checks if options are available
    	if ( $options ) {

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );

			// Add the required scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Settings need to be registered after admin_init
			add_action( 'admin_init', array( $this, 'settings_init' ) );

			// Adds options menu to the admin bar
			add_action( 'wp_before_admin_bar_render', array( $this, 'optionsframework_admin_bar' ) );

		}

    }

	/**
     * Registers the settings
     *
     * @since 1.7.0
     */
    function settings_init() {

		// Get the option name
		$options_framework = new Options_Framework;
	    $name = $options_framework->get_option_name();

		// Registers the settings fields and callback
		register_setting( 'optionsframework', $name, array ( $this, 'validate_options' ) );

		// Displays notice after options save
		add_action( 'optionsframework_after_validate', array( $this, 'save_options_notice' ) );

    }

	/*
	 * Define menu options
	 *
	 * Examples usage:
	 *
	 * add_filter( 'optionsframework_menu', function( $menu ) {
	 *     $menu['page_title'] = 'The Options';
	 *	   $menu['menu_title'] = 'The Options';
	 *     return $menu;
	 * });
	 *
	 * @since 1.7.0
	 *
	 */
	static function menu_settings() {

		$menu = array(

			// Modes: submenu, menu
            'mode' => 'submenu',

            // Submenu default settings
            'page_title' => __( 'Boxmoe????????????', 'theme-textdomain' ),
			'menu_title' => __( 'Boxmoe????????????', 'theme-textdomain' ),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'options-framework',
            'parent_slug' => 'themes.php',

            // Menu default settings
            'icon_url' => 'dashicons-admin-generic',
            'position' => '61'

		);

		return apply_filters( 'optionsframework_menu', $menu );
	}

	/**
     * Add a subpage called "Theme Options" to the appearance menu.
     *
     * @since 1.7.0
     */
	function add_custom_options_page() {
	$menu = $this->menu_settings();

	switch ($menu['mode']) {

		case 'menu':
		// http://codex.wordpress.org/Function_Reference/add_menu_page
			$this->options_screen = add_menu_page(
				$menu['page_title'],
				$menu['menu_title'],
				$menu['capability'],
				$menu['menu_slug'],
				array($this, 'options_page'),
				$menu['icon_url'],
				$menu['position']
			);
			break;
		default:
		// http://codex.wordpress.org/Function_Reference/add_submenu_page
			$this->options_screen = add_submenu_page(
				$menu['parent_slug'],
				$menu['page_title'],
				$menu['menu_title'],
				$menu['capability'],
				$menu['menu_slug'],
				array($this, 'options_page'));
			break;   
	}
}

	/**
     * Loads the required stylesheets
     *
     * @since 1.7.0
     */

	function enqueue_admin_styles( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		wp_enqueue_style( 'optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'css/optionsframework.css', array(),  Options_Framework::VERSION );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
     * Loads the required javascript
     *
     * @since 1.7.0
     */
	function enqueue_admin_scripts( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		// Enqueue custom option panel JS
		wp_enqueue_script(
			'options-custom',
			OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js',
			array( 'jquery','wp-color-picker' ),
			Options_Framework::VERSION
		);

		// Inline scripts from options-interface.php
		add_action( 'admin_head', array( $this, 'of_admin_head' ) );
	}

	function of_admin_head() {
		// Hook to add custom scripts
		do_action( 'optionsframework_custom_scripts' );
	}

	/**
     * Builds out the options panel.
     *
	 * If we were using the Settings API as it was intended we would use
	 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
	 * we'll call our own custom optionsframework_fields.  See options-interface.php
	 * for specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
     * @since 1.7.0
     */
/*
	 function options_page() { ?>

		<div id="optionsframework-wrap" class="wrap">

		<?php $menu = $this->menu_settings(); ?>
		<h2><?php echo esc_html( $menu['page_title'] ); ?></h2>

	    <h2 class="nav-tab-wrapper">
	        <?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'options-framework' ); ?>

	    <div id="optionsframework-metabox" class="metabox-holder">
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'optionsframework' ); ?>
				<?php Options_Framework_Interface::optionsframework_fields(); ?>
				<div id="optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'theme-textdomain' ); ?>" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'theme-textdomain' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'theme-textdomain' ) ); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>
			</div> 
		</div>
		<?php do_action( 'optionsframework_after' ); ?>
		</div> 

	<?php
	}
   */
	 function options_page() { ?>
		<div id="optionsframework-wrap" class="wrap">
            <div class="boxmoe-header-set">
                <h2>???????????????????????????</h2>
                <span>???????????????<?php echo THEME_VERSION;?> | <a href="https://www.boxmoe.com/468.html" target="_blank" rel="external nofollow" class="url themes-inf">????????????</a> |
                    <a href="https://jq.qq.com/?_wv=1027&amp;k=52f0L9P" target="_blank" rel="external nofollow" class="url themes-inf">?????????:24401689</a></span>
					<div id="versionss">??????????????????</div>
                <div class="setting-info">
            <?php $menu = $this->menu_settings(); ?>
			<?php settings_errors( 'options-framework' ); ?>
			<?php do_action( 'optionsframework_after' ); ?></div>
            </div>
			<div class="boxmoe-opt-main clearfix">
				<div class="opt-tab ">
				    <div class="title">
                        <span>Boxmoe??????</span>
                    </div>
					<div class="nav-tab-wrapper">
					<?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
					</div>
				</div>								
				<div id="optionsframework-metabox" class="metabox-holder opt-content  clearfix">
					<div id="optionsframework" class="postbox">
						<form action="options.php" method="post" class="uk-form">
							<?php settings_fields( 'optionsframework' ); ?>
							<?php Options_Framework_Interface::optionsframework_fields(); /* Settings */ ?>
							<div class="clear"></div>
							<div id="optionsframework-submit">
								<input type="submit" class="boxmoe-button" style="float: left;" name="update" value="<?php esc_attr_e( '????????????', 'boxmoe' ); ?>" />
								<input type="submit" class="boxmoe-button" name="reset" value="<?php esc_attr_e( '??????????????????', 'boxmoe' ); ?>" onclick="return confirm( '<?php print esc_js( __( '???????????????????????????????????????????????????????????????????????????????????????????????????', 'boxmoe' ) ); ?>' );" />
								<div class="clear"></div>
							</div>

							<div id="box" class="box">
							<img src="data:image/gif;base64,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">
							</div>

							<script>
								var timer  = null;
								box.onclick = function(){
									cancelAnimationFrame(timer);
									timer = requestAnimationFrame(function fn(){
										var oTop = document.body.scrollTop || document.documentElement.scrollTop;
										if(oTop > 0){
											document.body.scrollTop = document.documentElement.scrollTop = oTop - 50;
											timer = requestAnimationFrame(fn);
										}else{
											cancelAnimationFrame(timer);
										}
									});
								}
							</script>
						</form>
					</div> <!-- / #container -->
				</div>
			
			</div>
		</div>	 <!-- / .wrap -->


	<?php
	}   
	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @uses $_POST['reset'] to restore default options
	 */
	function validate_options( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'theme-textdomain' ), 'updated fade' );
			return $this->get_default_values();
		}

		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */

		$clean = array();
		$options = & Options_Framework::_optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		// Hook to run after validation
		do_action( 'optionsframework_after_validate', $clean );

		return $clean;
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', __( '???????????????', 'theme-textdomain' ), 'updated fade' );
	}

	/**
	 * Get the default values for all the theme options
	 *
	 * Get an array of all default values as set in
	 * options.php. The 'id','std' and 'type' keys need
	 * to be defined in the configuration array. In the
	 * event that these keys are not present the option
	 * will not be included in this function's output.
	 *
	 * @return array Re-keyed options configuration array.
	 *
	 */
	function get_default_values() {
		$output = array();
		$config = & Options_Framework::_optionsframework_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
			}
		}
		return $output;
	}

	/**
	 * Add options menu item to admin bar
	 */

	function optionsframework_admin_bar() {

		$menu = $this->menu_settings();

		global $wp_admin_bar;

		if ( 'menu' == $menu['mode'] ) {
			$href = admin_url( 'admin.php?page=' . $menu['menu_slug'] );
		} else {
			$href = admin_url( 'themes.php?page=' . $menu['menu_slug'] );
		}

		$args = array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => $menu['menu_title'],
			'href' => $href
		);

		$wp_admin_bar->add_menu( apply_filters( 'optionsframework_admin_bar', $args ) );
	}

}