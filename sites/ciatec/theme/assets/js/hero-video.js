/**
 * Hero background video: plays only when the visitor allows motion; pause/play button always works.
 * Vanilla JS. Without it the poster stays and the button stays hidden.
 */
( function () {
	'use strict';

	var video = document.querySelector( '.ciatec-hero__video' );
	var button = document.querySelector( '.ciatec-hero__control' );

	if ( ! video || ! button ) {
		return;
	}

	var label = button.querySelector( '.screen-reader-text' );
	var connection = navigator.connection || {};
	var blocked =
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ||
		true === connection.saveData ||
		window.matchMedia( '(max-width: 767px)' ).matches;

	function render() {
		var playing = ! video.paused && ! video.ended;
		button.classList.toggle( 'is-playing', playing );
		label.textContent = playing ? button.getAttribute( 'data-label-pause' ) : button.getAttribute( 'data-label-play' );
	}

	function play() {
		video.preload = 'auto';
		var attempt = video.play();
		if ( attempt && attempt.catch ) {
			attempt.catch( render ); // autoplay refused: stay on the poster.
		}
	}

	button.addEventListener( 'click', function () {
		if ( video.paused ) {
			play();
		} else {
			video.pause();
		}
	} );
	video.addEventListener( 'play', render );
	video.addEventListener( 'pause', render );

	button.hidden = false;
	render();

	if ( ! blocked ) {
		play();
	}
} )();
