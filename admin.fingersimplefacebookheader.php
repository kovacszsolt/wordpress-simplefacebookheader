<?php

/**
 * Class FingerSimpleFacbookHeaderSettings
 * Admin Setting page
 */
class FingerSimpleFacbookHeaderSettings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Option Name
	 * @var string
	 */
	private $_option_name = 'finger_simple_facbook_settings';

	/**
	 * Wordpress page ID
	 * @var string
	 */
	private $_page_name = 'finger-simple-facbook-settings';

	/**
	 * Facebook parameters
	 * @var array
	 */
	private $_parameters = array(
		array(
			'id'    => 'title',
			'type'  => 'text',
			'title' => 'Frontend Page Title'
		),
		array(
			'id'    => 'description',
			'type'  => 'text',
			'title' => 'Frontend Page Description'
		),
		array(
			'id'    => 'image',
			'type'  => 'text',
			'title' => 'Default Thumbnail'
		),
		array(
			'id'    => 'pageid',
			'type'  => 'text',
			'title' => 'Facebook Page ID'
		),
		array(
			'id'    => 'appid',
			'type'  => 'text',
			'title' => 'Facebook Application ID'
		)
	);

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_footer', 'media_selector_print_scripts' );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'Simple Facbook Settings',
			'Simple Facbook',
			'manage_options',
			$this->_page_name,
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( $this->_option_name );
		?>
        <div class="wrap">
            <h1>Simple Facebook Settings</h1>
            <form method="post" action="options.php">
				<?php
				settings_fields( 'my_option_group' );
				do_settings_sections( $this->_page_name );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}


	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'my_option_group', // Option group
			$this->_option_name, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'Facebook Share Settings', // Title
			array( $this, '' ), // Callback
			$this->_page_name // Page
		);
		// Create Item from parameters
		foreach ( $this->_parameters as $_parameter ) {
			switch ( $_parameter['type'] ) {
				case 'text':
					add_settings_field(
						$_parameter['id'],
						$_parameter['title'],
						array( $this, '_text_callback' ),
						$this->_page_name,
						'setting_section_id',
						$_parameter['id']
					);
					break;
			}

		}


	}

	/**
     * Text Callback
	 * @param string $name Registration Name
	 */
	public function _text_callback( $name ) {
		printf(
			'<input type="text" id="' . $name . '" name="' . $this->_option_name . '[' . $name . ']" value="%s" />',
			isset( $this->options[ $name ] ) ? esc_attr( $this->options[ $name ] ) : ''
		);
	}


	/**
     * Sanitize from POST input items
	 * @param $input
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_input = array();
		foreach ( $this->_parameters as $_parameter ) {
			if ( isset( $input[ $_parameter['id'] ] ) ) {
				$new_input[ $_parameter['id'] ] = sanitize_text_field( $input[ $_parameter['id'] ] );
			}
		}
		return $new_input;
	}
}
// Initialization
$_FingerSimpleFacbookHeaderSettings = new FingerSimpleFacbookHeaderSettings();