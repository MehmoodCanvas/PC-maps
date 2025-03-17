<script src='https://api.mapbox.com/mapbox-gl-js/v1.13.1/mapbox-gl.js'></script>
<script src="https://watergis.github.io/mapbox-gl-export/mapbox-gl-export.js"></script>
<script src="{{asset('assets/front/js/static.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const markers = document.querySelectorAll('.custom-marker');

    markers.forEach(marker => {
        // Allow focusing for interaction detection
        marker.setAttribute('tabindex', '0');

        // Ensure clicking inside the marker still triggers focus
        marker.addEventListener('mousedown', (event) => {
            event.preventDefault(); // Prevents focus from going to child elements
            marker.focus(); // Manually set focus on the marker
        });

        marker.addEventListener('focus', () => {
            marker.classList.add('active-marker');
        });

        marker.addEventListener('blur', () => {
            marker.classList.remove('active-marker');
        });
    });
});

interact('.custom-marker')
    .resizable({
        edges: { left: true, right: true, bottom: true, top: true },

        listeners: {
            move(event) {
                var target = event.target;

                // Get the specific p inside this custom-marker
                const textElement = target.querySelector('p');

                if (textElement) {
                    // Update size while maintaining text flow
                    target.style.width = event.rect.width + 'px';
                    target.style.height = 'auto'; // Ensure height adjusts with the text

                    // Dynamically resize font based on width
                    let newFontSize = event.rect.width / 10;
                    textElement.style.fontSize = `${Math.max(12, newFontSize)}px`;
                }

                target.classList.add('active-marker');
            },
            end(event) {
                var target = event.target;
                target.classList.remove('active-marker');
            }
        },

        modifiers: [
            interact.modifiers.restrictEdges({ outer: 'parent' }),
            interact.modifiers.restrictSize({ min: { width: 100, height: 50 } })
        ],

        inertia: true
    })
    .draggable(false);
</script>
