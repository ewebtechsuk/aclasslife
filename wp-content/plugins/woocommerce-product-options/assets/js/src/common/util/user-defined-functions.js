export const lCaseFunctionNames = ( functions ) => {
	return Object.fromEntries( Object.entries( functions ).map( ( [ key, value ] ) => {
		return [ key.toLowerCase(), value ];
	} ) );
};

export const getLogicalFunctions = () => {
	return {
		// eslint-disable-next-line no-unused-vars
		compare: ( a, b, operator = '>' ) => {
			switch ( operator ) {
				case '=':
					return a === b ? 1 : 0;
				case '!=':
					return a !== b ? 1 : 0;
				case '<':
					return a < b ? 1 : 0;
				case '>=':
					return a >= b ? 1 : 0;
				case '<=':
					return a <= b ? 1 : 0;
				case '>':
				default:
					return a > b ? 1 : 0;
			}
		},
		if: ( condition, trueValue, falseValue ) => {
			return condition ? trueValue : falseValue;
		},
		and: ( ...args ) => {
			return args.every( ( arg ) => arg );
		},
		or: ( ...args ) => {
			return args.some( ( arg ) => arg );
		},
		not: ( arg ) => {
			return ! arg;
		},
		eq: ( a, b ) => {
			return a === b;
		},
		neq: ( a, b ) => {
			return a !== b;
		},
		lt: ( a, b ) => {
			return a < b;
		},
		lte: ( a, b ) => {
			return a <= b;
		},
		gt: ( a, b ) => {
			return a > b;
		},
		gte: ( a, b ) => {
			return a >= b;
		},
	};
};

export const getCustomFunctions = () => {
	return {
		bulkPrice: ( price, quantity, ...prices ) => {
			if ( prices.length % 2 !== 0 ) {
				throw new Error(
					__(
						'bulkPrice() requires an even number of arguments after the product price and quantity',
						'woocommerce-product-options'
					)
				);
			}

			const priceArray = [];

			for ( let i = 0; i < prices.length; i += 2 ) {
				priceArray.push( {
					quantity: prices[ i ],
					price: prices[ i + 1 ],
				} );
			}

			priceArray.sort( ( a, b ) => b.quantity - a.quantity );

			for ( let i = 0; i < priceArray.length; i++ ) {
				if ( quantity >= priceArray[ i ].quantity ) {
					return priceArray[ i ].price;
				}
			}

			return price;
		},
		bulkRate: ( price, quantity, ...rates ) => {
			if ( rates.length % 2 !== 0 ) {
				throw new Error(
					__(
						'bulkRate() requires an even number of arguments after the product price and quantity',
						'woocommerce-product-options'
					)
				);
			}

			const rateArray = [];

			for ( let i = 0; i < rates.length; i += 2 ) {
				rateArray.push( {
					quantity: rates[ i ],
					rate: rates[ i + 1 ],
				} );
			}

			rateArray.sort( ( a, b ) => b.quantity - a.quantity );

			for ( let i = 0; i < rateArray.length; i++ ) {
				if ( quantity >= rateArray[ i ].quantity ) {
					return price * rateArray[ i ].rate;
				}
			}

			return price;
		},
		year: ( date = null ) => {
			date = date || new Date();
			return new Date( date ).getFullYear();
		},
		month: ( date = null ) => {
			date = date || new Date();
			return new Date( date ).getMonth() + 1;
		},
		day: ( date = null ) => {
			date = date || new Date();
			return new Date( date ).getDate();
		},
		weekday: ( date = null ) => {
			date = date || new Date();
			return ( 7 + new Date( date ).getDay() - ( window?.wpoSettings?.start_of_week || 0 ) ) % 7 + 1;
		},
		...( window?.wpoCustomFunctions ?? {} ),
	};
};

export const getCustomPriceTypes = () => {
	return {
		...window?.wpoCustomPriceTypes ?? {},
	};
}
