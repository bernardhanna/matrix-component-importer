<?php
if (! defined('ABSPATH')) exit;

function matrix_ci_get_block_type_map() {
    return [
        '404' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/404/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'banner' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/header/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'back-to-top' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/footer/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'blog' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/blog/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'breadcrumbs' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/header/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'contact' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'content' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'copyright' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/footer/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'cta' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'faq' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'features' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'footer' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/footer/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'gallery' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'hero' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/hero/',
            'template_dest' => 'template-parts/hero/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'intro' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'navigation-desktop' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/header/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'navigation-mobile' => [
        'has_acf'         => false,
        'acf_dest'        => '',
        'template_dest'   => 'template-parts/header/navbar/',
        'rename_callback' => function($url, $folder) {
            return 'mobile.php'; // Force correct filename
        },
        ],
        'newsletter' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/footer/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'pagination' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'inc/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'single-hero' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/hero/',
            'template_dest' => 'template-parts/single/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'sitemap' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'templates/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'taxonomy' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'inc/cpts/taxonomies/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'team' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'testimonials' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'topbar' => [
            'has_acf' => false,
            'acf_dest' => '',
            'template_dest' => 'template-parts/header/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
        'utility' => [
            'has_acf' => true,
            'acf_dest' => 'acf-fields/partials/blocks/',
            'template_dest' => 'template-parts/flexi/',
            'rename_callback' => 'matrix_ci_generic_rename',
        ],
    ];
}

function matrix_ci_fetch_all_blocks_from_server($force_refresh = false) {
    $cached = get_option('matrix_ci_components_grouped', []);
    if (!$force_refresh && !empty($cached)) {
        return $cached;
    }

    $base_url = 'https://blocks.wpflexitheme.com/index.json';

    $response = wp_remote_get($base_url, [
        'headers' => ['User-Agent' => 'Matrix-CI'],
        'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
        error_log("Matrix CI: Failed to fetch $base_url: " . $response->get_error_message());
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $master_index = json_decode($body, true);

    if (! is_array($master_index)) {
        error_log("Matrix CI: Malformed JSON from $base_url");
        return [];
    }

    // RE-GROUP by block type:
    $grouped = [];

    foreach ( $master_index as $item ) {
        $type = $item['type'] ?? 'unknown';
        if ( ! isset( $grouped[ $type ] ) ) {
            $grouped[ $type ] = [];
        }
        $grouped[ $type ][] = $item;
    }

    update_option('matrix_ci_components_grouped', $grouped);

    return $grouped;
}

function matrix_ci_get_stored_components() {
    return get_option('matrix_ci_components_grouped', []);
}

function matrix_ci_generic_rename($download_url, $folder_name) {
    $clean_url = strtok($download_url, '?');
    return basename($clean_url);
}