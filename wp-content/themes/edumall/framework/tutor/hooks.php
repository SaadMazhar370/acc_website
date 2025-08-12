<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Hooks' ) ) {
	class Edumall_Hooks {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {

		}
	}
}
