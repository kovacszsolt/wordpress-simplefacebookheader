<?php
if ( ! class_exists( "FingerSimpleFacbookHeader" ) ) {
	/**
	 * Simple Facebook Header Frontend
	 * Class FingerSimpleFacbookHeader
	 */
	class FingerSimpleFacbookHeader {

		/**
		 * Header items
		 * @var array
		 */
		private $_data = array(
			'title'       => '',
			'description' => '',
			'image'       => '',
			'pageid'      => '',
			'appid'       => ''
		);

		/**
		 * FingerSimpleFacbookHeader constructor.
		 */
		public function __construct() {

			// read from options
			$_tmp = get_option( 'finger_simple_facbook_settings', array() );
			// initial values
			$this->_data['title']       = \Finger::checkArrayItem( $_tmp, 'title', '' );
			$this->_data['description'] = \Finger::checkArrayItem( $_tmp, 'description', '' );
			$this->_data['image']       = \Finger::checkArrayItem( $_tmp, 'image', '' );
			$this->_data['pageid']      = \Finger::checkArrayItem( $_tmp, 'pageid', '' );
			$this->_data['appid']       = \Finger::checkArrayItem( $_tmp, 'appid', '' );
		}

		private function _frontPage() {

		}

		/**
		 * Category Page
		 */
		private function _categoryPage() {
			$_category                  = get_the_category();
			$this->_data['title']       = $_category[0]->name;
			$this->_data['description'] = $_category[0]->description;
		}

		/**
		 * Tag Page
		 */
		private function _tagPage() {
			$_tag = get_the_tags();
			$this->_data['title']       = $_tag[0]->name;
			$this->_data['description'] = $_tag[0]->description;
		}

		/**
		 * Post Page
		 */
		private function _postPage() {
			$_post          = get_post();
			$_thumbnail_src = '';
			// read thumbnails
			if ( get_post_thumbnail_id( $_post->ID ) != '' ) {
				$_thumbnail     = wp_get_attachment_image_src( get_post_thumbnail_id( $_post->ID ) );
				$_thumbnail_src = $_thumbnail[0];
			}
			$_post_content = $_post->post_content;
			// check readmore - intro info from content
			$_post_content_intro = substr( $_post_content, 0, strpos( $_post_content, '<!--more-->' ) );
			if ( $_post_content_intro == '' ) {
				$_post_content_intro = strip_tags( $_post_content );
			}
			$_tmp                       = explode( '<br/>', wordwrap( $_post_content_intro, 150, '<br/>' ) );
			$_post_content_intro        = str_replace( PHP_EOL, ' ', $_tmp[0] );
			$this->_data['title']       = $_post->post_title;
			$this->_data['description'] = $_post_content_intro;
			if ( $_thumbnail_src != '' ) {
				$this->_data['image'] = $_thumbnail_src;
			}

		}

		/**
		 * Main Hook
		 */
		function hook_meta() {
			if ( is_front_page() ) {
				$this->_frontPage();
			} elseif ( is_category() ) {
				$this->_categoryPage();
			} elseif ( is_tag() ) {
				$this->_tagPage();
			} else {
				$this->_postPage();
			}
			// add header
			echo '<meta property="og:url" content="' . get_permalink() . '"/>' . PHP_EOL;
			echo '<meta property="og:title" content="' . $this->_data['title'] . '"/>' . PHP_EOL;
			echo '<meta property="og:description" content="' . $this->_data['description'] . '"/>' . PHP_EOL;
			echo '<meta property="og:image" content="' . $this->_data['image'] . '"/>' . PHP_EOL;
			if ( $this->_data['appid'] != '' ) {
				echo '<meta property="fb:app_id" content="' . $this->_data['appid'] . '"/>' . PHP_EOL;
			}
			if ( $this->_data['pageid'] != '' ) {
				echo '<meta property="fb:pages" content="' . $this->_data['pageid'] . '"/>' . PHP_EOL;
			}
		}

	}
}

if ( class_exists( "FingerSimpleFacbookHeader" ) ) {
	// inicialize Class
	$FingerSimpleFacbookHeaderPlugin = new FingerSimpleFacbookHeader();
	add_action( 'wp_head', array( &$FingerSimpleFacbookHeaderPlugin, 'hook_meta' ), 100 );
}
