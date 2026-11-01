<?php
namespace CatFolders\Classes;

defined( 'ABSPATH' ) || exit;

class Helpers {
	protected static $instance = null;

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function isListMode() {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			return ( isset( $screen->id ) && ( ( 'upload' == $screen->id ) || ( 'media_page_mla-menu' == $screen->id ) ) );
		}
		return false;
	}

	public static function getMediaMode() {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();

			if ( isset( $screen->id ) && ( 'upload' == $screen->id ) ) {
				return get_user_option( 'media_library_mode', get_current_user_id() );
			}
		}

		return 'list';
	}

	public static function sanitizeSortFile( $value ) {
		$columns    = array( 'name', 'date', 'modified', 'author', 'title' );
		$directions = array( 'ASC', 'DESC' );

		if ( ! is_string( $value ) ) {
			return '';
		}

		$parts = explode( '-', $value );
		if ( 2 !== count( $parts ) || ! in_array( $parts[0], $columns, true ) || ! in_array( $parts[1], $directions, true ) ) {
			return '';
		}

		return $value;
	}

	public static function sanitizeSortFolder( $value ) {
		return in_array( $value, array( 'asc', 'desc' ), true ) ? $value : '';
	}

	public static function AutoOrderInListMode( $order ) {
		global $wpdb;

		// Validate again on read: older versions stored this value unchecked in user meta.
		$order = self::sanitizeSortFile( $order );
		if ( '' === $order ) {
			return '';
		}

		$order = explode( '-', $order );

		return "{$wpdb->posts}.post_{$order[0]} {$order[1]}";
	}

	public static function sanitize_array( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'self::sanitize_array', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

	public static function sanitize_intval_array( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'intval', $var );
		} else {
			return intval( $var );
		}
	}

	public static function get_bytes( $post_id ) {
		$bytes = '';
		$meta  = wp_get_attachment_metadata( $post_id );
		if ( isset( $meta['filesize'] ) ) {
			$bytes = $meta['filesize'];
		} else {
			$attached_file = get_attached_file( $post_id );
			if ( file_exists( $attached_file ) ) {
				$bytes = \wp_filesize( $attached_file );
			}
		}
		return $bytes;
	}

	public static function generateRandomString( $length = 10 ) {
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen( $characters );
		$randomString     = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$randomString .= $characters[ wp_rand( 0, $charactersLength - 1 ) ];
		}
		return $randomString;
	}
}
