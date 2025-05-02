<script src='https://api.mapbox.com/mapbox-gl-js/v1.13.1/mapbox-gl.js'></script>
<script src="https://watergis.github.io/mapbox-gl-export/mapbox-gl-export.js"></script>
<script src="{{asset('assets/front/js/static.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".custom-marker").forEach(marker => {
        marker.setAttribute("tabindex", "0");

        marker.addEventListener("focus", function () {
            this.classList.add("active-marker");
        });

        marker.addEventListener("blur", function () {
            this.classList.remove("active-marker");
        });

        marker.addEventListener("mousedown", function (e) {
            e.preventDefault();
        });
    });
});

interact(".custom-marker").resizable({
    edges: { left: true, right: true, top: true, bottom: true },
    modifiers: [
        interact.modifiers.restrictSize({
            min: { width: 50, height: 50 },
            max: { width: 300, height: 300 }
        })
    ],
    inertia: true
}).on("resizemove", function (event) {
    let target = event.target;
    let pTag = target.querySelector("p");

    // Apply new width and height
    target.style.width = `${event.rect.width}px`;
    target.style.height = `${event.rect.height}px`;

    // Adjust font size dynamically if <p> tag exists
    if (pTag) {
        let newSize = Math.max(12, event.rect.width / 10);
        pTag.style.fontSize = `${newSize}px`;
    }
});

</script>
