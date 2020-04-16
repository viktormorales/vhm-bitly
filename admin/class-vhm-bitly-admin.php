<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://viktormorales.com
 * @since      1.0.0
 *
 * @package    Vhm_Bitly
 * @subpackage Vhm_Bitly/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vhm_Bitly
 * @subpackage Vhm_Bitly/admin
 * @author     Viktor H. Morales <viktorhugomorales@gmail.com>
 */
class Vhm_Bitly_Admin {

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
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name;

	/**
	 * The Bitly Token
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$token 	Bitly token
	 */
	private $token;

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
		$this->option_name = 'vhm_bitly';
		$this->token = get_option($this->option_name . '_token');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vhm_Bitly_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vhm_Bitly_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vhm-bitly-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vhm_Bitly_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vhm_Bitly_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vhm-bitly-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'VHM Bitly Settings', $this->plugin_name ),
			__( 'VHM Bitly', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}
	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/'.$this->plugin_name.'-admin-display.php';
	}
	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', $this->plugin_name ),
			array( $this, $this->option_name .'_general_txt' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->option_name . '_token',
			__( 'Token', $this->plugin_name ),
			array( $this, $this->option_name .'_token_txt' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_token' )
		);
		
		register_setting( $this->plugin_name, $this->option_name . '_token' );
		
	}
	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function vhm_bitly_general_txt() {
		echo '<p>' . __( 'Create short links using the Bitly service.', $this->plugin_name ) . '</p>';
	}

	/**
	 * Render the input field for "element" option
	 *
	 * @since  1.0.0
	 */
	public function vhm_bitly_token_txt() {
		$token = get_option( $this->option_name . '_token' );

		echo '<input type="text" name="' . $this->option_name . '_token" id="' . $this->option_name . '_token" value="' . $token . '">';
		echo '<p class="description">' . __('Paste your Bitly Generic Access Token.', $this->plugin_name) . '</p>';
		echo '<p class="description">¿How to get a Generic Access token?</p>';
	}

	public function add_meta_box()
    {
        $screens = get_post_types(array('public' => true));
        foreach ($screens as $screen) {
            add_meta_box(
                'vhm_bitly_meta_box',          // Unique ID
                __('VHM Bitly',  $this->plugin_name), // Box title
                [$this, 'html_meta_box'],   // Content callback, must be of type callable
                $screen,
                'advanced'
            );
        }
    }
	
	public function html_meta_box($post)
    {
		$vhm_bitly_link = get_post_meta($post->ID, '_vhm_bitly', true);

		if ($this->token)
		{
			?>
			<table class="form-table">
				<tr>
					<th><label for="vhm_bitly_link"><?php _e('Post short link', $this->plugin_name) ?></label></th>
					<td>
						<?php if (!$vhm_bitly_link) { ?>
							<label><input type="checkbox" name="vhm_create_shortlink" value="1" checked="checked">
							<span class="description"><?php _e('Uncheck this box to avoid creating a short link for this post.', $this->plugin_name); ?></span></label>
						<?php } else { ?>
							<input type="text" id="vhm_bitly_link" name="vhm_bitly_link" value="<?php echo $vhm_bitly_link ?>" onclick="this.select()" readonly>
						<?php } ?>
					</td>
				</tr>
			</table>
			<?php
		}
		else {
			echo '<p>' . __('In order to create short links you need to provide a Bitly Generic Access Token.', $this->plugin_name) . ' ' . __('Go to', $this->plugin_name) . ' <a href="' . admin_url('options-general.php?page=vhm-bitly') . '">' . __('Settings page', $this->plugin_name) . '</a></p>';
		}
	}
	
    public function save_meta_box($post_id)
    {
		$post_title = get_the_title($post_id);
		$post_url = get_permalink($post_id);
		$create_shortlink = ($_POST['vhm_create_shortlink'] == 1) ? 'on' : 'off' ;
		
		if ($create_shortlink == 'on' && !get_post_meta($post_id, '_vhm_bitly', true))
		{
			$apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
			$genericAccessToken = $this->token;

			$data = array(
				'title' => $post_title,
				'long_url' => $post_url
			);
			$payload = json_encode($data);

			$header = array(
				'Authorization: Bearer ' . $genericAccessToken,
				'Content-Type: application/json',
				'Content-Length: ' . strlen($payload)
			);

			$ch = curl_init($apiv4);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$result = curl_exec($ch);
			$resultToJson = json_decode($result);

			if (isset($resultToJson->link)) {
				update_post_meta($post_id, '_vhm_bitly', $resultToJson->link);
			}
		}
    }

}
