<?php
if (! defined('ABSPATH')) exit;

/**
 * Returns a map of block types to their respective paths and ACF usage.
 * 
 * - has_acf: true/false
 * - acf_dest: Where to put the ACF file in the theme
 * - template_dest: Where to put the front-end template file
 * - rename_callback: function name to rename the final file if needed
 */
function matrix_ci_get_block_type_map()
{
  return [

    // 404 Page
    '404' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/404/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    'banner' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    'back-to-top' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Blog
    'blog' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/blog/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Breadcrumbs
    'breadcrumbs' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //Contact
    'contact' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //content
    'content' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    'copyright' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //CTA
    'cta' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //custom-post-types
    'custom-post-types' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'inc/cpts/post-types',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //faq
    'faq' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //features
    'features' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Footer (multiple sub-types like newsletter, back-to-top, etc.)
    'footer' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // gallery
    'gallery' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Hero
    'hero' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/hero/',
      'template_dest'   => 'template-parts/hero/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //intro
    'intro' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Navigation Desktop
    'navigation-desktop' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Navigation Mobile
    'navigation-mobile' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/navbar',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //Newsletter Signup Form
    'newsletter' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //pagination
    'pagination' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'inc/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    // Single Hero (example)
    'single-hero' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/hero/',
      'template_dest'   => 'template-parts/single/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //sitemap
    'sitemap' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'templates/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //taxonomy
    'taxonomy' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'inc/cpts/taxonomies/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //team
    'team' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //testimonials
    'testimonials' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //topbar
    'topbar' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

    //team
    'team' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_generic_rename',
    ],

  ];
}

/**
 * Generic rename callback: use the folder name as the file name.
 */
function matrix_ci_generic_rename($download_url, $folder_name)
{
  // Remove any query parameters from the URL.
  $clean_url = strtok($download_url, '?');
  // Return the base file name as on GitHub.
  return basename($clean_url);
}
