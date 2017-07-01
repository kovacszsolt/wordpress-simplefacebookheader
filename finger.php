<?php

/**
 * Class Finger
 * Utils
 */
class Finger {
	/**
	 * Check key in array
	 *
	 * @param array $array
	 * @param string $key
	 * @param string $default
	 *
	 * @return string
	 */
	static function checkArrayItem( $array, $key, $default = '' ) {
		$_return = $default;
		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) ) {
				$_return = $array[ $key ];
			}
		}

		return $_return;
	}
}