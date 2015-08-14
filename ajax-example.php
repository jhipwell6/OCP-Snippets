<?php 

/*
 * Update Project Designs
 * @template = single-project.php
 */
function ocp_ajax_update_designs() {
    if ( isset($_REQUEST) ) {		
		$on_deck = $_REQUEST['on-deck'];
		$in_progress = $_REQUEST['in-progress'];
		$completed = $_REQUEST['completed'];
		$post_id = $_REQUEST['id'];

		//add on-deck items field_55346df0f3317
		if($on_deck) {
			$ondeck_list = join(',', $on_deck);
			update_field( 'field_55346df0f3317', $ondeck_list, $post_id );
		} else {
			update_field( 'field_55346df0f3317', '', $post_id );
		}
		
		//add in-progress items field_55346e01f3318
		if($in_progress) {
			$inprogress_list = join(',', $in_progress);
			update_field( 'field_55346e01f3318', $inprogress_list, $post_id );
		} else {
			update_field( 'field_55346e01f3318', '', $post_id );
		}
		
		//add completed items field_55346e0cf3319
		if($completed) {
			$completed_list = join(',', $completed);
			update_field( 'field_55346e0cf3319', $completed_list, $post_id );
		} else {
			update_field( 'field_55346e0cf3319', '', $post_id );
		}
	}
    die();
}
add_action( 'wp_ajax_update_designs', 'ocp_ajax_update_designs' );
add_action( 'wp_ajax_nopriv_update_designs', 'ocp_ajax_update_designs' );