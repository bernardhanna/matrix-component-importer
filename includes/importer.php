<?php
if (! defined('ABSPATH')) exit;

function matrix_ci_import_component($block_type, $folder_name)
{
    $type_map = matrix_ci_get_block_type_map();

    if (! isset($type_map[ $block_type ] )) {
        return [
            'success' => false,
            'message' => "Unknown block type: $block_type",
        ];
    }

    $stored_components = matrix_ci_get_stored_components();

    if (! isset($stored_components[ $block_type ])) {
        return [
            'success' => false,
            'message' => "No stored components found for type: $block_type",
        ];
    }

    // Find matching component
    $component = null;

    foreach ( $stored_components[ $block_type ] as $comp ) {
        if ( $comp['folder'] === $folder_name ) {
            $component = $comp;
            break;
        }
    }

    if ( ! $component ) {
        return [
            'success' => false,
            'message' => "Component not found for type: $block_type / folder: $folder_name",
        ];
    }

    $acf_file      = $component['acf_file'] ?? '';
    $template_file = $component['template_file'] ?? '';

    $type_info = $type_map[ $block_type ];
    $rename_callback = $type_info['rename_callback'];

    // Import ACF file
    if ( ! empty( $acf_file ) && $type_info['has_acf'] ) {
        $acf_dest_dir = get_stylesheet_directory() . '/' . $type_info['acf_dest'];
        $acf_filename = call_user_func( $rename_callback, $acf_file, $folder_name );

        if ( ! file_exists( $acf_dest_dir ) ) {
            wp_mkdir_p( $acf_dest_dir );
        }

        $acf_result = matrix_ci_download_file( $acf_file, $acf_dest_dir . $acf_filename );

        if ( ! $acf_result ) {
            return [
                'success' => false,
                'message' => "Failed to download ACF file.",
            ];
        }
    }

    // Import Template file
    if ( ! empty( $template_file ) ) {
        $template_dest_dir = get_stylesheet_directory() . '/' . $type_info['template_dest'];
        $template_filename = call_user_func( $rename_callback, $template_file, $folder_name );

        if ( ! file_exists( $template_dest_dir ) ) {
            wp_mkdir_p( $template_dest_dir );
        }

        $template_result = matrix_ci_download_file( $template_file, $template_dest_dir . $template_filename );

        if ( ! $template_result ) {
            return [
                'success' => false,
                'message' => "Failed to download Template file.",
            ];
        }
    }

    return [
        'success' => true,
        'message' => "Component '$block_type / $folder_name' imported successfully.",
    ];
}

function matrix_ci_download_file( $url, $dest_path )
{
    $response = wp_remote_get( $url, [
        'timeout' => 30,
        'headers' => [
            'User-Agent' => 'Matrix-CI',
        ],
    ]);

    if ( is_wp_error( $response ) ) {
        error_log( "Matrix CI: Failed to download $url: " . $response->get_error_message() );
        return false;
    }

    $body = wp_remote_retrieve_body( $response );

    if ( empty( $body ) ) {
        error_log( "Matrix CI: Empty body for $url" );
        return false;
    }

    return file_put_contents( $dest_path, $body ) !== false;
}
