<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$all_components = get_option( 'matrix_ci_components_grouped', [] );

// If flat, regroup
if ( isset( $all_components[0] ) && is_array( $all_components[0] ) && isset( $all_components[0]['type'] ) ) {
    $grouped = [];
    foreach ( $all_components as $comp ) {
        $grouped[ $comp['type'] ][] = $comp;
    }
    $all_components = $grouped;
}

$block_types = array_keys( $all_components );
?>
<div class="wrap matrix-ci-admin-page">
    <h1>Matrix Block Library</h1>

    <button id="matrix-ci-rescan" class="button button-primary">Rescan</button>

    <div class="matrix-ci-tab-container">

        <!-- Left column: tab menu -->
        <ul class="matrix-ci-tab-menu">
            <?php foreach ( $block_types as $i => $type ): ?>
                <li class="matrix-ci-tab-link <?php echo $i === 0 ? 'active' : ''; ?>"
                    data-tab="tab-<?php echo esc_attr($type); ?>">
                    <?php echo esc_html( ucfirst($type) ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php foreach ( $block_types as $i => $type ): 
            $components = $all_components[ $type ];
        ?>
            <div id="tab-<?php echo esc_attr($type); ?>"
                 class="matrix-ci-tab-content <?php echo $i === 0 ? 'active' : ''; ?>">

                <h2><?php echo esc_html( ucfirst($type) ); ?> Components</h2>

                <?php if ( empty($components) ): ?>
                    <p>No components found for <?php echo esc_html($type); ?>.</p>
                <?php else: ?>
                    <div class="matrix-ci-grid">
                        <?php foreach ( $components as $comp ): ?>
                            <div class="matrix-ci-grid-item">
                                <div class="matrix-ci-grid-item-preview">
                                    <?php if ( ! empty($comp['preview']) ): ?>
                                        <img src="<?php echo esc_url( admin_url( 'admin-ajax.php?action=matrix_ci_preview_proxy&file=' . urlencode( $comp['preview'] ) ) ); ?>" alt="Preview">
                                    <?php else: ?>
                                        <div class="no-preview">No Preview</div>
                                    <?php endif; ?>
                                </div>
                                <div class="matrix-ci-grid-item-info">
                                    <p><strong>Folder:</strong> <?php echo esc_html($comp['folder']); ?></p>
                                    <button class="button matrix-ci-import-btn"
                                            data-type="<?php echo esc_attr($type); ?>"
                                            data-folder="<?php echo esc_attr($comp['folder']); ?>">
                                        Import
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div><!-- .matrix-ci-tab-container -->
</div>
