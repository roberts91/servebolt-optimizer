<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once 'optimize-db/checks.php';

?>

<div class="wrap sb-content">
	<?php if (isset($_GET['optimize-now'])) : ?>
        <div class="notice notice-success is-dismissible"><p><?php _e('Cache settings saved!', 'servebolt-wp'); ?></p></div>
	<?php endif; ?>
    <h2>⚡️<?php _e('Servebolt Optimize', 'servebolt-wp'); ?></h2>
    <h3><?php _e('Database Indexes', 'servebolt-wp'); ?></h3>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Optimization', 'servebolt-wp'); ?></th>
                <th><?php _e('Status', 'servebolt-wp'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><?php _e('Optimization', 'servebolt-wp'); ?></th>
                <th><?php _e('Status', 'servebolt-wp'); ?></th>
            </tr>
            </tfoot>
            <tbody>
            <tr>
                <td><?php _e('Index on autoload column in _options table', 'servebolt-wp'); ?></td>
                <td><?php echo options_has_index(); ?></td>
            </tr>
            <tr>
                <td><?php _e('Index on meta_value column in _postmeta', 'servebolt-wp'); ?></td>
                <td><?php echo postmeta_has_index(); ?></td>
            </tr>
        </tbody>
    </table>
    <h3><?php _e('Database Table Storage Engines'); ?></h3>
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th><?php _e('Table', 'servebolt-wp'); ?></th>
            <th><?php _e('Engine', 'servebolt-wp'); ?></th>
            <th><?php _e('Convert to', 'servebolt-wp'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th><?php _e('Table', 'servebolt-wp'); ?></th>
            <th><?php _e('Engine', 'servebolt-wp'); ?></th>
            <th><?php _e('Convert to', 'servebolt-wp'); ?></th>
        </tr>
        </tfoot>
        <tbody>
		<?php
		$myisam_tables = get_myisam_tables();
		if (empty($myisam_tables)) {
          echo '<tr>';
          echo '  <td>' . esc_html__('All tables use modern storage engines','servebolt-wp') . '</td>';
          echo '  <td></td>';
          echo '  <td></td>';
          echo '</tr>';
		}
		else {
          foreach ( $myisam_tables as $obj ) {
            echo '<tr>';
            echo '  <td>' . $obj->TABLE_NAME . '</td>';
            echo '  <td>' . $obj->ENGINE . '</td>';
            echo '  <td><a href="#optimize" class="optimize-now">' . _e('Convert to InnoDB', 'servebolt-wp') . '</a></td>';
            echo '</tr>';
          }
		}
		?>
        </tbody>

    </table>
    <div class="optimize">
        <h3><?php _e('Run the optimizer', 'servebolt-wp'); ?></h3>
        <p><?php _e('You can run the optimizer below.', 'servebolt-wp'); ?><br>
        <strong><?php _e('Always backup your database before running optimization!', 'servebolt-wp'); ?></strong>
        </p>
        <a href="#optimize-now" class="btn button button-primary optimize-now"><?php _e('Optimize!', 'servebolt-wp'); ?></a>
    </div>
    <h2><?php _e('Other suggested optimizations'); ?></h2>
    <p><?php _e('These settings can not be optimized by the plugin, but may be implemented manually.', 'servebolt-wp'); ?></p>
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th><?php _e('Optimization', 'servebolt-wp'); ?></th>
            <th><?php _e('How to', 'servebolt-wp'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th><?php _e('Optimization', 'servebolt-wp'); ?></th>
            <th><?php _e('How to', 'servebolt-wp'); ?></th>
        </tr>
        </tfoot>
        <tbody>
        <tr>
            <td>
                <?php _e('Disable WP Cron and run it from server cron', 'servebolt-wp'); ?>
            </td>
            <td>
                <?php echo (wp_cron_disabled() === true)
                    ? '<img src="' . plugin_dir_url( __FILE__ ) . 'img/checked.png" width="20"> WP Cron is disabled. Remember to set on cron on the server.'
                    : '<img src="' . plugin_dir_url( __FILE__ ) . 'img/cancel.png" width="20"> WP Cron is enabled, and may slow down your site and/or degrade the sites ability to scale. This should be disabled and run with server cron.';
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>