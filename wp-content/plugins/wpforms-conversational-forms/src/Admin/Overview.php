<?php

namespace WPFormsConversationalForms\Admin;

/**
 * Conversational Forms overview functionality.
 *
 * @since 1.0.0
 */
class Overview {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		add_filter( 'wpforms_overview_row_actions', [ $this, 'add_row_view_action' ], 10, 2 );
		add_action( 'wpforms_form_handler_duplicate_form', [ $this, 'add_to_location_meta_duplicate' ], 10, 3 );
	}

	/**
	 * Add form page to location meta on duplicate.
	 *
	 * @since 1.14.0
	 *
	 * @param int|mixed $id            The original Form ID.
	 * @param int|mixed $new_form_id   The new/duplicated Form ID.
	 * @param array     $new_form_data New form data.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function add_to_location_meta_duplicate( $id, $new_form_id, array $new_form_data ) {

		$form_obj = wpforms()->obj( 'form' );

		if ( ! $form_obj ) {
			return;
		}

		// Get duplicated form settings.
		$duplicated_form_data = $form_obj->get( $new_form_id, [ 'content_only' => true ] );

		if ( wpforms_is_form_data_slashing_enabled() ) {
			$duplicated_form_data = wp_slash( $duplicated_form_data );
		}

		// Generate an unique slug.
		$slug        = $duplicated_form_data['settings']['conversational_forms_page_slug'] ?? get_post_field( 'post_name', $id );
		$unique_slug = wp_unique_post_slug( $slug, $id, 'publish', 'wpforms', 0 );

		// Add the unique slug to the duplicated form settings.
		$duplicated_form_data['settings']['conversational_forms_page_slug'] = $unique_slug;

		// Update duplicated form settings.
		$form_obj->update( $new_form_id, $duplicated_form_data );

		$locator = wpforms()->obj( 'locator' );

		if ( ! $locator ) {
			return;
		}

		// Add the duplicated form to the locations' meta.
		$locator->add_standalone_location_to_locations_meta( $new_form_id, $new_form_data );
	}

	/**
	 * Add view row action if Conversational mode is activated.
	 *
	 * @since 1.0.0
	 *
	 * @param array    $row_actions Table row actions.
	 * @param \WP_Post $form        Form object.
	 *
	 * @return array
	 */
	public function add_row_view_action( $row_actions, $form ) {

		$form_data = ! empty( $form->post_content ) ? wpforms_decode( $form->post_content ) : [];

		// Form Templates are allowed to be previewed only by users with a capability to view forms.
		$not_allowed = $form->post_type === 'wpforms-template' && ! wpforms_current_user_can( 'view_forms' );

		// Do not show the preview link for trashed forms or form templates.
		$in_trash = $form->post_status === 'trash';

		if ( empty( $form_data['settings']['conversational_forms_enable'] ) || $not_allowed || $in_trash ) {
			return $row_actions;
		}

		$action = [
			'view' => sprintf(
				'<a href="%s" title="%s" target="_blank">%s</a>',
				esc_url( home_url( $form->post_name ) ),
				esc_html__( 'View Conversational Form', 'wpforms-conversational-forms' ),
				esc_html__( 'Conversational Form Preview', 'wpforms-conversational-forms' )
			),
		];

		return wpforms_array_insert( $row_actions, $action, 'preview_' );
	}
}
