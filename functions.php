<?php
/**
 * Plugin Name:       Plugin Blocks
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       plugin-blocks
 *
 * @package           plugin-blocks
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function plugin_blocks_init() {
	$blocks = glob( __DIR__ . '/build/*' );

	foreach ( $blocks as $block ) {
		$filepath = $block . '/render.php';
		$args = array();

		/**
		 * If the block has a render.php file, it will be used to render the block (ssr).
		 * render property in block.json will be in use since WordPress 6.1.0
		 * currently use the render property to copy the render.php file to the build folder
		 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#render
		 */
		if ( file_exists( $filepath ) ) {
			$args['render_callback'] = function( $attributes, $content ) use ( $filepath ) {
				ob_start();
				include $filepath;
				return ob_get_clean();
			};
		}

		register_block_type( $block, $args );
	}
}

add_action( 'init', 'plugin_blocks_init' );
