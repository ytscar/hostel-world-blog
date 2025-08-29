<?php
/**
 * Displays the strings translations machine translation form.
 *
 * @package Polylang-Pro
 * @since 3.7
 *
 * @var PLL_Model $model `PLL_Model` instance.
 */

defined( 'ABSPATH' ) || exit; // Don't access directly.

$url       = admin_url( 'admin.php?page=mlang_strings&translate=machine-translation&noheader=true' );
$languages = $model->get_languages_list();
$strings   = PLL_Admin_Strings::get_strings();
$groups    = array_unique( wp_list_pluck( $strings, 'context' ) );
?>
<div class="form-wrap">
	<form id="machine-strings-translations" method="post" action="<?php echo esc_url( $url ); ?>">
		<div class="form-field">
			<fieldset>
				<?php wp_nonce_field( PLL_Export_Strings_Action::ACTION_NAME, PLL_Export_Strings_Action::NONCE_NAME ); ?>
				<input type="hidden" name="pll_action" value="machine-translations" />
				<legend class="pll-legend"><?php esc_html_e( 'Target languages', 'polylang-pro' ); ?></legend>
				<?php
				foreach ( $languages as $language ) {
					if ( ! $language->is_default ) {
						printf(
							'<label><input name="target-lang[]" type="checkbox" value="%1$s" /><span class="pll-translation-flag">%3$s</span>%2$s</label>',
							esc_attr( $language->slug ),
							esc_html( $language->name ),
							$language->flag // phpcs:ignore WordPress.Security.EscapeOutput
						);
					}
				}
				?>
			</fieldset>
		</div>
		<div class="form-field">
			<label for="select-groups-machine"><?php esc_html_e( 'Filter group', 'polylang-pro' ); ?></label>
			<select name="group" id="select-groups-machine">
				<option selected value="-1">
					<?php esc_html_e( 'Translate all groups', 'polylang-pro' ); ?>
				</option>
				<?php
				foreach ( $groups as $group ) {
					?>
					<option value="<?php echo esc_attr( $group ); ?>">
						<?php echo esc_html( $group ); ?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
		<p class="submit">
			<button type="submit" name="action" class="button button-primary" value="translate"><?php echo esc_html__( 'Translate', 'polylang-pro' ); ?></button>
		</p>
	</form>
</div>
