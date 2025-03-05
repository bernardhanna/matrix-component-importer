<?php

/**
 * Plugin Name: Matrix Component Importer
 * Description: Imports block components from Matrix GitHub into your theme.
 * Author: Bernard Hanna
 * Version: 1.0
 */

if (! defined('ABSPATH')) {
  exit; // Prevent direct file access
}

// Define useful constants
define('MATRIX_CI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MATRIX_CI_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once MATRIX_CI_PLUGIN_DIR . 'includes/config.php';
require_once MATRIX_CI_PLUGIN_DIR . 'includes/api-handler.php';
require_once MATRIX_CI_PLUGIN_DIR . 'includes/importer.php';
require_once MATRIX_CI_PLUGIN_DIR . 'includes/settings.php';

// Plugin activation/deactivation hooks
register_activation_hook(__FILE__, 'matrix_ci_activate_plugin');
register_deactivation_hook(__FILE__, 'matrix_ci_deactivate_plugin');

function matrix_ci_activate_plugin()
{
  // No cron scheduling needed.
}

function matrix_ci_deactivate_plugin()
{
  // No scheduled events to remove.
}

// Admin menu
add_action('admin_menu', 'matrix_ci_register_admin_menu');
function matrix_ci_register_admin_menu()
{
  add_menu_page(
    'Matrix Components',              // Page title
    'Matrix Components',              // Menu title
    'manage_options',                 // Capability
    'matrix-ci-admin-page',           // Menu slug
    'matrix_ci_render_admin_page',    // Callback
    'dashicons-layout',               // Icon
    60                                // Position
  );
}

// Enqueue admin scripts/styles
add_action('admin_enqueue_scripts', 'matrix_ci_enqueue_admin_assets');
function matrix_ci_enqueue_admin_assets($hook)
{
  if ($hook !== 'toplevel_page_matrix-ci-admin-page') {
    return;
  }
  wp_enqueue_style('matrix-ci-admin-css', MATRIX_CI_PLUGIN_URL . 'assets/admin.css', [], '1.0');
  wp_enqueue_script('matrix-ci-admin-js', MATRIX_CI_PLUGIN_URL . 'assets/admin.js', ['jquery'], '1.0', true);

  // Pass AJAX data
  wp_localize_script('matrix-ci-admin-js', 'matrixCI', [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce'   => wp_create_nonce('matrix_ci_nonce')
  ]);
}

// Render admin page
function matrix_ci_render_admin_page()
{
  include_once MATRIX_CI_PLUGIN_DIR . 'templates/admin-page.php';
}

// AJAX: Rescan
add_action('wp_ajax_matrix_ci_rescan', 'matrix_ci_rescan_callback');
function matrix_ci_rescan_callback()
{
  check_ajax_referer('matrix_ci_nonce', 'nonce');

  matrix_ci_fetch_all_blocks_from_github(true);
  wp_send_json_success(['message' => 'Rescan complete!']);
}

// AJAX: Import a component
add_action('wp_ajax_matrix_ci_import_component', 'matrix_ci_import_component_callback');
function matrix_ci_import_component_callback()
{
  check_ajax_referer('matrix_ci_nonce', 'nonce');

  $block_type  = isset($_POST['blockType'])  ? sanitize_text_field($_POST['blockType'])  : '';
  $folder_name = isset($_POST['folderName']) ? sanitize_text_field($_POST['folderName']) : '';

  if (empty($block_type) || empty($folder_name)) {
    wp_send_json_error(['message' => 'Invalid request (missing type or folder).']);
  }

  $result = matrix_ci_import_component($block_type, $folder_name);
  if ($result['success']) {
    wp_send_json_success(['message' => $result['message']]);
  } else {
    wp_send_json_error(['message' => $result['message']]);
  }
}
