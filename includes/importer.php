<?php
if (! defined('ABSPATH')) exit;

/**
 * Imports a specific block type + folder combination.
 *
 * @param string $block_type   e.g. 'footer', 'hero', 'navigation'
 * @param string $folder_name  e.g. '001', '002'
 * @return array  [ 'success' => bool, 'message' => string ]
 */
function matrix_ci_import_component($block_type, $folder_name)
{
  $all = matrix_ci_get_stored_components();
  if (empty($all[$block_type])) {
    return [
      'success' => false,
      'message' => "No components found for type: $block_type"
    ];
  }

  // Find the matching subfolder
  $component = null;
  foreach ($all[$block_type] as $entry) {
    if ($entry['folder'] === $folder_name) {
      $component = $entry;
      break;
    }
  }
  if (! $component) {
    return [
      'success' => false,
      'message' => "Component '$folder_name' not found in type '$block_type'."
    ];
  }

  // Get info from config
  $type_map = matrix_ci_get_block_type_map();
  if (empty($type_map[$block_type])) {
    return [
      'success' => false,
      'message' => "Block type '$block_type' not configured."
    ];
  }
  $map_info = $type_map[$block_type];

  $theme_path = get_stylesheet_directory(); // e.g. /wp-content/themes/matrix-starter

  // If block has ACF, import it
  if ($map_info['has_acf'] && ! empty($component['acf_file'])) {
    $acf_dest_dir = $theme_path . '/' . $map_info['acf_dest'];
    wp_mkdir_p($acf_dest_dir);

    $acf_contents = matrix_ci_download_file_contents($component['acf_file']);
    if ($acf_contents !== false) {
      $acf_filename = basename($component['acf_file']);
      file_put_contents($acf_dest_dir . $acf_filename, $acf_contents);
    }
  }

  // Import front-end template
  $template_dest_dir = $theme_path . '/' . $map_info['template_dest'];
  wp_mkdir_p($template_dest_dir);

  $template_contents = matrix_ci_download_file_contents($component['template_file']);
  if ($template_contents === false) {
    return [
      'success' => false,
      'message' => 'Failed to download template file.'
    ];
  }

  // Determine final filename
  $rename_cb = $map_info['rename_callback'];
  if (! is_callable($rename_cb)) {
    // If no rename callback, just keep original
    $final_name = basename($component['template_file']);
  } else {
    $final_name = call_user_func($rename_cb, $component['template_file'], $folder_name);
  }

  file_put_contents($template_dest_dir . $final_name, $template_contents);

  return [
    'success' => true,
    'message' => "Imported $block_type / $folder_name successfully."
  ];
}

/**
 * Helper to download raw file contents from GitHub.
 */
function matrix_ci_download_file_contents($file_url)
{
  $resp = wp_remote_get($file_url, [
    'headers' => ['User-Agent' => 'Matrix-CI'],
    'timeout' => 20,
  ]);
  if (is_wp_error($resp)) {
    return false;
  }
  $code = wp_remote_retrieve_response_code($resp);
  if ($code !== 200) {
    return false;
  }
  return wp_remote_retrieve_body($resp);
}
