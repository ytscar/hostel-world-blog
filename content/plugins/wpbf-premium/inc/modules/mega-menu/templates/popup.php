<?php
/**
 * Popup template.
 *
 * @package Page Builder Framework Premium Add On
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="wpbf-menu-item-settings-popup-overlay" data-menu-item-id="" data-nav-menu-id="" data-menu-item-depth="">
	<div class="wpbf-menu-item-settings-popup">
		<header class="wpbf-menu-item-settings-popup-header">
			<button class="wpbf-menu-item-settings-close-button">
				×
			</button>
			<h2>
				<span class="wpbf-menu-item-settings-replace-title"></span>
				-
				<?php _e( 'Menu Item Settings', 'wpbfpremium' ); ?>
			</h2>
		</header>
		<div class="wpbf-menu-item-settings-popup-content">

			<div class="wpbf-menu-item-settings-field">
				<label for="" class="wpbf-menu-item-settings-label">
					<?php _e( 'Mega Menu', 'wpbfpremium' ); ?>
				</label>
				<div class="wpbf-menu-item-settings-control">
					<div class="switch-control is-rounded is-small">
						<label for="wpbf_enable_mega_menu">
							<input
								type="checkbox"
								id="wpbf_enable_mega_menu"
								value="1"
							>

							<span class="switch"></span>
						</label>
					</div>
				</div>
			</div>

			<div class="wpbf-menu-item-settings-field wpbf-is-hidden">
				<label for="" class="wpbf-menu-item-settings-label">
					<?php _e( 'Width', 'wpbfpremium' ); ?>
				</label>
				<div class="wpbf-menu-item-settings-control">
					<select id="wpbf_mega_menu_dropdown_width_type">
						<option value="container-width">
							<?php _e( 'Container Width', 'wpbfpremium' ); ?>
						</option>
						<option value="full-width">
							<?php _e( 'Full Width', 'wpbfpremium' ); ?>
						</option>
						<option value="custom-width">
							<?php _e( 'Custom Width', 'wpbfpremium' ); ?>
						</option>
					</select>
				</div>
			</div>

			<div class="wpbf-menu-item-settings-field wpbf-is-hidden">
				<label for="wpbf_mega_menu_dropdown_custom_width_slider" class="wpbf-menu-item-settings-label">
					<?php _e( 'Custom Width', 'wpbfpremium' ); ?>
				</label>
				<div class="wpbf-menu-item-settings-control">

					<div class="wpbf-menu-item-settings-range-slider">
						<div class="wpbf-menu-item-settings-slider-field-wrapper">
							<input
								type="range"
								value="400"
								min="0"
								max="1000"
								step="1"
								class="wpbf-menu-item-settings-slider-field"
								id="wpbf_mega_menu_dropdown_custom_width_slider"
							/>
						</div>
						<div class="wpbf-menu-item-settings-slider-value-wrapper">
							<input
								type="text"
								class="wpbf-menu-item-settings-slider-value"
								id="wpbf_mega_menu_dropdown_custom_width"
								value="400px"
							>
						</div>
					</div>

				</div>
			</div>

			<div class="wpbf-menu-item-settings-field wpbf-is-hidden">
				<label for="" class="wpbf-menu-item-settings-label">
					<?php _e( 'Columns', 'wpbfpremium' ); ?>
				</label>
				<div class="wpbf-menu-item-settings-control">
					<ul class="wpbf-menu-item-settings-number-selector">
						<li data-wpbf-value="1">1</li>
						<li data-wpbf-value="2" class="is-active">2</li>
						<li data-wpbf-value="3">3</li>
						<li data-wpbf-value="4">4</li>
						<li data-wpbf-value="5">5</li>
						<li data-wpbf-value="6">6</li>
					</ul>
					<input type="hidden" id="wpbf_mega_menu_dropdown_column" value="2">
				</div>
			</div>

		</div>
		<footer class="wpbf-menu-item-settings-popup-footer">
			<button type="button" class="button-primary wpbf-done-settings-button">
				<?php _e( 'Done', 'wpbfpremium' ); ?>
			</button>
		</footer>
	</div>
</div>
