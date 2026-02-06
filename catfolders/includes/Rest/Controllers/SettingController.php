<?php
namespace CatFolders\Rest\Controllers;

use CatFolders\Models\OptionModel;

class SettingController {
	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/global-setting',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/other-settings/dismiss-banner-sidebar',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'dismiss_banner_sidebar' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);
	}

	public function update( \WP_REST_Request $request ) {
		if( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( 403, __( 'You are not allowed to update settings.', 'catfolders' ) );
		}
		$name  = sanitize_key( $request->get_param( 'name' ) );
		$value = sanitize_key( $request->get_param( 'value' ) );
		OptionModel::update_option( array( $name => $value ) );
		return new \WP_REST_Response( array( 'success' => true ) );
	}

	public function permission_callback() {
		return current_user_can( 'upload_files' );
	}

	public function dismiss_banner_sidebar( \WP_REST_Request $request ) {
		$res = update_option( 'catf_banner_sidebar_dismissed', 'yes' );
		return new \WP_REST_Response( array( 'success' => $res ) );
	}
}
