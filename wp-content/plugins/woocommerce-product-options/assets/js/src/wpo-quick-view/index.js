import conditionalLogic from '../wpo-single-product/conditional-logic';
import priceCalculator from '../wpo-single-product/price-calculator';
import fieldValidation from '../wpo-single-product/field-validation';
import attributeOptions from '../wpo-single-product/attribute-options';

import fileUpload from '../wpo-single-product/fields/file-upload';
import imageButtons from '../wpo-single-product/fields/image-buttons';
import dropdown from '../wpo-single-product/fields/dropdown';
import customCheckboxes from '../wpo-single-product/fields/custom-checkboxes';
import datePicker from '../wpo-single-product/fields/date-picker';
import imageSwitcher from '../wpo-single-product/fields/image-switcher';

import ECESupport from '../wpo-single-product/integration/ece-support';

import wbvPriceCalculator from '../wpo-single-product/integration/wbv-price-calculator';

( function ( $ ) {
	/**
	 * WooCommerce Quick View Pro
	 */
	$( document.body ).on( 'quick_view_pro:open', () => initModules() );

	/**
	 * General init helper.
	 */
	function initModules() {
		const cartForms = document.querySelectorAll( 'form.cart' );

		cartForms.forEach( ( cartForm ) => {
			conditionalLogic( cartForm ).init();
			fieldValidation( cartForm ).init();
			fileUpload( cartForm ).init();
			imageButtons( cartForm ).init();

			// check if this is a WBV cart form
			if ( cartForm.classList.contains( 'wcbvp-cart' ) ) {
				wbvPriceCalculator( cartForm ).init();
			} else {
				attributeOptions( cartForm ).init();
				priceCalculator( cartForm ).init();
			}

			ECESupport( $, cartForm ).init();
		} );

		dropdown.init();
		customCheckboxes.init();
		datePicker.init();
		imageSwitcher.init();
	}
} )( jQuery );
