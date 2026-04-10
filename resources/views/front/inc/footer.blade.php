<script src='https://api.mapbox.com/mapbox-gl-js/v1.13.1/mapbox-gl.js'></script>
<script src="https://watergis.github.io/mapbox-gl-export/mapbox-gl-export.js"></script>
<script src="{{asset('assets/front/js/static.js')}}"></script>

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

</script>
