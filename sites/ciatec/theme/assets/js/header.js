/**
 * CIATec header: mobile menu, language flag dropdown and light/dark switch.
 * Vanilla JS. Without it the menu stays visible and the theme follows the system setting.
 */
( function () {
	'use strict';

	var BREAKPOINT = 900;
	var header = document.getElementById( 'site-header' );

	if ( ! header ) {
		return;
	}

	/* Theme switch ------------------------------------------------------- */
	var themeToggle = header.querySelector( '.ciatec-theme-toggle' );

	if ( themeToggle ) {
		var root = document.documentElement;

		var syncTheme = function () {
			themeToggle.setAttribute( 'aria-pressed', root.getAttribute( 'data-theme' ) === 'dark' ? 'true' : 'false' );
		};

		themeToggle.addEventListener( 'click', function () {
			var next = root.getAttribute( 'data-theme' ) === 'dark' ? 'light' : 'dark';
			root.setAttribute( 'data-theme', next );
			try {
				localStorage.setItem( 'ciatec-theme', next );
			} catch ( e ) {}
			syncTheme();
		} );
		syncTheme();
	}

	/* Language dropdown -------------------------------------------------- */
	var lang = header.querySelector( '[data-ciatec-lang]' );

	if ( lang ) {
		var trigger = lang.querySelector( '.ciatec-lang__trigger' );
		var menu = lang.querySelector( '.ciatec-lang__menu' );

		var setLangOpen = function ( isOpen ) {
			trigger.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			menu.hidden = ! isOpen;
		};

		trigger.addEventListener( 'click', function ( event ) {
			event.stopPropagation();
			setLangOpen( trigger.getAttribute( 'aria-expanded' ) !== 'true' );
		} );
		document.addEventListener( 'click', function ( event ) {
			if ( ! lang.contains( event.target ) ) {
				setLangOpen( false );
			}
		} );
		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && trigger.getAttribute( 'aria-expanded' ) === 'true' ) {
				setLangOpen( false );
				trigger.focus();
			}
		} );
	}

	/* Mobile menu -------------------------------------------------------- */
	var toggle = header.querySelector( '.ciatec-header__toggle' );
	var panel = document.getElementById( 'ciatec-nav' );

	if ( ! toggle || ! panel ) {
		return;
	}

	var mq = window.matchMedia( '(max-width: ' + BREAKPOINT + 'px)' );

	function setOpen( isOpen ) {
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		header.classList.toggle( 'is-open', isOpen );
		panel.hidden = mq.matches && ! isOpen;
	}

	function sync() {
		toggle.hidden = ! mq.matches;
		setOpen( false );
	}

	toggle.addEventListener( 'click', function () {
		setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
	} );

	// Close after choosing an anchor.
	panel.addEventListener( 'click', function ( event ) {
		if ( mq.matches && event.target.closest( '.ciatec-nav a' ) ) {
			setOpen( false );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	mq.addEventListener( 'change', sync );
	sync();
} )();
