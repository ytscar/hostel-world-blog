<?php
/**
 * Class for auto-insert inside content.
 *
 * @package wpcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPCode_Auto_Insert_Single.
 */
class WPCode_Auto_Insert_Content extends WPCode_Auto_Insert_Type {

	/**
	 * The type unique name (slug).
	 *
	 * @var string
	 */
	public $name = 'content';

	/**
	 * The category of this type.
	 *
	 * @var string
	 */
	public $category = 'page';

	/**
	 * Load the available options and labels.
	 *
	 * @return void
	 */
	public function init() {
		$this->label = __( 'Content', 'wpcode-premium' );

		$this->locations = array(
			'after_words' => array(
				'label'       => esc_html__( 'Insert After # Words', 'wpcode-premium' ),
				'description' => esc_html__( 'Insert snippet after a minimum number of words.', 'wpcode-premium' ),
			),
			'every_words' => array(
				'label'       => esc_html__( 'Insert Every # Words', 'wpcode-premium' ),
				'description' => esc_html__( 'Insert snippet every # number of words.', 'wpcode-premium' ),
			),
		);
	}

	/**
	 * Checks if we are on a singular page and we should be executing hooks.
	 *
	 * @return bool
	 */
	public function conditions() {
		return is_singular();
	}

	/**
	 * Add hooks specific to single posts.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'the_content', array( $this, 'insert_number_words' ) );
		add_action( 'the_content', array( $this, 'insert_every_words' ) );
	}

	/**
	 * Insert the snippet after a minimum number of words.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	public function insert_number_words( $content ) {
		if ( ! is_main_query() ) {
			return $content;
		}

		$snippets = $this->get_snippets_for_location( 'after_words' );

		foreach ( $snippets as $snippet ) {
			$auto_insert_number = $snippet->get_auto_insert_number();
			$auto_insert_number = empty( $auto_insert_number ) ? 1 : absint( $auto_insert_number );
			$snippet_output     = wpcode()->execute->get_snippet_output( $snippet );
			$content            = $this->insert_after_words( $snippet_output, $auto_insert_number, $content );
		}

		return $content;

	}

	/**
	 * Insert the snippet every # number of words in the content.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	public function insert_every_words( $content ) {
		if ( ! is_main_query() ) {
			return $content;
		}

		$snippets = $this->get_snippets_for_location( 'every_words' );

		foreach ( $snippets as $snippet ) {
			$auto_insert_number = $snippet->get_auto_insert_number();
			$number_of_words    = empty( $auto_insert_number ) ? 1 : absint( $auto_insert_number );
			$snippet_output     = wpcode()->execute->get_snippet_output( $snippet );
			$content            = $this->insert_every_words_between_paragraphs( $snippet_output, $number_of_words, $content );
		}

		return $content;
	}

	/**
	 * Insert the $snippet_output after a minimum $number_of_words in the $content.
	 *
	 * @param string $snippet_output The snippet output.
	 * @param int    $number_of_words The minimum number of words to insert after.
	 * @param string $content The content to insert into.
	 * @param bool   $return_remaining Whether to return an array with the content split between the inserted and remaining content.
	 *
	 * @return string|array
	 */
	public function insert_after_words( $snippet_output, $number_of_words, $content, $return_remaining = false ) {

		if ( str_word_count( $content ) <= $number_of_words ) {
			return $content;
		}

		$words = str_word_count( $content, 2 );

		$position = 0;
		$count    = 0;
		foreach ( $words as $position => $word ) {
			// Let's check it's an actual word or number using preg_match.
			if ( preg_match( '/[a-zA-Z0-9]/', $word ) ) {
				$count ++;
			}
			if ( $count > $number_of_words ) {
				break;
			}
		}

		$paragraph            = strpos( $content, '</p>', $position );
		$content_with_snippet = substr( $content, 0, $paragraph + 4 ) . $snippet_output;
		$remaining_content    = substr( $content, $paragraph + 4 );

		if ( $return_remaining ) {
			return array(
				'content'   => $content_with_snippet,
				'remaining' => $remaining_content,
			);
		}

		$content = $content_with_snippet . $remaining_content;

		return $content;
	}

	/**
	 * Insert the $insert_text after every $number_of_words in the $content.
	 *
	 * @param string $insert_text The text to insert.
	 * @param int    $number_of_words The number of words to insert after.
	 * @param string $content The content to insert into.
	 *
	 * @return string
	 */
	public function insert_every_words_between_paragraphs( $insert_text, $number_of_words, $content ) {

		$word_count = str_word_count( $content );

		if ( $word_count <= $number_of_words ) {
			return $content;
		}

		$result    = $this->insert_after_words( $insert_text, $number_of_words, $content, true );
		$content   = $result['content'];
		$remaining = $result['remaining'];
		while ( str_word_count( $remaining ) > $number_of_words ) {
			$result    = $this->insert_after_words( $insert_text, $number_of_words, $remaining, true );
			$remaining = $result['remaining'];

			$content .= $result['content'];
		}

		$content .= $remaining;

		return $content;
	}
}

new WPCode_Auto_Insert_Content();
