<?php

/**
 * Plugin Name: Edel Museum Generator Pro
 * Description: Premium version. Unlock Link integration and 3D Model (GLB) support.
 * Version: 1.4.0
 * Author: Edel Hearts
 * Author URI: https://edel-hearts.com
 * Text Domain: edel-museum-generator
 * Domain Path: /languages
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) exit;

$info = get_file_data(__FILE__, array('plugin_name' => 'Plugin Name', 'version' => 'Version'));

// ★修正: 定数名を統一 (EDEL_MUSEUM_GENERATOR_PRO_...)
define('EDEL_MUSEUM_GENERATOR_PRO_URL', plugins_url('', __FILE__));
define('EDEL_MUSEUM_GENERATOR_PRO_PATH', dirname(__FILE__));
define('EDEL_MUSEUM_GENERATOR_PRO_SLUG', 'edel-museum-generator-pro');
define('EDEL_MUSEUM_GENERATOR_PRO_VERSION', $info['version']);
define('EDEL_MUSEUM_GENERATOR_PRO_DEVELOP', true);

register_activation_hook(__FILE__, 'edel_museum_pro_activation_check');
function edel_museum_pro_activation_check() {
    // Lite版が有効なら停止を促す
    if (is_plugin_active('edel-museum-generator/edel-museum-generator.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            'Error: The Lite version "Edel Museum Generator" is active. Please deactivate it first.',
            'Plugin Activation Error',
            array('back_link' => true)
        );
    }
}

class EdelMuseumGeneratorPro {
    public function init() {
        if (class_exists('EdelMuseumGenerator')) return;

        add_action('plugins_loaded', array($this, 'load_textdomain'));

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_plugin_links'));

        // 新しい定数を使用して読み込み
        require_once EDEL_MUSEUM_GENERATOR_PRO_PATH . '/inc/class-admin.php';
        $admin = new EdelMuseumGeneratorAdminPro();
        $admin->init();

        require_once EDEL_MUSEUM_GENERATOR_PRO_PATH . '/inc/class-front.php';
        $front = new EdelMuseumGeneratorFrontPro();
        $front->init();
    }

    public function load_textdomain() {
        load_plugin_textdomain('edel-museum-generator', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function add_plugin_links($links) {
        $url = admin_url('edit.php?post_type=edel_exhibition&page=edel-museum-help');
        $settings_link = '<a href="' . esc_url($url) . '" style="font-weight:bold;">' . __('Usage Guide', 'edel-museum-generator') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

$instance = new EdelMuseumGeneratorPro();
$instance->init();
