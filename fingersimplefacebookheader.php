<?php
/*
Plugin Name: Finger Simple Facebook Header extension
Description: Change HTML header add OG TAG
Version: 1.0
Author: Kovács Zsolt
Author URI: http://www.itcrowd.hu
*/

// include the Util class
include_once 'finger.php';

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}
if ( is_admin() ) {
	// admin Page
	include_once 'admin.fingersimplefacebookheader.php';

} else {
	// frontend
	include_once 'class.fingersimplefacebookheader.php';
}
