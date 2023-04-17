<?php

declare( strict_types=1 );

namespace AgentFire\Plugin\Task;

use AgentFire\Plugin\Task\Traits\Singleton;

/**
 * Class Admin
 * @package AgentFire\Plugin\Task
 */
class AdminPage {
	use Singleton;
	public function __construct() {
		add_action('admin_menu', [ $this, 'add_menu']);
		add_action( 'admin_init', [ $this, 'register_settings']);

	}

	public function add_menu() {
		add_options_page(
			'AgentFire Plugin Settings',
			'AgentFire Config',
			'manage_options',
			'agentfire-settings',
			[$this, 'render']
		);
	}

	public function register_settings(){
		register_setting(
			'agentfire-settings-group',
			'agentfire_token'
		);
	}

	public function render() {
		?>
		<div class="wrap">
			<h1>AgentFire Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'agentfire-settings-group' ); ?>
				<?php do_settings_sections( 'agentfire-settings' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">MAPBOX TOKEN</th>
						<td><input type="text" name="agentfire_token" value="<?php echo esc_attr( get_option('agentfire_token') ); ?>" /></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
