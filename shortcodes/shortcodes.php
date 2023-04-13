<?php
use Timber\Timber;
function render_html_plugin() {
    $context = Timber::get_context();
    $context['logo'] = plugins_url( '/agentfire-test/assets/images/AgentFire-Logo-2020-white.png' );
    $context['variable'] = 'This is a variable';
    return Timber::compile( WP_PLUGIN_DIR . '/agentfire-test/template/render-html.twig' , $context);
}
add_shortcode('agentfire_test', 'render_html_plugin');