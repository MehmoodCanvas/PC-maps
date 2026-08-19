/*
 * Builds a mitred picture frame around a map preview from a moulding swatch.
 * See frame-preview.css for how the four strips are assembled.
 *
 * Markup:
 *   <div class="pframe" data-frame-swatch="/frames/web/E1SVG.jpg">
 *       <img src="...">
 *   </div>
 *
 * API:
 *   PictureFrame.apply(el, swatchUrl)  swatchUrl null/'' removes the frame
 *   PictureFrame.refresh(el)           re-measure after a layout change
 */
(function (window, document) {
	'use strict';

	// Frame thickness as a share of the print's shorter side, then clamped so
	// it stays believable on both tiny thumbnails and large previews.
	var THICKNESS_RATIO = 0.075;
	var MIN_THICKNESS = 12;
	var MAX_THICKNESS = 64;

	var SIDES = ['top', 'right', 'bottom', 'left'];

	function buildParts(root) {
		if (root._pframeParts) {
			return root._pframeParts;
		}

		var parts = {};

		var mount = document.createElement('div');
		mount.className = 'pframe-mount';
		root.appendChild(mount);

		SIDES.forEach(function (side) {
			var el = document.createElement('div');
			el.className = 'pframe-edge pframe-' + side;
			root.appendChild(el);
			parts[side] = el;
		});

		root._pframeParts = parts;
		return parts;
	}

	function artwork(root) {
		return root.querySelector('.pframe-art') || root.querySelector('img');
	}

	function refresh(root) {
		if (!root || !root.classList.contains('is-framed')) {
			return;
		}

		var art = artwork(root);

		if (!art) {
			return;
		}

		var width = art.offsetWidth;
		var height = art.offsetHeight;

		if (!width || !height) {
			return;
		}

		var thickness = Math.round(Math.min(width, height) * THICKNESS_RATIO);
		thickness = Math.max(MIN_THICKNESS, Math.min(MAX_THICKNESS, thickness));

		// Setting these changes the margin, which resizes the element and would
		// re-enter through the ResizeObserver -- only write on an actual change.
		if (root._pfT !== thickness) {
			root._pfT = thickness;
			root.style.setProperty("--pf-t", thickness + "px");
		}

		// Side rails are rotated, so their length is the frame's outer height.
		var outerHeight = height + 2 * thickness;

		if (root._pfH !== outerHeight) {
			root._pfH = outerHeight;
			root.style.setProperty("--pf-h", outerHeight + "px");
		}
	}

	function apply(root, swatchUrl) {
		if (!root) {
			return;
		}

		var parts = buildParts(root);

		if (!swatchUrl) {
			root.classList.remove('is-framed');
			root.removeAttribute('data-frame-swatch');
			return;
		}

		var value = 'url("' + String(swatchUrl).replace(/"/g, '\\"') + '")';

		SIDES.forEach(function (side) {
			parts[side].style.backgroundImage = value;
		});

		root.setAttribute('data-frame-swatch', swatchUrl);
		root.classList.add('is-framed');
		refresh(root);
	}

	function watch(root) {
		var art = artwork(root);

		if (art && !art.complete) {
			art.addEventListener('load', function () {
				refresh(root);
			});
		}

		if (typeof window.ResizeObserver === 'function') {
			new window.ResizeObserver(function () {
				refresh(root);
			}).observe(root);
		}
	}

	function init() {
		var roots = document.querySelectorAll('.pframe');

		Array.prototype.forEach.call(roots, function (root) {
			watch(root);

			var swatch = root.getAttribute('data-frame-swatch');

			if (swatch) {
				apply(root, swatch);
			} else {
				buildParts(root);
			}
		});
	}

	window.PictureFrame = {
		apply: apply,
		refresh: refresh
	};

	// Fallback for browsers without ResizeObserver.
	window.addEventListener('resize', function () {
		Array.prototype.forEach.call(document.querySelectorAll('.pframe'), refresh);
	});

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})(window, document);
