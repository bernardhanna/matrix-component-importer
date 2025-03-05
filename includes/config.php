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
      'rename_callback' => 'matrix_ci_rename_404',
    ],

    'banner' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_rename_banner',
    ],

    'back-to-top' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_rename_footer',
    ],

    // Blog
    'blog' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/blog/',
      'rename_callback' => 'matrix_ci_rename_blog',
    ],

    // Breadcrumbs
    'breadcrumbs' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_rename_breadcrumbs',
    ],

    //Contact
    'contact' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_contact',
    ],

    //content
    'content' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_content',
    ],

    'copyright' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_rename_footer',
    ],

    //CTA
    'cta' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_cta',
    ],

    //custom-post-types
    'custom-post-types' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'inc/cpts/post-types',
      'rename_callback' => 'matrix_ci_rename_custom_post_types',
    ],

    //faq
    'faq' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_faq',
    ],

    //features
    'features' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_features',
    ],

    // Footer (multiple sub-types like newsletter, back-to-top, etc.)
    'footer' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_rename_footer',
    ],

    // gallery
    'gallery' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_gallery',
    ],

    // Hero
    'hero' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/hero/',
      'template_dest'   => 'template-parts/hero/',
      'rename_callback' => 'matrix_ci_rename_hero',
    ],

    //intro
    'intro' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_intro',
    ],

    // Navigation Desktop
    'navigation-desktop' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_rename_navigation',
    ],

    // Navigation Mobile
    'navigation-mobile' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/navbar',
      'rename_callback' => 'matrix_ci_rename_navigation',
    ],

    //Newsletter Signup Form
    'newsletter' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/footer/',
      'rename_callback' => 'matrix_ci_rename_footer',
    ],

    //pagination
    'pagination' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'inc/',
      'rename_callback' => 'matrix_ci_rename_pagination',
    ],

    // Single Hero (example)
    'single-hero' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/hero/',
      'template_dest'   => 'template-parts/single/',
      'rename_callback' => 'matrix_ci_rename_hero',
    ],

    //sitemap
    'sitemap' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'templates/',
      'rename_callback' => 'matrix_ci_rename_sitemap',
    ],

    //taxonomy
    'taxonomy' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'inc/cpts/taxonomies/',
      'rename_callback' => 'matrix_ci_rename_taxonomy',
    ],

    //team
    'team' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_team',
    ],

    //testimonials
    'testimonials' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_testimonials',
    ],

    //topbar
    'topbar' => [
      'has_acf'         => false,
      'acf_dest'        => '',
      'template_dest'   => 'template-parts/header/',
      'rename_callback' => 'matrix_ci_rename_topbar',
    ],

    //team
    'team' => [
      'has_acf'         => true,
      'acf_dest'        => 'acf-fields/partials/blocks/',
      'template_dest'   => 'template-parts/flexi/',
      'rename_callback' => 'matrix_ci_rename_team',
    ],

  ];
}

/**
 * Example rename callback for 404: always call it "404.php"
 */
function matrix_ci_rename_404($download_url, $folder_name)
{
  return '404.php';
}

/**
 * Example rename callback for blog: always call it "index.php"
 */
function matrix_ci_rename_blog($download_url, $folder_name)
{
  return 'index.php';
}

/**
 * Example rename callback for footer blocks.
 * If the sub-subfolder is "newsletter", you might want "footer-newsletter.php".
 * If it's "back-to-top", "footer-back-to-top.php".
 * 
 * This is just one approach. It depends on how your GitHub directory is named.
 * 
 * For instance, if your GitHub path is: footer/newsletter/001/footer-newsletter.php,
 * you might parse `$download_url` or `$folder_name` to figure out "newsletter".
 */
function matrix_ci_rename_footer($download_url, $folder_name)
{
  // In your question, the actual file is named "footer-newsletter.php" always,
  // so maybe we just return that same base name:
  return basename($download_url);
}

/**
 * Example rename for navigation: always "navigation.php"
 */
function matrix_ci_rename_navigation($download_url, $folder_name)
{
  return 'navigation.php';
}

/**
 * Example rename for hero or single-hero:
 * Keep the original filename, e.g. hero_001.php, or rename if you prefer.
 */
function matrix_ci_rename_hero($download_url, $folder_name)
{
  return basename($download_url);
}
