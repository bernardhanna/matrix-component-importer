<?php
/**
 * Plugin Name: Matrix Component Importer
 * Description: Imports block components from Matrix server into your theme.
 * Author: Bernard Hanna
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MATRIX_CI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MATRIX_CI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once MATRIX_CI_PLUGIN_DIR . 'includes/config.php';
require_once MATRIX_CI_PLUGIN_DIR . 'includes/importer.php';

// Admin menu
add_action( 'admin_menu', 'matrix_ci_register_admin_menu' );

function matrix_ci_register_admin_menu() {
    add_menu_page(
        'Matrix Components',
        'Matrix Components',
        'manage_options',
        'matrix-ci-admin-page',
        'matrix_ci_render_admin_page',
        'dashicons-layout',
        60
    );
}

// Enqueue admin scripts/styles
add_action( 'admin_enqueue_scripts', 'matrix_ci_enqueue_admin_assets' );

function matrix_ci_enqueue_admin_assets( $hook ) {
    if ( $hook !== 'toplevel_page_matrix-ci-admin-page' ) {
        return;
    }

    wp_enqueue_style( 'matrix-ci-admin-css', MATRIX_CI_PLUGIN_URL . 'assets/admin.css', [], '1.0' );
    wp_enqueue_script( 'matrix-ci-admin-js', MATRIX_CI_PLUGIN_URL . 'assets/admin.js', [ 'jquery' ], '1.0', true );

    wp_localize_script( 'matrix-ci-admin-js', 'matrixCI', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'matrix_ci_nonce' ),
    ] );
}

// Render admin page
function matrix_ci_render_admin_page() {
    include_once MATRIX_CI_PLUGIN_DIR . 'templates/admin-page.php';
}

// AJAX: Rescan
add_action( 'wp_ajax_matrix_ci_rescan', 'matrix_ci_rescan_callback' );

function matrix_ci_rescan_callback() {
    check_ajax_referer( 'matrix_ci_nonce', 'nonce' );

    $components = matrix_ci_fetch_all_blocks_from_server( true );

    wp_send_json_success( [
        'message' => 'Rescan complete!',
        'components' => $components,
    ] );
}

// AJAX: Import component
add_action( 'wp_ajax_matrix_ci_import_component', 'matrix_ci_import_component_callback' );

function matrix_ci_import_component_callback() {
    check_ajax_referer( 'matrix_ci_nonce', 'nonce' );

    $block_type  = isset( $_POST['blockType'] ) ? sanitize_text_field( $_POST['blockType'] ) : '';
    $folder_name = isset( $_POST['folderName'] ) ? sanitize_text_field( $_POST['folderName'] ) : '';

    if ( empty( $block_type ) || empty( $folder_name ) ) {
        wp_send_json_error( [ 'message' => 'Invalid request (missing type or folder).' ] );
    }

    $result = matrix_ci_import_component( $block_type, $folder_name );

    if ( $result['success'] ) {
        wp_send_json_success( [ 'message' => $result['message'] ] );
    } else {
        wp_send_json_error( [ 'message' => $result['message'] ] );
    }
}


// AJAX: Preview Proxy
add_action( 'wp_ajax_matrix_ci_preview_proxy', 'matrix_ci_preview_proxy_callback' );

function matrix_ci_preview_proxy_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }

    $file_url = isset( $_GET['file'] ) ? esc_url_raw( $_GET['file'] ) : '';

    if ( empty( $file_url ) ) {
        status_header( 400 );
        echo 'Missing file param.';
        exit;
    }

    // Validate it's an image
    $allowed_types = [ 'image/jpeg', 'image/png', 'image/jpg', 'image/gif' ];
    $head = wp_remote_head( $file_url );

    if ( is_wp_error( $head ) ) {
        status_header( 400 );
        echo 'Failed to fetch image.';
        exit;
    }

    $content_type = wp_remote_retrieve_header( $head, 'content-type' );

    if ( ! in_array( $content_type, $allowed_types, true ) ) {
        status_header( 400 );
        echo 'Invalid image type.';
        exit;
    }

    // Stream the image
    $response = wp_remote_get( $file_url );

    if ( is_wp_error( $response ) ) {
        status_header( 400 );
        echo 'Failed to fetch image.';
        exit;
    }

    $body = wp_remote_retrieve_body( $response );

    header( 'Content-Type: ' . $content_type );
    echo $body;
    exit;
}
