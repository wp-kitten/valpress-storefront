/**
 * ValPress Storefront — checkout & UI helpers
 */
(function () {
	'use strict';

	function initSameAsBilling() {
		var toggle = document.getElementById('vs-same-as-billing');
		var shippingFields = document.getElementById('vs-shipping-fields');
		var mirrorWrap = document.getElementById('vs-shipping-mirror-fields');
		var form = toggle ? toggle.closest('form') : null;

		if (!toggle || !shippingFields || !mirrorWrap || !form) {
			return;
		}

		function syncMirror() {
			mirrorWrap.querySelectorAll('[data-mirror]').forEach(function (hidden) {
				var source = form.querySelector('[name="' + hidden.getAttribute('data-mirror') + '"]');
				if (source) {
					hidden.value = source.value;
				}
			});
		}

		function setMode(useMirror) {
			shippingFields.classList.toggle('d-none', useMirror);
			mirrorWrap.classList.toggle('d-none', !useMirror);

			shippingFields.querySelectorAll('input, select, textarea').forEach(function (input) {
				input.disabled = useMirror;
			});
		}

		toggle.addEventListener('change', function () {
			setMode(toggle.checked);
			if (toggle.checked) {
				syncMirror();
			}
		});

		form.querySelectorAll('[name^="billing_address"]').forEach(function (input) {
			input.addEventListener('input', function () {
				if (toggle.checked) {
					syncMirror();
				}
			});
		});

		form.addEventListener('submit', function () {
			if (toggle.checked) {
				syncMirror();
			}
		});

		setMode(toggle.checked);
		if (toggle.checked) {
			syncMirror();
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		initSameAsBilling();
	});
})();
