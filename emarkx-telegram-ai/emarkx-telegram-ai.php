<?php
/**
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              https://emarkdev.com
* @since             1.0.0
* @package           emarkx_telegram_ai
*
* @wordpress-plugin
* Plugin Name:       Emarkx AI Integration with Telegram
* Plugin URI:		 https://emarkdev.com/emarkx-telegram-ai-2/
* Description:       Solve problems at a faster rate using AI Technology and Telegram.
* Version:           1.0.0
* Author:            Ephrain Marchan
* Author URI:        https://emarkdev.com
* License:           Non-GPL
* Text Domain:       emarkx-telegram-ai
* Domain Path:       /languages
*
*
* ███████╗███╗   ███╗ █████╗ ██████╗ ██╗  ██╗██████╗ ███████╗██╗   ██╗
* ██╔════╝████╗ ████║██╔══██╗██╔══██╗██║ ██╔╝██╔══██╗██╔════╝██║   ██║
* █████╗  ██╔████╔██║███████║██████╔╝█████╔╝ ██║  ██║█████╗  ██║   ██║
* ██╔══╝  ██║╚██╔╝██║██╔══██║██╔══██╗██╔═██╗ ██║  ██║██╔══╝  ╚██╗ ██╔╝
* ███████╗██║ ╚═╝ ██║██║  ██║██║  ██║██║  ██╗██████╔╝███████╗ ╚████╔╝ 
* ╚══════╝╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝  ╚═══╝  
*                                                                    
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
define( 'emarkx_telegram_ai_VERSION', '1.0.0' );

require_once 'vendor/autoload.php';
use WP_REST_Request;


class TelegramAIPlugin {

    private $chat_ids_option_name = 'emarkx_telegram_ai_chat_ids';
	
	private $bot_api_key_option_name = 'emarkx_telegram_ai_bot_api_key';
	
	private $gpt_api_key_option_name = 'emarkx_telegram_ai_gpt_api_key';

    public function __construct() {	
		
		// Add the filter to modify the action links
		add_filter( 'plugin_action_links', array($this, 'my_plugin_settings_link') , 10, 2 );
		
        // Hook into the WordPress admin menu
        add_action('admin_menu', array($this, 'add_settings_page'));

        // Register the plugin settings
        add_action('admin_init', array($this, 'register_settings'));
	
		// Hook into the WordPress REST API
		add_action('rest_api_init', array($this, 'register_telegram_bot_webhook_endpoint'));
		
		// Register activation hook
		register_activation_hook(__FILE__, array($this, 'emarkx_telegram_ai_activate'));
		
		// Register the deactivation hook
		register_deactivation_hook( __FILE__, array($this, 'emarkx_telegram_ai_deactivate'));
		
		// Enqueue CSS Script
			add_action('admin_enqueue_scripts', array($this, 'emarkx_telegram_ai_enqueue_admin_styles') );
    }

    public function add_settings_page() {
        add_options_page(
            'Emarkx Telegram AI',
            'Emarkx Telegram AI',
            'manage_options',
            'emarkx_telegram_ai_settings',
            array($this, 'settings_page')
        );
    }
	
	public function emarkx_telegram_ai_enqueue_admin_styles() {
		
		$current_screen = get_current_screen();
		if ($current_screen && $current_screen->id === 'settings_page_emarkx_telegram_ai_settings') {

			
			wp_enqueue_style('settings-css-handle', plugins_url( 'css/settings-css-file.css', __FILE__ ) );
			wp_enqueue_script('settings-js-handle', plugins_url( 'js/settings-js-file.js', __FILE__ ) );

		}
		
    }

	// Function to add the "Settings" link
	public function my_plugin_settings_link( $links, $plugin_file ) {
		// Check if it's your plugin
		if ( plugin_basename( __FILE__ ) === $plugin_file ) {
			// Create the "Settings" link
			$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=emarkx_telegram_ai_settings' ) ) . '">Settings</a>';

			
			// Insert the "Settings" link before the "Deactivate" link
			array_splice( $links, count( $links ) - 1, 0, $settings_link );
		}

		return $links;
	}
	
    public function settings_page() {
        ?>
        <div class="wrap">
			
			
            <h1>Emarkx AI Integration with Telegram
			<div id="donate">
				<a href="https://www.paypal.me/emarkdev" target="_blank">
				  <img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal">
				</a>
			</div>
			</h1>
			
			<div id="cute-buttons">
				<button id="main">Main</button>
				<button id="documentation">Documentation</button>
			</div>
			
			<div id="content1">
			<form method="post" action="options.php">
                <?php
                settings_fields('emarkx_telegram_ai_settings');
                do_settings_sections('emarkx_telegram_ai_settings');
                submit_button();
                ?>
            </form>	
			</div>
			
			<div id="content2" style="display:none;">
			  <h2>Steps to get started:</h2>
			  <ul>
				  <div id="first">
				  <li>1. Generate your very own Telegram bot and save the API key.</li>
				  <li>2. Generate your very own AI API key.</li>
				  <li>2. Seamlessly integrate the telegram bot and AI API keys into the plugin on your WordPress site.</li>
				  <li>3. Chat with your bot on Telegram and let AI work its magic.</li>
				  </div>
				  
				  <li><a id="telegram_bot">How to generate your Telegram bot</a></li>
				  
				  <div id="contenttelegram" style="display:none;">
					  <li>1. Sign in to the Telegram Web, Desktop, or Mobile App and create an account if you don't have one.</li>
					  <li>2. Search for BotFather on Telegram and open a new chat with the account.</li>
					  <li>3. Use the "/newbot" command to create a new bot and provide a unique name for it. BotFather will return a Bot API token - remember to keep it secure!</li>
				  </div>
				  
				  <li><a id="gpt_key">How to generate your AI key</a></li>
				  <div id="contentgpt" style="display:none;">
					  <li>1. Go to the OpenAI homepage at openai.com and create a free account if you haven't already by clicking on the "Sign Up" button.</li>
					  <li>2. Once you're logged in, click on the "API" option from the top navigation menu.
					  <li>3. Click on the "GPT-3" option from the left menu.</li>
					  <li>4. Scroll down to find the "Chat" API option.</li>
					  <li>5. Click on the "Request Access" button next to the "Chat" API option.</li>
					  <li>6. Fill out the required information for the API application form including your intended use case for the API.</li>
					  <li>7. Wait for OpenAI to review your application.</li>
					  <li>8. If your application is approved, you will receive an email with instructions on how to create your AI API key in your OpenAI account.</li>
					  <li>9. Follow the instructions in the email to create your AI API key.</li>
					  <li>10. Once you've created your API key, you can start using the AI API in your projects.</li>
				  </div>
				  
				  <li><a id="response_speed">How to increase response speed</a></li>
				  <div id="contentresponse" style="display:none;">
					  <li>The speed in which AI responds to your message depends on the complexity of your message and AI's server.</li>
				  </div>
				  
				  <li><a href="https://www.emarkdev.com">Link to Author's Website</a></li>
			  </ul>
			</div>
			
        </div>
        <?php
    }

    public function register_settings() {
        add_settings_section('emarkx_telegram_ai_settings_section', 'Plugin Settings', array($this, 'settings_section_callback'), 'emarkx_telegram_ai_settings');
		
        add_settings_field('emarkx_telegram_ai_chat_ids', 'Telegram Chat IDs ( , )', array($this, 'chat_ids_field_callback'), 'emarkx_telegram_ai_settings', 'emarkx_telegram_ai_settings_section');
		
        add_settings_field('emarkx_telegram_ai_bot_api_key', 'Telegram Bot API Key', array($this, 'bot_api_key_field_callback'), 'emarkx_telegram_ai_settings', 'emarkx_telegram_ai_settings_section');
		
		add_settings_field('emarkx_telegram_ai_gpt_api_key', 'GPT API Key', array($this, 'gpt_api_key_field_callback'), 'emarkx_telegram_ai_settings', 'emarkx_telegram_ai_settings_section');

        register_setting('emarkx_telegram_ai_settings', 'emarkx_telegram_ai_chat_ids', array($this, 'sanitize_chat_ids'));
		
        register_setting('emarkx_telegram_ai_settings', 'emarkx_telegram_ai_bot_api_key', 'sanitize_text_field');
		
		register_setting('emarkx_telegram_ai_settings', 'emarkx_telegram_ai_gpt_api_key', 'sanitize_text_field');
		
    }

    public function settings_section_callback() {
        // Section callback function
    }

    public function chat_ids_field_callback() {
		
        $chat_ids = get_option($this->chat_ids_option_name);	
	
		$chat_ids = is_array($chat_ids) ? implode(',', $chat_ids) : ''; // Convert array to string with comma separation
        echo '<input type="text" name="emarkx_telegram_ai_chat_ids[]" value="' . esc_attr($chat_ids) . '" />';
    }

    public function bot_api_key_field_callback() {
        $bot_api_key = get_option($this->bot_api_key_option_name);
        echo '<input type="text" name="emarkx_telegram_ai_bot_api_key" value="' . esc_attr($bot_api_key) . '" />';
    }
	
	public function gpt_api_key_field_callback() {
        $gpt_api_key = get_option($this->gpt_api_key_option_name);
        echo '<input type="text" name="emarkx_telegram_ai_gpt_api_key" value="' . esc_attr($gpt_api_key) . '" />';
    }

    public function sanitize_chat_ids($chat_ids) {
        $sanitized_chat_ids = array_map('trim', $chat_ids);
        $sanitized_chat_ids = array_filter($sanitized_chat_ids);
		// $sanitized_chat_ids = implode(',', $sanitized_chat_ids);
        return $sanitized_chat_ids;
    }
	
	public function register_telegram_bot_webhook_endpoint() {
		register_rest_route('my-gpt-plugin/v1', '/telegram-webhook', [
			'methods'  => 'POST',
			'callback' => array($this, 'process_telegram_webhook'),
			'permission_callback' => '__return_true',
		]);
	}

	// Callback function to process incoming webhook requests from Telegram
	public function process_telegram_webhook(WP_REST_Request $request) {
		// Get the request data
		$data = $request->get_json_params();

		// instance of the class
		// $instance = new TelegramAIPlugin();

		// Extract the chat ID and message text
		$chat_id = $data['message']['chat']['id'];
		$message = $this->sanitize_telegram_message($data['message']['text']);

		$chat_ids = get_option($this->chat_ids_option_name); 
		// Define the allowed chat ID(s)
		$chat_ids_string = implode(',', $chat_ids);
		$allowed_chat_ids = explode(',', $chat_ids_string);

		// Check if the received chat ID is allowed
		if (in_array($chat_id, $allowed_chat_ids)) {
			// Perform actions based on the received message
			// For example, call the gpt API and send a response back to the chat ID
			$response = $this->call_gpt_api($message);
			$this->send_telegram_message($chat_id, $response);

			// Return a response indicating successful message processing
			return ['status' => 'success'];
		} else {
			// Return an error response if the chat ID is not allowed
			return ['status' => 'error', 'message' => 'Unauthorized chat ID'];
		}

	}


	public function send_telegram_message($chat_id, $message) {
		$bot_token = get_option($this->bot_api_key_option_name);
		$url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
		$data = [
			'chat_id' => $chat_id,
			'text' => $this->sanitize_telegram_message($message),
		];

		$response = wp_remote_post($url, [
			'body' => json_encode($data),
			'headers' => [
				'Content-Type' => 'application/json',
			],
		]);

		if (is_wp_error($response)) {
			error_log('Telegram API request failed: ' . $response->get_error_message());
		}
	}

	public function sanitize_telegram_message($message) {
		// sanitizing the code here
		// AI has been in advance sanitization checks, this code prevents the plugin from crashing
		// prevents from security issues
		$message = htmlspecialchars_decode($message);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		return $message;
	}

	private function validate_telegram_message($message) {
		// Validate the message to ensure it meets your requirements
		
		if (empty($message)) {
			// The message should not be empty
			// Tell AI to send an error message response
			$message = 'Reply saying that the message is empty';
		}

		if (strlen($message) > 2000) {
			// The message should not exceed 2000 characters
			// Tell AI that they wrote too much characters
			$message = 'Reply saying that max characters is 2000';
		}

		return $message;
	}
	
	private function escape_telegram_message($message) {
		
		// Escape the sanitized message using WordPress's built-in functions
		// making sure html tags and similar are properly dispatched
		$escaped_message = esc_html($message);

		return $escaped_message;
	}
	

	public function test_send_telegram_message() {
		$chat_ids = get_option($this->chat_ids_option_name); 
		$message = 'Plugin Activated'; // test message

		$chat_ids_string = implode(',', $chat_ids);
    	$chat_ids_array = explode(',', $chat_ids_string);
		
		foreach ($chat_ids_array as $chat_id) {
			$this->send_telegram_message($chat_id, $message); // Trim any leading/trailing whitespace
		}

	}

	public function call_gpt_api($message) {
		$api_url = esc_url_raw('https://api.openai.com/v1/chat/completions'); 

		$headers = [
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer '.get_option($this->gpt_api_key_option_name), 
		];

		$message = $this->sanitize_telegram_message($message);
		$message = $this->validate_telegram_message($message);
		$message = $this->escape_telegram_message($message);

		$data = [
			'model' => 'gpt-3.5-turbo',
			'messages' => [
				[
					'role' => 'user',
					'content' => $message,
				],
			],
		];

		$args = [
			'body' => wp_json_encode($data),
			'headers' => $headers,
			'timeout' => 60,
		];

		$response = wp_remote_post($api_url, $args);

		if (is_wp_error($response)) {
			error_log('gpt API request failed: ' . $response->get_error_message());
			return '';
		}

		$response_data = wp_remote_retrieve_body($response);
		$response_data = json_decode($response_data, true);

		if (isset($response_data['choices'][0]['message']['content'])) {
			return $response_data['choices'][0]['message']['content'];
		}

		return '';
	}




	// Set up the Telegram bot webhook URL
	public function set_telegram_bot_webhook() {
		$bot_token = get_option($this->bot_api_key_option_name);
		$webhook_url = home_url('/wp-json/my-gpt-plugin/v1/telegram-webhook');

		// Generate a nonce for the WP REST API
		$nonce = wp_create_nonce('wp_rest');

		$api_url = "https://api.telegram.org/bot{$bot_token}/setWebhook";
		$response = wp_remote_post($api_url, [
			'body' => ['url' => $webhook_url],
			// '_wpnonce' => $nonce, // Include the nonce in the request body
		]);

		if (is_wp_error($response)) {
			error_log('Telegram webhook setup failed: ' . $response->get_error_message());
		}
	}


	// Function to run on plugin activation
	public function emarkx_telegram_ai_activate() {
		$this->set_telegram_bot_webhook();
		$this->test_send_telegram_message();
	}
	
	// Function to run on plugin deactivation
	public function emarkx_telegram_ai_deactivate() {
		$bot_token = get_option($this->bot_api_key_option_name);
		$response = wp_remote_post( 'https://api.telegram.org/bot{$bot_token}/setWebhook?url=' );
	}

}	


// Create an instance of the TelegramAIPlugin class
$emarkx_telegram_ai = new TelegramAIPlugin();
