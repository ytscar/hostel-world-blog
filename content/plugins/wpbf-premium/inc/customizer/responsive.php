<?php
/**
 * Responsive styles.
 *
 * Holds responsive CSS styles.
 * Only active if custom breakpoints are set.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

function wpbf_premium_responsive_customizer_css() {

	// Stop here if responsive breakpoints aren't set.
	if ( ! wpbf_has_responsive_breakpoints() ) {
		return;
	}

	$breakpoint_mobile_int  = wpbf_breakpoint_mobile();
	$breakpoint_medium_int  = wpbf_breakpoint_medium();
	$breakpoint_desktop_int = wpbf_breakpoint_desktop();

	$breakpoint_mobile  = $breakpoint_mobile_int . 'px';
	$breakpoint_medium  = $breakpoint_medium_int . 'px';
	$breakpoint_desktop = $breakpoint_desktop_int . 'px';

	$margin_large  = $padding_large  = '80px';
	$margin_medium = $padding_medium = '40px';
	$margin        = $padding        = '20px';

	?>

	/* From <?php echo esc_attr( $breakpoint_mobile_int ) + 1; ?>px | Phone Landscape & Bigger */
	@media (min-width: <?php echo esc_attr( $breakpoint_mobile_int ) + 1; ?>px) {

		/* Grid */
		.wpbf-grid-small-1-1 > * {
			width: 100%;
		}
		.wpbf-grid-small-1-2 > * {
			width: 50%;
		}
		.wpbf-grid-small-1-3 > * {
			width: 33.333%;
		}
		.wpbf-grid-small-2-3 > * {
			width: 66.666%;
		}
		.wpbf-grid-small-1-4 > * {
			width: 25%;
		}
		.wpbf-grid-small-1-5 > * {
			width: 20%;
		}
		.wpbf-grid-small-1-6 > * {
			width: 16.666%;
		}
		.wpbf-grid-small-1-10 > * {
			width: 10%;
		}

		/* Grid Cells */

		/* Whole */
		.wpbf-small-1-1 {
			width: 100%;
		}
		/* Halves */
		.wpbf-small-1-2,
		.wpbf-small-2-4,
		.wpbf-small-3-6,
		.wpbf-small-5-10 {
			width: 50%;
		}
		/* Thirds */
		.wpbf-small-1-3,
		.wpbf-small-2-6 {
			width: 33.333%;
		}
		.wpbf-small-2-3,
		.wpbf-small-4-6 {
			width: 66.666%;
		}
		/* Quarters */
		.wpbf-small-1-4 {
			width: 25%;
		}
		.wpbf-small-3-4 {
			width: 75%;
		}
		/* Fifths */
		.wpbf-small-1-5,
		.wpbf-small-2-10 {
			width: 20%;
		}
		.wpbf-small-2-5,
		.wpbf-small-4-10 {
			width: 40%;
		}
		.wpbf-small-3-5,
		.wpbf-small-6-10 {
			width: 60%;
		}
		.wpbf-small-4-5,
		.wpbf-small-8-10 {
			width: 80%;
		}
		/* Sixths */
		.wpbf-small-1-6 {
			width: 16.666%;
		}
		.wpbf-small-5-6 {
			width: 83.333%;
		}
		/* Tenths */
		.wpbf-small-1-10 {
			width: 10%;
		}
		.wpbf-small-3-10 {
			width: 30%;
		}
		.wpbf-small-7-10 {
			width: 70%;
		}
		.wpbf-small-9-10 {
			width: 90%;
		}

	}

	/* From <?php echo esc_attr( $breakpoint_medium_int ) + 1; ?>px | Tablet & Bigger */
	@media (min-width: <?php echo esc_attr( $breakpoint_medium_int ) + 1; ?>px) {

		/* Gutenberg */

		.wpbf-no-sidebar .alignwide {
			margin-left: -50px;
			margin-right: -50px;
			max-width: unset;
		}

		/* Sidebar */
		.wpbf-grid-divider > [class*='wpbf-medium-']:not(.wpbf-medium-1-1):nth-child(n+2) {
			border-left: 1px solid #d9d9e0;
		}

		/* Grid */
		.wpbf-grid-medium-1-1 > * {
			width: 100%;
		}
		.wpbf-grid-medium-1-2 > * {
			width: 50%;
		}
		.wpbf-grid-medium-1-3 > * {
			width: 33.333%;
		}
		.wpbf-grid-medium-2-3 > * {
			width: 66.666%;
		}
		.wpbf-grid-medium-1-4 > * {
			width: 25%;
		}
		.wpbf-grid-medium-1-5 > * {
			width: 20%;
		}
		.wpbf-grid-medium-1-6 > * {
			width: 16.666%;
		}
		.wpbf-grid-medium-1-10 > * {
			width: 10%;
		}

		/* Grid Cells */

		/* Whole */
		.wpbf-medium-1-1 {
			width: 100%;
		}
		/* Halves */
		.wpbf-medium-1-2,
		.wpbf-medium-2-4,
		.wpbf-medium-3-6,
		.wpbf-medium-5-10 {
			width: 50%;
		}
		/* Thirds */
		.wpbf-medium-1-3,
		.wpbf-medium-2-6 {
			width: 33.333%;
		}
		.wpbf-medium-2-3,
		.wpbf-medium-4-6 {
			width: 66.666%;
		}
		/* Quarters */
		.wpbf-medium-1-4 {
			width: 25%;
		}
		.wpbf-medium-3-4 {
			width: 75%;
		}
		/* Fifths */
		.wpbf-medium-1-5,
		.wpbf-medium-2-10 {
			width: 20%;
		}
		.wpbf-medium-2-5,
		.wpbf-medium-4-10 {
			width: 40%;
		}
		.wpbf-medium-3-5,
		.wpbf-medium-6-10 {
			width: 60%;
		}
		.wpbf-medium-4-5,
		.wpbf-medium-8-10 {
			width: 80%;
		}
		/* Sixths */
		.wpbf-medium-1-6 {
			width: 16.666%;
		}
		.wpbf-medium-5-6 {
			width: 83.333%;
		}
		/* Tenths */
		.wpbf-medium-1-10 {
			width: 10%;
		}
		.wpbf-medium-3-10 {
			width: 30%;
		}
		.wpbf-medium-7-10 {
			width: 70%;
		}
		.wpbf-medium-9-10 {
			width: 90%;
		}

	}

	/* From <?php echo esc_attr( $breakpoint_desktop_int ) + 1; ?>px | Desktop & Bigger */
	@media (min-width: <?php echo esc_attr( $breakpoint_desktop_int ) + 1; ?>px) {

		/* Gutenberg */

		.wpbf-no-sidebar .alignwide {
			margin-left: -75px;
			margin-right: -75px;
		}

		/* Sidebar */
		.wpbf-grid-divider > [class*='wpbf-large-']:not(.wpbf-large-1-1):nth-child(n+2) {
			border-left:		1px solid #d9d9e0;
		}

		/* Grid */
		.wpbf-grid-large-1-1 > * {
			width: 100%;
		}
		.wpbf-grid-large-1-2 > * {
			width: 50%;
		}
		.wpbf-grid-large-1-3 > * {
			width: 33.333%;
		}
		.wpbf-grid-large-2-3 > * {
			width: 66.666%;
		}
		.wpbf-grid-large-1-4 > * {
			width: 25%;
		}
		.wpbf-grid-large-1-5 > * {
			width: 20%;
		}
		.wpbf-grid-large-1-6 > * {
			width: 16.666%;
		}
		.wpbf-grid-large-1-10 > * {
			width: 10%;
		}

		/* Grid Cells */

		/* Whole */
		.wpbf-large-1-1 {
			width: 100%;
		}
		/* Halves */
		.wpbf-large-1-2,
		.wpbf-large-2-4,
		.wpbf-large-3-6,
		.wpbf-large-5-10 {
			width: 50%;
		}
		/* Thirds */
		.wpbf-large-1-3,
		.wpbf-large-2-6 {
			width: 33.333%;
		}
		.wpbf-large-2-3,
		.wpbf-large-4-6 {
			width: 66.666%;
		}
		/* Quarters */
		.wpbf-large-1-4 {
			width: 25%;
		}
		.wpbf-large-3-4 {
			width: 75%;
		}
		/* Fifths */
		.wpbf-large-1-5,
		.wpbf-large-2-10 {
			width: 20%;
		}
		.wpbf-large-2-5,
		.wpbf-large-4-10 {
			width: 40%;
		}
		.wpbf-large-3-5,
		.wpbf-large-6-10 {
			width: 60%;
		}
		.wpbf-large-4-5,
		.wpbf-large-8-10 {
			width: 80%;
		}
		/* Sixths */
		.wpbf-large-1-6 {
			width: 16.666%;
		}
		.wpbf-large-5-6 {
			width: 83.333%;
		}
		/* Tenths */
		.wpbf-large-1-10 {
			width: 10%;
		}
		.wpbf-large-3-10 {
			width: 30%;
		}
		.wpbf-large-7-10 {
			width: 70%;
		}
		.wpbf-large-9-10 {
			width: 90%;
		}

	}

	/* From 1201px | Large Screen & Bigger */
	@media (min-width: 1201px) {

		.wpbf-grid-xlarge-1-1 > * {
			width: 100%;
		}
		.wpbf-grid-xlarge-1-2 > * {
			width: 50%;
		}
		.wpbf-grid-xlarge-1-3 > * {
			width: 33.333%;
		}
		.wpbf-grid-xlarge-2-3 > * {
			width: 66.666%;
		}
		.wpbf-grid-xlarge-1-4 > * {
			width: 25%;
		}
		.wpbf-grid-xlarge-1-5 > * {
			width: 20%;
		}
		.wpbf-grid-xlarge-1-6 > * {
			width: 16.666%;
		}
		.wpbf-grid-xlarge-1-10 > * {
			width: 10%;
		}

		/* Grid Cells */

		/* Whole */
		.wpbf-xlarge-1-1 {
			width: 100%;
		}
		/* Halves */
		.wpbf-xlarge-1-2,
		.wpbf-xlarge-2-4,
		.wpbf-xlarge-3-6,
		.wpbf-xlarge-5-10 {
			width: 50%;
		}
		/* Thirds */
		.wpbf-xlarge-1-3,
		.wpbf-xlarge-2-6 {
			width: 33.333%;
		}
		.wpbf-xlarge-2-3,
		.wpbf-xlarge-4-6 {
			width: 66.666%;
		}
		/* Quarters */
		.wpbf-xlarge-1-4 {
			width: 25%;
		}
		.wpbf-xlarge-3-4 {
			width: 75%;
		}
		/* Fifths */
		.wpbf-xlarge-1-5,
		.wpbf-xlarge-2-10 {
			width: 20%;
		}
		.wpbf-xlarge-2-5,
		.wpbf-xlarge-4-10 {
			width: 40%;
		}
		.wpbf-xlarge-3-5,
		.wpbf-xlarge-6-10 {
			width: 60%;
		}
		.wpbf-xlarge-4-5,
		.wpbf-xlarge-8-10 {
			width: 80%;
		}
		/* Sixths */
		.wpbf-xlarge-1-6 {
			width: 16.666%;
		}
		.wpbf-xlarge-5-6 {
			width: 83.333%;
		}
		/* Tenths */
		.wpbf-xlarge-1-10 {
			width: 10%;
		}
		.wpbf-xlarge-3-10 {
			width: 30%;
		}
		.wpbf-xlarge-7-10 {
			width: 70%;
		}
		.wpbf-xlarge-9-10 {
			width: 90%;
		}

	}

	/* Until 1200 */
	@media screen and (max-width: 1200px) {

		/* Margin */
		.wpbf-margin-xlarge {
			margin-top: <?php echo $margin_large; ?>;
			margin-bottom: <?php echo $margin_large; ?>;
		}
		.wpbf-margin-xlarge-top {
			margin-top: <?php echo $margin_large; ?>;
		}
		.wpbf-margin-xlarge-bottom {
			margin-bottom: <?php echo $margin_large; ?>;
		}
		.wpbf-margin-xlarge-left {
			margin-left: <?php echo $margin_large; ?>;
		}
		.wpbf-margin-xlarge-right {
			margin-right: <?php echo $margin_large; ?>;
		}

		/* Padding */
		.wpbf-padding-xlarge {
			padding-top: <?php echo $padding_large; ?>;
			padding-bottom: <?php echo $padding_large; ?>;
		}
		.wpbf-padding-xlarge-top {
			padding-top: <?php echo $padding_large; ?>;
		}
		.wpbf-padding-xlarge-bottom {
			padding-bottom: <?php echo $padding_large; ?>;
		}
		.wpbf-padding-xlarge-left {
			padding-left: <?php echo $padding_large; ?>;
		}
		.wpbf-padding-xlarge-right {
			padding-right: <?php echo $padding_large; ?>;
		}

	}

	/* Until <?php echo esc_attr( $breakpoint_desktop ); ?> */
	@media screen and (max-width: <?php echo esc_attr( $breakpoint_desktop ); ?>) {

		/* Margin */
		.wpbf-margin-large, .wpbf-margin-xlarge {
			margin-top: <?php echo $margin_medium; ?>;
			margin-bottom: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-large-top {
			margin-top: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-large-bottom {
			margin-bottom: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-large-left {
			margin-left: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-large-right {
			margin-right: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-xlarge-top {
			margin-top: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-xlarge-bottom {
			margin-bottom: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-xlarge-left {
			margin-left: <?php echo $margin_medium; ?>;
		}
		.wpbf-margin-xlarge-right {
			margin-right: <?php echo $margin_medium; ?>;
		}

		/* Padding */
		.wpbf-padding-large, .wpbf-padding-xlarge {
			padding-top: <?php echo $padding_medium; ?>;
			padding-bottom: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-large-top {
			padding-top: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-large-bottom {
			padding-bottom: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-large-left {
			padding-left: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-large-right {
			padding-right: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-xlarge-top {
			padding-top: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-xlarge-bottom {
			padding-bottom: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-xlarge-left {
			padding-left: <?php echo $padding_medium; ?>;
		}
		.wpbf-padding-xlarge-right {
			padding-right: <?php echo $padding_medium; ?>;
		}

	}

	/* Until <?php echo esc_attr( $breakpoint_medium ); ?> */
	@media screen and (max-width: <?php echo esc_attr( $breakpoint_medium ); ?>) {

		/* General */

		.wpbf-footer-two-columns,
		.wpbf-pre-header-two-columns {
			display: block;
		}

		.wpbf-footer-two-columns .wpbf-inner-footer-left,
		.wpbf-footer-two-columns .wpbf-inner-footer-right,
		.wpbf-pre-header-two-columns .wpbf-inner-pre-header-left,
		.wpbf-pre-header-two-columns .wpbf-inner-pre-header-right {
			display: block;
			width: 100%;
			text-align: center;
		}

		.wpbf-page-footer .wpbf-inner-footer-right .wpbf-menu {
			float: none;
			width: 100%;
	 		display: flex;
	 		align-items: center;
	 		justify-content: center;
		}

		.wpbf-page-footer .wpbf-inner-footer-left .wpbf-menu {
			float: none;
			width: 100%;
	 		display: flex;
	 		align-items: center;
	 		justify-content: center;
		}

	}

	/* Until <?php echo esc_attr( $breakpoint_mobile ); ?> */
	@media screen and (max-width: <?php echo esc_attr( $breakpoint_mobile ); ?>) {

		/* Margin */
		.wpbf-margin-medium, .wpbf-margin-large, .wpbf-margin-xlarge {
			margin-top: <?php echo $margin; ?>;
			margin-bottom: <?php echo $margin; ?>;
		}
		.wpbf-margin-large-top {
			margin-top: <?php echo $margin; ?>;
		}
		.wpbf-margin-large-bottom {
			margin-bottom: <?php echo $margin; ?>;
		}
		.wpbf-margin-large-left {
			margin-left: <?php echo $margin; ?>;
		}
		.wpbf-margin-large-right {
			margin-right: <?php echo $margin; ?>;
		}
		.wpbf-margin-medium-top {
			margin-top: <?php echo $margin; ?>;
		}
		.wpbf-margin-medium-bottom {
			margin-bottom: <?php echo $margin; ?>;
		}
		.wpbf-margin-medium-left {
			margin-left: <?php echo $margin; ?>;
		}
		.wpbf-margin-medium-right {
			margin-right: <?php echo $margin; ?>;
		}
		.wpbf-margin-xlarge-top {
			margin-top: <?php echo $margin; ?>;
		}
		.wpbf-margin-xlarge-bottom {
			margin-bottom: <?php echo $margin; ?>;
		}
		.wpbf-margin-xlarge-left {
			margin-left: <?php echo $margin; ?>;
		}
		.wpbf-margin-xlarge-right {
			margin-right: <?php echo $margin; ?>;
		}

		/* Padding */
		.wpbf-padding-medium, .wpbf-padding-large, .wpbf-padding-xlarge {
			padding-top: <?php echo $padding; ?>;
			padding-bottom: <?php echo $padding; ?>;
		}
		.wpbf-padding-large-top {
			padding-top: <?php echo $padding; ?>;
		}
		.wpbf-padding-large-bottom {
			padding-bottom: <?php echo $padding; ?>;
		}
		.wpbf-padding-large-left {
			padding-left: <?php echo $padding; ?>;
		}
		.wpbf-padding-large-right {
			padding-right: <?php echo $padding; ?>;
		}
		.wpbf-padding-medium-top {
			padding-top: <?php echo $padding; ?>;
		}
		.wpbf-padding-medium-bottom {
			padding-bottom: <?php echo $padding; ?>;
		}
		.wpbf-padding-medium-left {
			padding-left: <?php echo $padding; ?>;
		}
		.wpbf-padding-medium-right {
			padding-right: <?php echo $padding; ?>;
		}
		.wpbf-padding-xlarge-top {
			padding-top: <?php echo $padding; ?>;
		}
		.wpbf-padding-xlarge-bottom {
			padding-bottom: <?php echo $padding; ?>;
		}
		.wpbf-padding-xlarge-left {
			padding-left: <?php echo $padding; ?>;
		}
		.wpbf-padding-xlarge-right {
			padding-right: <?php echo $padding; ?>;
		}

	}

	/* Visibility */

	/* Desktop and bigger */
	@media (min-width: <?php echo esc_attr( $breakpoint_desktop_int ) + 1 ?>px) {
		.wpbf-visible-small {
			display: none !important;
		}
		.wpbf-visible-medium {
			display: none !important;
		}
		.wpbf-hidden-large {
			display: none !important;
		}
	}
	/* Tablets portrait */
	@media (min-width: <?php echo esc_attr( $breakpoint_medium_int ) + 1 ?>px) and (max-width: <?php echo esc_attr( $breakpoint_desktop ); ?>) {
		.wpbf-visible-small {
			display: none !important;
		}
		.wpbf-visible-large {
			display: none !important ;
		}
		.wpbf-hidden-medium {
			display: none !important;
		}
	}
	/* Tablets */
	@media (max-width: <?php echo esc_attr( $breakpoint_medium ); ?>) {
		.wpbf-visible-medium {
			display: none !important;
		}
		.wpbf-visible-large {
			display: none !important;
		}
		.wpbf-hidden-small {
			display: none !important;
		}
	}

	/* Row & column order */

	/* Desktop and bigger */
	@media (min-width:  <?php echo esc_attr( $breakpoint_desktop_int ) + 1 ?>px) {
		.wpbf-row-reverse-large {
			flex-direction: row-reverse;
		}
		.wpbf-column-reverse-large {
			flex-direction: column-reverse;
		}
	}

	/* Tablets */
	@media screen and (max-width: <?php echo esc_attr( $breakpoint_medium ); ?>) {
		.wpbf-row-reverse-medium {
			flex-direction: row-reverse;
		}
		.wpbf-column-reverse-medium {
			flex-direction: column-reverse;
		}
	}

	/* Mobiles */
	@media screen and (max-width: <?php echo esc_attr( $breakpoint_mobile ); ?>) {
		.wpbf-row-reverse-small {
			flex-direction: row-reverse;
		}
		.wpbf-column-reverse-small {
			flex-direction: column-reverse;
		}
	}

<?php

}
add_action( 'wpbf_after_customizer_css', 'wpbf_premium_responsive_customizer_css', 20 );
