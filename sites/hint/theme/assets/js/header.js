/**
 * HINT Header — mobile menu and language dropdown.
 * Vanilla JS, no dependencies.
 */
( function () {
	'use strict';

	var BREAKPOINT = 900; // Smallest breakpoint in header.css; larger text sizes switch earlier.

	function initMobileMenu() {
		var toggle = document.querySelector( '.hint-header__toggle' );

		if ( ! toggle ) {
			return;
		}

		var panel = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

		if ( ! panel ) {
			return;
		}

		function setOpen( isOpen ) {
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

			if ( isOpen ) {
				panel.removeAttribute( 'hidden' );
			} else {
				panel.setAttribute( 'hidden', '' );
			}
		}

		toggle.addEventListener( 'click', function () {
			setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				setOpen( false );
				toggle.focus();
			}
		} );

		// The toggle is shown by CSS breakpoints that depend on the text-size
		// level (header.css), so "desktop" means the toggle is not displayed.
		window.addEventListener( 'resize', function () {
			var isDesktop = window.innerWidth > BREAKPOINT && 'none' === window.getComputedStyle( toggle ).display;

			if ( isDesktop && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				setOpen( false );
			}
		} );
	}

	function initLanguageSwitchers() {
		var switchers = [].slice.call( document.querySelectorAll( '[data-hint-lang]' ) );

		if ( ! switchers.length ) {
			return;
		}

		var controllers = [];

		switchers.forEach( function ( switcher ) {
			var trigger = switcher.querySelector( '.hint-lang__trigger' );
			var menu    = switcher.querySelector( '.hint-lang__menu' );

			if ( ! trigger || ! menu ) {
				return;
			}

			function setOpen( isOpen ) {
				trigger.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

				if ( isOpen ) {
					menu.removeAttribute( 'hidden' );
				} else {
					menu.setAttribute( 'hidden', '' );
				}
			}

			trigger.addEventListener( 'click', function ( event ) {
				event.stopPropagation();
				var willOpen = trigger.getAttribute( 'aria-expanded' ) !== 'true';

				closeAll();
				setOpen( willOpen );
			} );

			controllers.push( {
				element: switcher,
				trigger: trigger,
				setOpen: setOpen,
			} );
		} );

		function closeAll() {
			controllers.forEach( function ( controller ) {
				controller.setOpen( false );
			} );
		}

		// Capture phase, so a click on another header widget (which may stop
		// propagation) still closes the language menu.
		document.addEventListener( 'click', function ( event ) {
			controllers.forEach( function ( controller ) {
				if ( ! controller.element.contains( event.target ) ) {
					controller.setOpen( false );
				}
			} );
		}, true );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' !== event.key ) {
				return;
			}

			controllers.forEach( function ( controller ) {
				if ( controller.trigger.getAttribute( 'aria-expanded' ) === 'true' ) {
					controller.setOpen( false );
					controller.trigger.focus();
				}
			} );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initMobileMenu();
		initLanguageSwitchers();
	} );
}() );
