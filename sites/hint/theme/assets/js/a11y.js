/**
 * HINT Accessibility — appearance / text-size panel and hero video control.
 * Vanilla JS, no dependencies.
 *
 * State lives on <html> as data-theme="light|dark|contrast" and
 * data-text="1|2|3". A saved choice is kept in localStorage under
 * `hint-a11y` (the inline script printed by functions.php applies it before
 * first paint). Without a saved choice the site follows the system:
 * prefers-contrast: more → contrast, prefers-color-scheme: dark → dark.
 */
( function () {
	'use strict';

	var STORAGE_KEY = 'hint-a11y';
	var THEMES      = [ 'light', 'dark', 'contrast' ];
	var TEXT_SIZES  = [ '1', '2', '3' ];
	var FOCUSABLE   = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

	var root       = document.documentElement;
	var mqDark     = matchMediaSafe( '(prefers-color-scheme: dark)' );
	var mqContrast = matchMediaSafe( '(prefers-contrast: more)' );
	var mqMotion   = matchMediaSafe( '(prefers-reduced-motion: reduce)' );

	function matchMediaSafe( query ) {
		if ( window.matchMedia ) {
			return window.matchMedia( query );
		}

		return { matches: false, addEventListener: function () {}, addListener: function () {} };
	}

	function onMediaChange( mq, handler ) {
		if ( mq.addEventListener ) {
			mq.addEventListener( 'change', handler );
		} else if ( mq.addListener ) {
			mq.addListener( handler );
		}
	}

	/* ——— Preference store ——— */

	function readPreference() {
		var saved = {};

		try {
			saved = JSON.parse( window.localStorage.getItem( STORAGE_KEY ) || '{}' ) || {};
		} catch ( error ) {
			saved = {};
		}

		return {
			theme: THEMES.indexOf( saved.theme ) !== -1 ? saved.theme : '',
			text: TEXT_SIZES.indexOf( saved.text ) !== -1 ? saved.text : '',
		};
	}

	function writePreference( preference ) {
		try {
			if ( ! preference.theme && ! preference.text ) {
				window.localStorage.removeItem( STORAGE_KEY );
			} else {
				window.localStorage.setItem( STORAGE_KEY, JSON.stringify( preference ) );
			}
		} catch ( error ) {
			// Private mode or storage blocked: the choice lasts for this page only.
		}
	}

	function systemTheme() {
		if ( mqContrast.matches ) {
			return 'contrast';
		}

		return mqDark.matches ? 'dark' : 'light';
	}

	/* ——— Apply to <html> ——— */

	function apply() {
		var preference = readPreference();
		var theme      = preference.theme || systemTheme();
		var text       = preference.text || '1';

		root.setAttribute( 'data-theme', theme );
		root.setAttribute( 'data-text', text );

		document.dispatchEvent( new CustomEvent( 'hint:a11y', { detail: { theme: theme, text: text } } ) );
	}

	function setChoice( key, value ) {
		var preference = readPreference();

		preference[ key ] = value;
		writePreference( preference );
		apply();
	}

	function resetChoices() {
		writePreference( { theme: '', text: '' } );
		apply();
	}

	/* ——— Panels (one per header instance: desktop and mobile) ——— */

	function initPanels() {
		var panels = [].slice.call( document.querySelectorAll( '[data-hint-a11y]' ) );

		if ( ! panels.length ) {
			return;
		}

		var controllers = [];

		panels.forEach( function ( container ) {
			var trigger = container.querySelector( '.hint-a11y__trigger' );
			var panel   = container.querySelector( '.hint-a11y__panel' );

			if ( ! trigger || ! panel ) {
				return;
			}

			var options = [].slice.call( panel.querySelectorAll( '[data-a11y-theme], [data-a11y-text]' ) );
			var reset   = panel.querySelector( '[data-a11y-reset]' );

			function isOpen() {
				return trigger.getAttribute( 'aria-expanded' ) === 'true';
			}

			function setOpen( open, restoreFocus ) {
				trigger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

				if ( open ) {
					panel.removeAttribute( 'hidden' );
					refresh();

					var first = panel.querySelector( '[aria-pressed="true"]' ) || panel.querySelector( FOCUSABLE );
					if ( first ) {
						first.focus();
					}
				} else {
					panel.setAttribute( 'hidden', '' );

					if ( restoreFocus ) {
						trigger.focus();
					}
				}
			}

			function refresh() {
				var theme = root.getAttribute( 'data-theme' ) || systemTheme();
				var text  = root.getAttribute( 'data-text' ) || '1';

				options.forEach( function ( option ) {
					var pressed = option.hasAttribute( 'data-a11y-theme' )
						? option.getAttribute( 'data-a11y-theme' ) === theme
						: option.getAttribute( 'data-a11y-text' ) === text;

					option.setAttribute( 'aria-pressed', pressed ? 'true' : 'false' );
				} );
			}

			trigger.addEventListener( 'click', function ( event ) {
				event.stopPropagation();
				var willOpen = ! isOpen();

				controllers.forEach( function ( controller ) {
					if ( controller.container !== container ) {
						controller.setOpen( false, false );
					}
				} );

				setOpen( willOpen, ! willOpen );
			} );

			options.forEach( function ( option ) {
				option.addEventListener( 'click', function () {
					if ( option.hasAttribute( 'data-a11y-theme' ) ) {
						setChoice( 'theme', option.getAttribute( 'data-a11y-theme' ) );
					} else {
						setChoice( 'text', option.getAttribute( 'data-a11y-text' ) );
					}
				} );
			} );

			if ( reset ) {
				reset.addEventListener( 'click', function () {
					resetChoices();
				} );
			}

			// Disclosure, not a modal: Esc closes and returns focus to the
			// trigger; tabbing out of the panel simply closes it.
			panel.addEventListener( 'keydown', function ( event ) {
				if ( 'Escape' === event.key ) {
					event.preventDefault();
					event.stopPropagation();
					setOpen( false, true );
				}
			} );

			container.addEventListener( 'focusout', function ( event ) {
				// relatedTarget is null when the window loses focus: keep the panel.
				if ( isOpen() && event.relatedTarget && ! container.contains( event.relatedTarget ) ) {
					setOpen( false, false );
				}
			} );

			controllers.push( {
				container: container,
				trigger: trigger,
				isOpen: isOpen,
				setOpen: setOpen,
				refresh: refresh,
			} );
		} );

		// Capture phase: other header widgets stop propagation on their clicks.
		document.addEventListener( 'click', function ( event ) {
			controllers.forEach( function ( controller ) {
				if ( controller.isOpen() && ! controller.container.contains( event.target ) ) {
					controller.setOpen( false, false );
				}
			} );
		}, true );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' !== event.key ) {
				return;
			}

			controllers.forEach( function ( controller ) {
				if ( controller.isOpen() ) {
					controller.setOpen( false, true );
				}
			} );
		} );

		document.addEventListener( 'hint:a11y', function () {
			controllers.forEach( function ( controller ) {
				controller.refresh();
			} );
		} );

		controllers.forEach( function ( controller ) {
			controller.refresh();
		} );
	}

	/* ——— Hero video: pause/play toggle, paused by default with reduced motion ——— */

	function initHeroVideo() {
		var hero = document.querySelector( '.hint-hero' );

		if ( ! hero ) {
			return;
		}

		var video  = hero.querySelector( '.hint-hero__video' );
		var button = hero.querySelector( '.hint-hero__play' );

		if ( ! video || ! button ) {
			return;
		}

		// home.css hides the video under prefers-reduced-motion until the
		// script is in control (otherwise it would autoplay with no working pause).
		video.setAttribute( 'data-ready', '' );

		// What the visitor wants (reduced motion → paused until they press play).
		var wantPaused = mqMotion.matches;

		function reflect() {
			button.setAttribute( 'aria-pressed', video.paused ? 'true' : 'false' );
		}

		function play() {
			var promise = video.play();

			if ( promise && promise.catch ) {
				promise.catch( function () {
					reflect();
				} );
			}
		}

		function sync() {
			var hiddenByTheme = root.getAttribute( 'data-theme' ) === 'contrast';

			if ( wantPaused || hiddenByTheme ) {
				video.pause();
			} else if ( video.paused ) {
				play();
			}

			reflect();
		}

		if ( wantPaused ) {
			video.removeAttribute( 'autoplay' );
			video.pause();
			// Show the first frame instead of a blank box.
			video.addEventListener( 'loadedmetadata', function () {
				if ( wantPaused ) {
					try {
						video.currentTime = 0.01;
					} catch ( error ) {
						// Ignore: the solid hero background is the fallback.
					}
				}
			}, { once: true } );
		}

		// Autoplay may still kick in after the attribute was removed.
		video.addEventListener( 'play', function () {
			if ( wantPaused ) {
				video.pause();
			}
			reflect();
		} );
		video.addEventListener( 'pause', reflect );

		button.addEventListener( 'click', function () {
			wantPaused = ! video.paused;
			sync();
		} );

		document.addEventListener( 'hint:a11y', sync );

		// Boot through sync(): with high contrast already saved the video is
		// display:none, so stop it instead of letting it download and loop unseen.
		sync();
	}

	/* ——— Boot ——— */

	onMediaChange( mqDark, function () {
		if ( ! readPreference().theme ) {
			apply();
		}
	} );

	onMediaChange( mqContrast, function () {
		if ( ! readPreference().theme ) {
			apply();
		}
	} );

	// Another tab changed the preference.
	window.addEventListener( 'storage', function ( event ) {
		if ( null === event.key || STORAGE_KEY === event.key ) {
			apply();
		}
	} );

	apply();

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			initPanels();
			initHeroVideo();
		} );
	} else {
		initPanels();
		initHeroVideo();
	}
}() );
