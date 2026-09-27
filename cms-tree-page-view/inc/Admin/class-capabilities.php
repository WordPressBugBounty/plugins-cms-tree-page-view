<?php
/**
 * Role capabilities for the tree-move permission.
 *
 * @package cms-tree-page-view
 */

namespace CMS_Tree_Page_View\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The retired tree-move capability (CMS_TPV_MOVE_PERMISSION).
 *
 * It was meant to gate reordering, but nothing has checked it for years:
 * reordering is gated by edit_post (REST\Mutation_Controller), and on new
 * installs the capability was never even granted (todo 84). So it is no
 * longer granted, and remove() takes it off every role on upgrade and on
 * uninstall, including roles a site owner once gave it to by hand.
 */
class Capabilities {

	/**
	 * Used to grant the tree-move capability. Kept as a no-op: the old
	 * cms_tvp_setup_caps() shim pointed callers here from 2.0.0 to 2.5.2.
	 *
	 * @deprecated 2.6.0 The capability is retired; no replacement.
	 */
	public static function setup() {
		_deprecated_function( __METHOD__, '2.6.0' );
	}

	/**
	 * Remove the retired tree-move capability from every role that has it.
	 */
	public static function remove() {
		foreach ( array_keys( wp_roles()->roles ) as $role ) {
			self::remove_caps_from_role( $role, array( CMS_TPV_MOVE_PERMISSION ) );
		}
	}

	/**
	 * Add an array of capabilities to a role.
	 *
	 * @param string   $role Role name to add the capabilities to.
	 * @param string[] $caps Capabilities to add.
	 */
	public static function add_caps_to_role( $role, $caps ) {
		global $wp_roles;

		if ( $wp_roles->is_role( $role ) ) {
			$role = get_role( $role );
			foreach ( $caps as $cap ) {
				$role->add_cap( $cap );
			}
		}
	}

	/**
	 * Remove an array of capabilities from a role.
	 *
	 * @param string   $role Role name to remove the capabilities from.
	 * @param string[] $caps Capabilities to remove.
	 */
	public static function remove_caps_from_role( $role, $caps ) {
		global $wp_roles;

		if ( $wp_roles->is_role( $role ) ) {
			$role = get_role( $role );
			foreach ( $caps as $cap ) {
				$role->remove_cap( $cap );
			}
		}
	}
}
