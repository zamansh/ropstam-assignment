/**
 * Live add-on price calculator (vanilla JS, no dependencies).
 *
 * Design notes:
 * - ONE delegated "change" listener for the whole page, not one per checkbox.
 * - Money is summed in integer cents to avoid floating point drift.
 * - Only the total's text node is touched, and only when the text changed.
 * - Server already renders the correct base total, so with JS disabled the
 *   page still shows a valid price; JS only enhances it.
 */
( function () {
	'use strict';

	var CARD = '.cpa-card';
	var SWITCH = '.cpa-switch__input';
	var TOTAL = '.cpa-total__value';

	/**
	 * Build a currency formatter. Falls back to a plain "$0.00" string if
	 * Intl is missing or throws (very old browsers, locked-down environments).
	 */
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

	/** "50" -> 5000. Anything invalid counts as 0 so one bad value cannot break the total. */
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

	/** Recalculate every card, e.g. when the browser restores checked boxes. */
	function syncAll() {
		var cards = document.querySelectorAll( CARD );
		for ( var i = 0; i < cards.length; i++ ) {
			updateCard( cards[ i ] );
		}
	}

	document.addEventListener( 'change', onChange );

	// Script is deferred, so the DOM is ready; sync once, and again on back/forward cache restore.
	syncAll();
	window.addEventListener( 'pageshow', syncAll );
} )();
