/**
 * Live add-on price calculator (vanilla JS, no dependencies).
 */
( function () {
	'use strict';

	var CARD = '.cpa-card';
	var SWITCH = '.cpa-switch__input';
	var TOTAL = '.cpa-total__value';

	function createFormatter() {
		try {
			var intl = new Intl.NumberFormat( 'en-US', { style: 'currency', currency: 'USD' } );
			return function ( amount ) {
				return intl.format( amount );
			};
		} catch ( e ) {
			return function ( amount ) {
				return '$' + amount.toFixed( 2 );
			};
		}
	}

	var format = createFormatter();

	function toCents( value ) {
		var number = parseFloat( value );
		return isFinite( number ) && number > 0 ? Math.round( number * 100 ) : 0;
	}

	function updateCard( card ) {
		if ( card.getAttribute( 'data-available' ) !== '1' ) {
			return;
		}

		var cents = toCents( card.getAttribute( 'data-base-price' ) );
		var active = card.querySelectorAll( SWITCH + ':checked' );

		for ( var i = 0; i < active.length; i++ ) {
			cents += toCents( active[ i ].getAttribute( 'data-addon-price' ) );
		}

		var output = card.querySelector( TOTAL );
		var text = format( cents / 100 );

		if ( output && output.textContent !== text ) {
			output.textContent = text;
		}
	}

	function onChange( event ) {
		var target = event.target;

		if ( ! target || ! target.matches || ! target.matches( SWITCH ) ) {
			return;
		}

		var card = target.closest( CARD );
		if ( card ) {
			updateCard( card );
		}
	}

	
	function syncAll() {
		var cards = document.querySelectorAll( CARD );
		for ( var i = 0; i < cards.length; i++ ) {
			updateCard( cards[ i ] );
		}
	}

	document.addEventListener( 'change', onChange );

	
	syncAll();
	window.addEventListener( 'pageshow', syncAll );
} )();
