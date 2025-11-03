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

( function ( $ ) {
	/**
	 * WooCommerce Restauarant Ordering (Custom init)
	 */
	$( document.body ).on( 'wro:modal:open', () => {
		const cartForms = document.querySelectorAll( 'form.cart' );

		cartForms.forEach( ( cartForm ) => {
			const addtoCartButton = cartForm.querySelector( 'button#add-product' );

			conditionalLogic( cartForm ).init();
			priceCalculator( cartForm, 'wro' ).init();
			attributeOptions( cartForm ).init();

			fileUpload( cartForm, addtoCartButton ).init();
			fieldValidation( cartForm, addtoCartButton ).init();
			imageButtons( cartForm ).init();

			ECESupport( $, cartForm ).init();
		} );

		dropdown.init();
		customCheckboxes.init();
		datePicker.init();
		imageSwitcher.init();
	} );
} )( jQuery );
