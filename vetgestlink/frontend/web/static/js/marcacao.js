/* ========================================
   Marcação - Scripts
   ======================================== */

(function () {
    'use strict';

    // Helper: try native Bootstrap collapse if available (Bootstrap 5)
    function bsToggle(btn, targetEl) {
        try {
            if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                // If bootstrap present, use its Collapse and events so styles behave normally
                var bsCollapse = bootstrap.Collapse.getInstance(targetEl) || new bootstrap.Collapse(targetEl, {toggle: false});
                bsCollapse.toggle();
                return true;
            }
        } catch (e) {
            // ignore and fallback
        }
        return false;
    }

    // Fallback: custom toggle
    function fallbackToggle(el) {
        var isShown = el.classList.contains('show');
        if (isShown) {
            // hide
            el.style.maxHeight = el.scrollHeight + 'px'; // set current then force to 0 for smooth anim
            requestAnimationFrame(function () {
                el.style.maxHeight = '0px';
                el.classList.remove('show');
                // after transition, hide to remove from accessibility flow
                setTimeout(function () { el.hidden = true; }, 300);
            });
        } else {
            // show
            el.hidden = false;
            // set to 0 then to scrollHeight to animate
            el.style.maxHeight = '0px';
            requestAnimationFrame(function () {
                el.classList.add('show');
                el.style.maxHeight = el.scrollHeight + 'px';
            });
            // clear maxHeight after animation to allow responsive height
            setTimeout(function () { el.style.maxHeight = ''; }, 300);
        }
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.toggle-diagnostico');
        if (!btn) return;
        var targetSelector = btn.getAttribute('data-target');
        if (!targetSelector) return;
        var target = document.querySelector(targetSelector);
        if (!target) return;

        // Try Bootstrap first
        var usedBootstrap = bsToggle(btn, target);
        if (!usedBootstrap) {
            // Fallback behavior
            fallbackToggle(target);
        }

        // Toggle aria-expanded
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    });
})();

