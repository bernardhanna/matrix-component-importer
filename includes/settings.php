<?php
if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Register the Token submenu page under the main Matrix Components menu.
 * Using a later priority ensures the parent menu is registered first.
 */
add_action('admin_menu', 'matrix_ci_register_token_submenu', 100);
function matrix_ci_register_token_submenu()
{
  add_submenu_page(
    'matrix-ci-admin-page',        // Parent slug from main menu
    'GitHub Token',                // Page title
    'Token',                       // Menu title
    'manage_options',              // Capability
    'matrix-ci-token',             // Menu slug for the token page
    'matrix_ci_render_token_page'  // Callback to render the token page
  );
}

/**
 * Render the Token submenu page.
 */
function matrix_ci_render_token_page()
{
?>
  <div class="wrap">
    <h1>GitHub Token Settings</h1>
    <form method="post" action="options.php">
      <?php
      settings_fields('matrix_ci_options_group');
      do_settings_sections('matrix_ci_token');
      submit_button();
      ?>
    </form>
  </div>
<?php
}

/**
 * Register the GitHub token setting.
 */
add_action('admin_init', 'matrix_ci_register_token_setting');
function matrix_ci_register_token_setting()
{
  register_setting(
    'matrix_ci_options_group',         // Option group
    'matrix_ci_github_token',          // Option name
    'matrix_ci_sanitize_github_token'  // Sanitize callback
  );

  add_settings_section(
    'matrix_ci_token_section',        // ID
    'GitHub Token Settings',          // Title
    'matrix_ci_token_section_callback', // Callback description
    'matrix_ci_token'                 // Page (matches our submenu slug)
  );

  add_settings_field(
    'matrix_ci_github_token',         // ID
    'GitHub Token',                   // Title
    'matrix_ci_github_token_field_callback', // Callback to render field
    'matrix_ci_token',                // Page
    'matrix_ci_token_section'         // Section
  );
}

/**
 * Section callback.
 */
function matrix_ci_token_section_callback()
{
  echo '<p>Enter your GitHub token below. Leave blank to keep the current token.</p>';
}

/**
 * Sanitize callback: if input is empty, keep the existing token.
 */
function matrix_ci_sanitize_github_token($input)
{
  if (empty($input)) {
    return get_option('matrix_ci_github_token');
  }
  return sanitize_text_field($input);
}

/**
 * Render the GitHub token field as a password field.
 */
function matrix_ci_github_token_field_callback()
{
  $token = get_option('matrix_ci_github_token', '');
  $placeholder = $token ? str_repeat('*', strlen($token)) : '';
  // Leave the value empty to prevent the token from being exposed.
  echo '<input type="password" name="matrix_ci_github_token" value="" placeholder="' . esc_attr($placeholder) . '" class="regular-text" />';
}
