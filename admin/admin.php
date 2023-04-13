<?php

// Create admin settings page
function agentfire_test_settings_page() {
    add_options_page(
        'AgentFire Plugin Settings',
        'AgentFire Config',
        'manage_options',
        'agentfire-settings',
        'agentfire_settings_page_callback'
    );
}

// Callback settings
function agentfire_settings_page_callback() {
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

// Register settings
function agentfire_register_settings() {
    register_setting( 'agentfire-settings-group', 'agentfire_token' );
}

// Add actions.
add_action( 'admin_menu', 'agentfire_test_settings_page' );
add_action( 'admin_init', 'agentfire_register_settings' );
