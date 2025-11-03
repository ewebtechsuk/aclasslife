import conditionalLogic from './conditional-logic';
import priceCalculator from './price-calculator';
import fieldValidation from './field-validation';
import attributeOptions from './attribute-options';

import fileUpload from './fields/file-upload';
import imageButtons from './fields/image-buttons';
import dropdown from './fields/dropdown';
import customCheckboxes from './fields/custom-checkboxes';
import datePicker from './fields/date-picker';
import imageSwitcher from './fields/image-switcher';

// import ECESupport from './integration/ece-support';
import wbvPriceCalculator from './integration/wbv-price-calculator';

( function ( $ ) {
	/**
	 * Default
	 */
	document.addEventListener( 'DOMContentLoaded', function () {
		initModules();
	} );

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
				priceCalculator( cartForm ).init();
				attributeOptions( cartForm ).init();
			}

			// ECESupport( $, cartForm ).init();
		} );

		dropdown.init();
		customCheckboxes.init();
		datePicker.init();
		imageSwitcher.init();
	}
} )( jQuery );
