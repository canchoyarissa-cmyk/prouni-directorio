/**
 * ProUNI - Directorio Institucional
 * Comportamiento de pestañas (Asociados/Aliados), chips de filtro por
 * categoría y buscador por nombre dentro de cada panel.
 *
 * Adaptado del prototipo original a un IIFE sin dependencias, cargado en
 * el footer con defer para no bloquear el render y evitar conflictos con
 * otros scripts del tema o de Elementor.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initTabs();
		initChipFilters();
		initNameSearch();
	} );

	function initTabs() {
		var tabButtons = document.querySelectorAll( '.switch-tabs button' );
		var panels = document.querySelectorAll( '.tab-panel' );

		tabButtons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				tabButtons.forEach( function ( b ) {
					b.classList.remove( 'active' );
					b.setAttribute( 'aria-selected', 'false' );
				} );
				panels.forEach( function ( p ) {
					p.classList.remove( 'active' );
				} );
				btn.classList.add( 'active' );
				btn.setAttribute( 'aria-selected', 'true' );
				var target = document.getElementById( btn.dataset.tab );
				if ( target ) {
					target.classList.add( 'active' );
				}
			} );
		} );
	}

	function initChipFilters() {
		var chips = document.querySelectorAll( '.chip' );

		chips.forEach( function ( chip ) {
			chip.addEventListener( 'click', function () {
				var group = chip.parentElement;
				group.querySelectorAll( '.chip' ).forEach( function ( c ) {
					c.classList.remove( 'active' );
					c.setAttribute( 'aria-pressed', 'false' );
				} );
				chip.classList.add( 'active' );
				chip.setAttribute( 'aria-pressed', 'true' );

				var filter = chip.dataset.filter;
				var panel = chip.closest( '.tab-panel' );
				if ( ! panel ) {
					return;
				}

				panel.querySelectorAll( '.filter-card' ).forEach( function ( card ) {
					card.style.display = ( filter === 'all' || card.dataset.category === filter ) ? '' : 'none';
				} );

				updateNoResultsState( panel );
			} );
		} );
	}

	function initNameSearch() {
		var searchInputs = document.querySelectorAll( '.search-box input[data-role="name-search"]' );

		searchInputs.forEach( function ( input ) {
			input.addEventListener( 'input', function () {
				var panel = input.closest( '.tab-panel' );
				if ( ! panel ) {
					return;
				}
				var term = input.value.trim().toLowerCase();
				var activeChip = panel.querySelector( '.chip.active' );
				var activeFilter = activeChip ? activeChip.dataset.filter : 'all';

				panel.querySelectorAll( '.filter-card' ).forEach( function ( card ) {
					var matchesCategory = ( activeFilter === 'all' || card.dataset.category === activeFilter );
					var name = ( card.dataset.name || card.textContent || '' ).toLowerCase();
					var matchesName = ( term === '' || name.indexOf( term ) !== -1 );
					card.style.display = ( matchesCategory && matchesName ) ? '' : 'none';
				} );

				updateNoResultsState( panel );
			} );
		} );
	}

	function updateNoResultsState( panel ) {
		var visibleCards = panel.querySelectorAll( '.filter-card:not([style*="display: none"])' );
		var msg = panel.querySelector( '.no-results-msg' );
		if ( ! msg ) {
			return;
		}
		msg.classList.toggle( 'visible', visibleCards.length === 0 );
	}
} )();
