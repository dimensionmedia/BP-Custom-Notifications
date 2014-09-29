<?php

class BP_Custom_Notifications_Comment_Component extends BP_Component {
	/**
	 * Initial component setup.
	 */
	
	public function __construct() {
		parent::start(
			// Unique component ID
			'wctpa_notifications_comment',

			// Used by BP when listing components (eg in the Dashboard)
			__( 'Enable Custom Post Comment Notifications', 'wctpa_notifications_comment' )
		);
	}

	/**
	 * Set up component data, as required by BP.
	 */
	public function setup_globals( $args = array() ) {

		global $bp;

		$wctpa_notifications_comments_slug = 'wctpa_notifications_comment';

		parent::setup_globals( array(
			'id' => 'wctpa_notifications_comment',
			'slug' => $wctpa_notifications_comments_slug, // used for building URLs
			'notification_callback' => 'wctpa_format_notifications_comments'
		) );

		/* Register this in the active components array */
		$bp->active_components[$wctpa_notifications_comments_slug] = 'wctpa_notifications_comment';

	}

	/**
	 * Set up component navigation, and register display callbacks.
	 */
	public function setup_nav( $main_nav = array(), $sub_nav = array() ) {
		return false;
	}

	/**
	 * Set up display screen logic.
	 *
	 * We are using BP's plugins.php template as a wrapper, which is
	 * the easiest technique for compatibility with themes.
	 */
	public function screen_function() {
		return false;
	}


}

/**
 * Bootstrap the component.
 */
function wctpa_init() {
	buddypress()->wctpa_notifications_comment = new BP_Custom_Notifications_Comment_Component();
}
add_action( 'bp_loaded', 'wctpa_init' );





/**
 * Format the BuddyBar/Toolbar notifications for the custom component
 *
 * @since BuddyPress (1.0)
 * @param string $action The kind of notification being rendered
 * @param int $item_id The primary item id
 * @param int $secondary_item_id The secondary item id
 * @param int $total_items The total number of custom-related notifications waiting for the user
 * @param string $format 'string' for BuddyBar-compatible notifications; 'array' for WP Toolbar
 */
function wctpa_format_notifications_comments( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {

	if ( 'new_comment' === $action ) {
		$link  = get_permalink( $item_id );
		$title = __( 'New Comment On '.get_the_title( $item_id ), 'buddypress' );

		if ( (int) $total_items > 1 ) {
			$text   = sprintf( __('You have %d new comments', 'buddypress' ), (int) $total_items );
			$filter = 'wctpa_bp_notifications_multiple_new_comment_notification';
		} else {
			if ( !empty( $secondary_item_id ) ) {
				$text = sprintf( __('You have %d new comment on %s', 'buddypress' ), (int) $total_items, get_the_title( $item_id ) );
			} else {
				$text = sprintf( __('You have %d new comment',       'buddypress' ), (int) $total_items );
			}
			$filter = 'wctpa_bp_notifications_single_new_comment_notification';
		}
	}

	if ( 'string' === $format ) {
		$return = apply_filters( $filter, '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $link, $item_id, $secondary_item_id );
	} else {
		$return = apply_filters( $filter, array(
			'text' => $text,
			'link' => $link
		), $link, (int) $total_items, $text, $link, $item_id, $secondary_item_id );
	}

	do_action( 'wctpa_format_notifications_comments', $action, $item_id, $secondary_item_id, $total_items );

	return $return;
}