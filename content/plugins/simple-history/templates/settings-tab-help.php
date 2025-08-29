<?php
/**
 * Template for the Help & Support tab.
 *
 * @package SimpleHistory
 */

namespace Simple_History;

use Simple_History\Dropins\Sidebar_Add_Ons_Dropin;

defined( 'ABSPATH' ) || die();

// FAQ items from https://simple-history.com/docs/faq-frequently-asked-questions/.
$faq_items = [
	[
		'question' => 'How do I control what is logged?',
	],
	[
		'question' => 'Can I log when a user does something on my website, like visits a page?',
	],
	[
		'question' => 'Can we track things that happen on the frontend of our website?',
	],
	[
		'question' => 'Can the RSS feed show more than 10 items?',
	],
	[
		'question' => 'Can the log be shown on the front of my website?',
	],
	[
		'question' => 'How do I clear the log?',
	],
	[
		'question' => 'Can I keep items longer than 60 days?',
	],
	[
		'question' => 'How do I see more details about an event?',
	],
	[
		'question' => 'How can I see the IP address of an event?',
	],
	[
		'question' => 'Why is the IP address always end with a zero (0)',
	],
	[
		'question' => 'Who is the "other" user that sometimes is responsible for logged events?',
	],
	[
		'question' => 'Is the plugin GDPR compliant?',
	],
	[
		'question' => 'My question in not answered in this FAQ',
	],
];
$faq_url = 'https://simple-history.com/docs/faq-frequently-asked-questions/';

?>

<style>
	.sh-HelpAndSupportPage > .sh-SettingsPage-settingsSection-title {
		margin-bottom: 1rem;
	}
</style>

<div class="wrap sh-HelpAndSupportPage">
	<?php
	echo wp_kses(
		Helpers::get_settings_section_title_output( __( 'Get help and support', 'simple-history' ), 'help' ),
		[
			'span' => [
				'class' => [],
			],
		]
	);
	?>
	<div class="sh-grid sh-grid-cols-1/2">
		
		<div class="postbox sh-PremiumFeaturesPostbox">
			<div class="inside">
				<h3 class="sh-PremiumFeaturesPostbox-title"><?php echo esc_html_x( 'Support', 'help page section title', 'simple-history' ); ?></h3>
				<p>
					<?php
					printf(
						/* translators: 1: Link to support forum. */
						wp_kses_post( __( 'For support with the free version, please visit the <a href="%1$s" target="_blank">WordPress.org support forum</a>.', 'simple-history' ) ),
						'https://simple-history.com/support/'
					);
					?>
				</p>
				<p>
					<?php
					printf(
						/* translators: 1: Link to premium support. */
						wp_kses_post( __( 'If you\'re using Simple History Premium or any add-ons, you can access <a href="%1$s" target="_blank">premium support</a>.', 'simple-history' ) ),
						'https://simple-history.com/support/'
					);
					?>
				</p>
			</div>
		</div>
	
		<div class="postbox sh-PremiumFeaturesPostbox">
			<div class="inside">
				<h3 class="sh-PremiumFeaturesPostbox-title"><?php echo esc_html_x( 'Documentation', 'help page section title', 'simple-history' ); ?></h3>
				<p>
					<?php
					printf(
						/* translators: 1: Link to documentation website. */
						wp_kses_post( __( 'Visit the <a href="%1$s" target="_blank">Simple History documentation website</a> for detailed information about features, settings, and how to get the most out of Simple History.', 'simple-history' ) ),
						'https://simple-history.com/docs/'
					);
					?>
				</p>
			</div>
		</div>

		<div class="postbox sh-PremiumFeaturesPostbox">
			<div class="inside">
				<h3 class="sh-PremiumFeaturesPostbox-title"><?php echo esc_html_x( 'Frequently Asked Questions', 'help page section title', 'simple-history' ); ?></h3>
				
				<ul>
					<?php
					$faq_num_items_to_show = 5;

					for ( $i = 0; $i < $faq_num_items_to_show; $i++ ) {
						$faq_item = $faq_items[ $i ];
						?>
						<li>
							<a href="<?php echo esc_url( $faq_url ); ?>"><?php echo esc_html( $faq_item['question'] ); ?></a>
						</li>
						<?php
					}
					?>
				</ul>

				<p><a href="<?php echo esc_url( $faq_url ); ?>">» View all Frequently Asked Questions</a>.</p>
			</div>
		</div>
	</div>

	<!-- Grid with premium features. -->
	<div class="sh-grid sh-grid-cols-1/3">
		<?php
		if ( ! Helpers::is_premium_add_on_active() ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo Sidebar_Add_Ons_Dropin::get_premium_features_postbox_html();
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo Sidebar_Add_Ons_Dropin::get_woocommerce_logger_features_postbox_html();
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo Sidebar_Add_Ons_Dropin::get_debug_and_monitor_features_postbox_html();
		}
		?>
	</div>

</div>
