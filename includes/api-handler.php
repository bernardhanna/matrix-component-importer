<?php
if (! defined('ABSPATH')) exit;

/**
 * Fetch all block types & subfolders from GitHub and store in an option.
 *
 * @param bool $force_refresh  If false, uses cached data if available.
 * @return array
 */
function matrix_ci_fetch_all_blocks_from_github($force_refresh = false)
{
  $cached = get_option('matrix_ci_components_grouped', []);
  if (!$force_refresh && !empty($cached)) {
    return $cached;
  }

  $type_map = matrix_ci_get_block_type_map();
  $owner = 'bernardhanna';
  $repo  = 'matrix-starter-components';

  // Get the token from the admin settings (if set)
  $token = get_option('matrix_ci_github_token', '');

  $base_url = "https://api.github.com/repos/{$owner}/{$repo}/contents";

  $all_components = [];

  foreach ($type_map as $type => $info) {
    $url = $base_url . '/' . $type;
    error_log('GitHub API URL: ' . $url);

    // Prepare headers. Only add the Authorization header if a token exists.
    $headers = [
      'User-Agent' => 'Matrix-CI'
    ];
    if (!empty($token)) {
      $headers['Authorization'] = 'token ' . $token;
    }

    $response = wp_remote_get($url, [
      'headers' => $headers,
      'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
      error_log('GitHub API Error: ' . $response->get_error_message());
      $all_components[$type] = [];
      continue;
    }

    $body = wp_remote_retrieve_body($response);
    error_log('GitHub API Response: ' . $body);

    $body_data = json_decode($body, true);

    // Check for API errors
    if (isset($body_data['message'])) {
      error_log('GitHub API Error: ' . $body_data['message']);
      $all_components[$type] = [];
      continue;
    }

    if (!is_array($body_data)) {
      error_log('GitHub API Response is not an array: ' . print_r($body_data, true));
      $all_components[$type] = [];
      continue;
    }

    $components_for_type = [];

    foreach ($body_data as $item) {
      if ($item['type'] === 'dir') {
        $subfolder_url = $item['url'];
        $sub_resp = wp_remote_get($subfolder_url, [
          'headers' => $headers,
          'timeout' => 20,
        ]);

        if (is_wp_error($sub_resp)) {
          error_log('GitHub Subfolder API Error: ' . $sub_resp->get_error_message());
          continue;
        }

        $sub_body = wp_remote_retrieve_body($sub_resp);
        $sub_data = json_decode($sub_body, true);

        if (!is_array($sub_data)) {
          error_log('GitHub Subfolder API Response is not an array: ' . print_r($sub_data, true));
          continue;
        }

        $acf_file_path = '';
        $template_file_path = '';
        $preview_url = '';
        $found_acf = !$info['has_acf']; // Consider ACF found if not required
        $found_template = false;

        foreach ($sub_data as $subfile) {
          $filename = $subfile['name'];
          $download_url = $subfile['download_url'];

          if ($info['has_acf'] && strpos($filename, 'acf_') === 0 && substr($filename, -4) === '.php') {
            $acf_file_path = $download_url;
            $found_acf = true;
          }

          if (substr($filename, -4) === '.php' && strpos($filename, 'acf_') === false) {
            $template_file_path = $download_url;
            $found_template = true;
          }

          if (preg_match('/\.(png|jpe?g)$/i', $filename)) {
            $preview_url = $download_url;
          }
        }

        if ($found_acf && $found_template) {
          $components_for_type[] = [
            'folder'        => $item['name'],
            'type'          => $type,
            'acf_file'      => $acf_file_path,
            'template_file' => $template_file_path,
            'preview'       => $preview_url,
          ];
        }
      }
    }

    $all_components[$type] = $components_for_type;
  }

  update_option('matrix_ci_components_grouped', $all_components);
  return $all_components;
}

/**
 * For convenience, provide a function to get the stored data without re-fetching.
 */
function matrix_ci_get_stored_components()
{
  return get_option('matrix_ci_components_grouped', []);
}
