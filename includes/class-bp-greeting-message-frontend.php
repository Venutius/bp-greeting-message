<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BP_Greeting_Message_Frontend {
	public $parent = null;
	public $settings = null;
	public $base;
	public $version;
	private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;
	private $template_path;
	public $template_url;
	public $home_url;
	public $site_url;
	public $_token;

	public function __construct( $file, $version ) {
		$this->parent        = BP_Greeting_Message();
		$this->settings      = $this->parent->settings;
		$this->_token        = $this->parent->_token;
		$this->base          = $this->parent->settings->base;
		$this->version       = $version;
		$this->dir           = dirname( $file );
		$this->file          = $file;
		$this->assets_dir    = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url    = esc_url( trailingslashit( plugins_url( '/assets/', $file ) ) );
		$this->template_path = trailingslashit( $this->dir ) . 'templates/';
		$this->template_url  = esc_url( trailingslashit( plugins_url( '/templates/', $file ) ) );
		$this->home_url      = trailingslashit( home_url() );
		$this->site_url      = trailingslashit( site_url() );

		add_action( 'init', array( $this, 'display_greeting' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_js_scripts' ), 20 );
	}

	public function get_option( $id = '' ) {
		if ( empty( $id ) ) {
			return false;
		}

		$data        = false;
		$option_name = $this->settings->base . $id;
		$option      = get_option( $option_name );

		if ( isset( $option ) ) {
			$data = $option;
		}

		return $data;
	}

	public function display_greeting() {
		$display_position = $this->get_option( 'display_position' );

		if ( isset( $display_position ) && $display_position == 'before' ) {
			add_action( 'bp_before_directory_activity_content', array( $this, 'get_greeting_html' ) );
			add_action( 'bp_before_group_activity_post_form', array( $this, 'get_greeting_html' ) );
			add_action( 'bp_before_member_activity_post_form', array( $this, 'get_greeting_html' ) );
		} else if ( isset( $display_position ) && $display_position == 'after' ) {
			add_action( 'template_notices', array( $this, 'get_greeting_html' ) );
			add_action( 'bp_after_group_activity_post_form', array( $this, 'get_greeting_html' ) );
			add_action( 'bp_after_member_activity_post_form', array( $this, 'get_greeting_html' ) );
		} else if ( isset( $display_position ) && $display_position == 'before-profile' ) {
			add_action( 'template_notices', array( $this, 'get_greeting_html' ) );
			add_action( 'bp_before_profile_content', array( $this, 'get_greeting_html' ) );

		}
	}

	public function get_greeting_html() {
		global $bp;

		$day_parts             = $this->get_option( 'day_parts' );
		$enable_custom_message = $this->get_option( 'enable_custom_message' );
		$custom_message        = $this->get_option( 'custom_message' );

		$bp_user_name = '';
		if ( is_user_logged_in() ) {
			$bp_user_name = $bp->loggedin_user->fullname;
		}

		$message_html = '';
		$message_html .= '<div class="bpgm-container">';
		$message_html .= '<div class="bpgm-left">';
		$message_html .= '<p class="bpgm-message"></p>';

		if ( 'on' == $enable_custom_message && ! empty( $custom_message ) ) {
			$message_html .= '<p class="bpgm-custom-message">' . $custom_message . '</p>';
		}

		$message_html .= '</div><!-- //bpgm-left -->';
		$message_html .= '<div class="bpgm-right">';
		$message_html .= '</div><!-- //bpgm-right -->';
		$message_html .= '</div><!-- //bpgm-container -->';

		echo $message_html;
	}


	public function frontend_js_scripts() {
		global $bp;

		$user_name = '';
		if ( is_user_logged_in() ) {
			$user_name = $bp->loggedin_user->fullname;
		}

		$greeting  = array();
		$day_parts = (array) $this->get_option( 'day_parts' );

		if ( in_array( 'morning', $day_parts ) ) {
			$greeting['morning']['message'] = __( 'Good Morning', 'bp-greeting-message' );
			$greeting['morning']['icon']    = '<img src="' . $this->assets_url . 'img/morning.png' . '" class="bpgm-icon bpgm-morning" />';
		}

		if ( in_array( 'afternoon', $day_parts ) ) {
			$greeting['afternoon']['message'] = __( 'Good Afternoon', 'bp-greeting-message' );
			$greeting['afternoon']['icon']    = '<img src="' . $this->assets_url . 'img/afternoon.png' . '" class="bpgm-icon bpgm-afternoon" />';
		}

		if ( in_array( 'evening', $day_parts ) ) {
			$greeting['evening']['message'] = __( 'Good Evening', 'bp-greeting-message' );
			$greeting['evening']['icon']    = '<img src="' . $this->assets_url . 'img/evening.png' . '" class="bpgm-icon bpgm-evening" />';
		}

		if ( in_array( 'night', $day_parts ) ) {
			$greeting['night']['message'] = __( 'Good Night', 'bp-greeting-message' );
			$greeting['night']['icon']    = '<img src="' . $this->assets_url . 'img/night.png' . '" class="bpgm-icon bpgm-night" />';
		}

		wp_localize_script(
			$this->_token . '-frontend',
			$this->base . 'values',
			array(
				'user_name' => $user_name,
				'greeting'  => $greeting,
			)
		);
	}
}
