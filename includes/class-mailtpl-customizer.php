<?php

/**
 * All customizer aspects will go in here
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Mailtpl_Customizer {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = Mailtpl::defaults();

	}

	/**
	 * Add all panels to customizer
	 * @param $wp_customize
	 */
	public function register_customize_sections( $wp_customize ){

		$wp_customize->add_panel( 'mailtpl', array(
			'title'         => __( 'Email Templates', $this->plugin_name ),
			'description'   => __( 'Within the Email Templates customizer you can change how your WordPress Emails looks. It\'s fully compatible with WooCommerce and Easy Digital Downloads html emails', $this->plugin_name ),
		) );

		do_action('mailtpl/sections/before', $wp_customize );
		// Add sections
		$wp_customize->add_section( 'section_mailtpl_settings', array(
			'title' => __( 'Settings', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_section( 'section_mailtpl_template', array(
			'title' => __( 'Template', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_section( 'section_mailtpl_header', array(
			'title' => __( 'Email Header', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_section( 'section_mailtpl_body', array(
			'title' => __( 'Email Body', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_section( 'section_mailtpl_footer', array(
			'title' => __( 'Footer', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_section( 'section_mailtpl_test', array(
			'title' => __( 'Send test email', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		// Populate sections
		$this->settings_section( $wp_customize );
		$this->template_section( $wp_customize );
		$this->header_section( $wp_customize );
		$this->body_section( $wp_customize );
		$this->footer_section( $wp_customize );
		$this->test_section( $wp_customize );

		do_action('mailtpl/sections/after', $wp_customize );

	}

	/**
	 * Remover other panels and sections
	 * @param $active
	 * @param $section
	 *
	 * @return bool
	 */
	public function remove_other_sections( $active, $section ) {
		if ( isset( $_GET['mailtpl_display'] ) ) {
			if (
				in_array( $section->id,
					apply_filters( 'mailtpl/customizer_sections',
							array(  'section_mailtpl_footer',
									'section_mailtpl_template',
									'section_mailtpl_header',
									'section_mailtpl_body',
									'section_mailtpl_test',
									'section_mailtpl_settings'
							)
					)
				)
			) {
				return true;
			}
			return false;
		}
		return true;
	}

	/**
	 * Here we capture the page and show template acordingly
	 * @param $template
	 *
	 * @return string
	 */
	public function capture_customizer_page( $template ){

		if( is_customize_preview() && isset( $_GET['mailtpl_display'] ) && 'true' == $_GET['mailtpl_display'] ){
			return apply_filters( 'mailtpl/customizer_template', MAILTPL_PLUGIN_DIR . "/admin/templates/default.php");
		}
		return $template;
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'mailtpl-js', MAILTPL_PLUGIN_URL . '/admin/js/mailtpl-admin.js', '', $this->version, false );

	}

	/**
	 * Enqueue scripts for preview area
	 * @since 1.0.0
	 */
	public function enqueue_template_scripts(){
		wp_enqueue_script( 'mailtpl-front-js', MAILTPL_PLUGIN_URL . '/admin/js/mailtpl-public.js', array(  'jquery', 'customize-preview' ), $this->version, true );
		wp_enqueue_style( 'mailtpl-css', MAILTPL_PLUGIN_URL . '/admin/css/mailtpl-admin.css', '', $this->version, false );
	}

	/**
	 * Template Section
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function settings_section($wp_customize) {

		do_action('mailtpl/sections/settings/before_content', $wp_customize);

		$wp_customize->add_setting( 'mailtpl_opts[from_name]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['from_name'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_text_field',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_from_name', array(
				'label'         => __( 'From name', $this->plugin_name ),
				'type'          => 'text',
				'section'       => 'section_mailtpl_settings',
				'settings'      => 'mailtpl_opts[from_name]',
				'description'   => __('Default: ', $this->plugin_name ) . get_bloginfo('name')
			)
		) );

		$wp_customize->add_setting( 'mailtpl_opts[from_email]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['from_email'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_text_field',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_from_email', array(
				'label'         => __( 'From Email', $this->plugin_name ),
				'type'          => 'text',
				'section'       => 'section_mailtpl_settings',
				'settings'      => 'mailtpl_opts[from_email]',
				'description'   => __('Default: ', $this->plugin_name ) . get_bloginfo('admin_email')
			)
		) );


		do_action('mailtpl/sections/settings/after_content', $wp_customize);
	}


	/**
	 * Template Section
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function template_section($wp_customize) {

		do_action('mailtpl/sections/template/before_content', $wp_customize);

		$wp_customize->add_setting( 'mailtpl_opts[template]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['template'],
			'transport'             => 'refresh',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this, 'sanitize_templates'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_template', array(
				'label'         => __( 'Choose one', $this->plugin_name ),
				'type'          => 'select',
				'section'       => 'section_mailtpl_template',
				'settings'      => 'mailtpl_opts[template]',
				'choices'       => apply_filters( 'mailtpl/template_choices', array(
					'boxed'    => 'Boxed',
					'fullwidth' => 'Fullwidth'
				)),
				'description'   => ''
			)
		) );

		$wp_customize->add_setting( 'mailtpl_opts[body_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['body_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_body_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_template',
				'settings'      => 'mailtpl_opts[body_bg]',
				'description'   => __( 'Choose email background color', $this->plugin_name )
			)
		) );
		do_action('mailtpl/sections/template/after_content', $wp_customize);
	}


	/**
	 * Header section
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function header_section( $wp_customize ) {
		require_once MAILTPL_PLUGIN_DIR . '/includes/customize-controls/class-font-size-customize-control.php';
		do_action('mailtpl/sections/header/before_content', $wp_customize);

		// image logo
		$wp_customize->add_setting( 'mailtpl_opts[header_logo]', array(
			'type'                  => 'option',
			'default'               => '',
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'mailtpl_header', array(
				'label'         => __( 'Logo', $this->plugin_name ),
				'type'          => 'image',
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_logo]',
				'description'   => __( 'Add an image to use in header. Leave empty to use text instead', $this->plugin_name )
			)
		) );

		// image logo
		$wp_customize->add_setting( 'mailtpl_opts[header_logo_text]', array(
			'type'                  => 'option',
			'default'               => '',
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_text'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_header_logo_text', array(
				'label'         => __( 'Logo', $this->plugin_name ),
				'type'          => 'textarea',
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_logo_text]',
				'description'   => __( 'Add text to your mail header', $this->plugin_name )
			)
		) );
		// header alignment
		$wp_customize->add_setting( 'mailtpl_opts[header_aligment]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_aligment'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_alignment'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_aligment', array(
				'label'         => __( 'Aligment', $this->plugin_name ),
				'type'          => 'select',
				'default'       => 'center',
				'choices'       => array(
					'left'  => 'Left',
					'center'=> 'Center',
					'right' => 'Right'
				),
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_aligment]',
				'description'   => __( 'Choose alignment for header', $this->plugin_name )
			)
		) );

		// background color
		$wp_customize->add_setting( 'mailtpl_opts[header_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_header_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_bg]',
				'description'   => __( 'Choose header background color', $this->plugin_name )
			)
		) );
		// text size
		$wp_customize->add_setting( 'mailtpl_opts[header_text_size]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_text_size'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_text'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Font_Size_Customize_Control( $wp_customize,
			'mailtpl_header_text_size', array(
				'label'         => __( 'Text size', $this->plugin_name ),
				'type'          => 'mailtpl_send_mail',
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_text_size]',
				'description'   => __( 'Slide to change text size', $this->plugin_name )
			)
		) );

		// text color
		$wp_customize->add_setting( 'mailtpl_opts[header_text_color]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_text_color'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_header_text_color', array(
				'label'         => __( 'Text Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_text_color]',
				'description'   => __( 'Choose header text color', $this->plugin_name )
			)
		) );
		do_action('mailtpl/sections/header/after_content', $wp_customize);
	}

	/**
	 * Body section
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function body_section( $wp_customize ) {
		require_once MAILTPL_PLUGIN_DIR . '/includes/customize-controls/class-font-size-customize-control.php';
		do_action('mailtpl/sections/body/before_content', $wp_customize);

		// background color
		$wp_customize->add_setting( 'mailtpl_opts[email_body_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['email_body_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_email_body_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_body',
				'settings'      => 'mailtpl_opts[email_body_bg]',
				'description'   => __( 'Choose email body background color', $this->plugin_name )
			)
		) );
		// text size
		$wp_customize->add_setting( 'mailtpl_opts[body_text_size]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['body_text_size'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_text'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Font_Size_Customize_Control( $wp_customize,
			'mailtpl_body_text_size', array(
				'label'         => __( 'Text size', $this->plugin_name ),
				'type'          => 'mailtpl_send_mail',
				'section'       => 'section_mailtpl_body',
				'settings'      => 'mailtpl_opts[body_text_size]',
				'description'   => __( 'Slide to change text size', $this->plugin_name )
			)
		) );

		// text color
		$wp_customize->add_setting( 'mailtpl_opts[body_text_color]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['body_text_color'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_body_text_color', array(
				'label'         => __( 'Text Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_body',
				'settings'      => 'mailtpl_opts[body_text_color]',
				'description'   => __( 'Choose body text color', $this->plugin_name )
			)
		) );
		do_action('mailtpl/sections/body/after_content', $wp_customize);
	}

	/**
	 * Footer section
	 *
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function footer_section($wp_customize) {

		require_once MAILTPL_PLUGIN_DIR . '/includes/customize-controls/class-font-size-customize-control.php';
		do_action('mailtpl/sections/footer/before_content', $wp_customize);

		$wp_customize->add_setting( 'mailtpl_opts[footer_text]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_text'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_text'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_footer', array(
				'label'     => __( 'Footer text', $this->plugin_name ),
				'type'      => 'textarea',
				'section'   => 'section_mailtpl_footer',
				'settings'  => 'mailtpl_opts[footer_text]',
				'description'   => __('Change the email footer here', $this->plugin_name )
			)
		) );

		// footer alignment
		$wp_customize->add_setting( 'mailtpl_opts[footer_aligment]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_aligment'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_alignment'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_footer_aligment', array(
				'label'         => __( 'Aligment', $this->plugin_name ),
				'type'          => 'select',
				'default'       => 'center',
				'choices'       => array(
					'left'  => 'Left',
					'center'=> 'Center',
					'right' => 'Right'
				),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_aligment]',
				'description'   => __( 'Choose alignment for footer', $this->plugin_name )
			)
		) );

		// background color
		$wp_customize->add_setting( 'mailtpl_opts[footer_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_footer_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_bg]',
				'description'   => __( 'Choose footer background color', $this->plugin_name )
			)
		) );
		// text size
		$wp_customize->add_setting( 'mailtpl_opts[footer_text_size]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_text_size'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => array( $this,'sanitize_text'),
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Font_Size_Customize_Control( $wp_customize,
			'mailtpl_footer_text_size', array(
				'label'         => __( 'Text size', $this->plugin_name ),
				'type'          => 'mailtpl_send_mail',
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_text_size]',
				'description'   => __( 'Slide to change text size', $this->plugin_name )
			)
		) );
		// text color
		$wp_customize->add_setting( 'mailtpl_opts[footer_text_color]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_text_color'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => 'sanitize_hex_color',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_footer_text_color', array(
				'label'         => __( 'Text Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_text_color]',
				'description'   => __( 'Choose footer text color', $this->plugin_name )
			)
		) );

		// Powered by
		$wp_customize->add_setting( 'mailtpl_opts[footer_powered_by]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_powered_by'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_footer_powered_by', array(
				'label'         => __( 'Powered by', $this->plugin_name ),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_powered_by]',
				'type'          => 'select',
				'choices'       => array(
					'off'   => 'Off',
					'on'    => 'On',
				),
				'description'   => __( 'Display a tiny link to the plugin page', $this->plugin_name )
			)
		) );
		do_action('mailtpl/sections/footer/after_content', $wp_customize);
	}

	/**
	 * Send test email section
	 * @param $wp_customize Wp_Customize_Manager
	 */
	private function test_section( $wp_customize ) {
		require_once MAILTPL_PLUGIN_DIR . '/includes/customize-controls/class-send-mail-customize-control.php';

		do_action('mailtpl/sections/test/before_content', $wp_customize);

		// image logo
		$wp_customize->add_setting( 'mailtpl_opts[send_mail]', array(
			'type'                  => 'option',
			'default'               => '',
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Send_Mail_Customize_Control( $wp_customize,
			'mailtpl_test', array(
				'label'         => __( 'Send test email', $this->plugin_name ),
				'type'          => 'mailtpl_send_mail',
				'section'       => 'section_mailtpl_test',
				'settings'      => 'mailtpl_opts[send_mail]',
				'description'   => __( 'Save the template and then click the button to send a test email to admin email ', $this->plugin_name ) . get_bloginfo('admin_email')
			)
		) );
		do_action('mailtpl/sections/test/after_content', $wp_customize);
	}

	/**
	 * We let them use some safe html
	 * @param $input string to sanitize
	 *
	 * @return string
	 */
	public function sanitize_text( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}


	/**
	 * Sanitize aligment selects
	 * @param $input string to sanitize
	 *
	 * @return string
	 */
	public function sanitize_alignment( $input ) {
		$valid = array(
			'left',
			'right',
			'center',
		);

		if ( in_array( $input, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}
	/**
	 * Sanitize template select
	 * @param $input string to sanitize
	 *
	 * @return string
	 */
	public function sanitize_templates( $input ) {
		$valid = apply_filters( 'mailtpl/template_choices', array(
			'boxed'    => 'Simple Theme',
			'fullwidth' => 'Fullwidth'
		));

		if ( array_key_exists( $input, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}
}
