<<<<<<< Updated upstream
mapboxgl.accessToken = 'pk.eyJ1IjoidGVtc3NzIiwiYSI6ImNsd250cjF3ZDFibncya3J5MzN0MHgzNzUifQ.Bz4RQ5XuUGJZ5UGpZUJZJQ'; // Replace with your Mapbox access token

        // Parse query parameters to get origin and destination
        const urlParams = new URLSearchParams(window.location.search);
        const origin = urlParams.get('from');
        const destination = urlParams.get('to');

        // Initialize map
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/satellite-streets-v12', // Replace with your preferred map style
            center: [30.0573877, -1.9209785], // Default center coordinates (Kigali, Rwanda)
            zoom: 16.5 // Default zoom level
        });

        // Add navigation control (zoom buttons)
        map.addControl(new mapboxgl.NavigationControl());

        // Add directions control
        const directions = new MapboxDirections({
            accessToken: mapboxgl.accessToken,
            unit: 'metric',
            profile: 'mapbox/driving', // Adjust profile based on your preference
            interactive: true,
            controls: {
                inputs: true,
                instructions: true,
                profileSwitcher: true
            }
        });

        // Add directions control to the map
        map.addControl(directions, 'top-left');

        // If origin and destination are available, set them in the directions input
        if (origin && destination) {
            directions.setOrigin(origin);
            directions.setDestination(destination);
        }

        // Add event listener to display route duration
        directions.on('route', (e) => {
            const routes = e.route;
            if (routes.length > 0) {
                const route = routes[0];
                const duration = Math.floor(route.duration / 60); // Duration in minutes
                alert(`Estimated travel time: ${duration} minutes`);
            }
        });
        // main.js
$(document).ready(function() {
    $('.hamburger').click(function() {
        openNav();
    });

    function openNav() {
        document.getElementById("sideMenu").style.width = "250px";
    }

    window.closeNav = function() {
        document.getElementById("sideMenu").style.width = "0";
    }
});
function showSignupForm() {
    document.getElementById('signup-form').style.display = 'block';
}
=======
mapboxgl.accessToken = 'pk.eyJ1IjoidGVtc3NzIiwiYSI6ImNsd250cjF3ZDFibncya3J5MzN0MHgzNzUifQ.Bz4RQ5XuUGJZ5UGpZUJZJQ'; // Replace with your Mapbox access token

        // Parse query parameters to get origin and destination
        const urlParams = new URLSearchParams(window.location.search);
        const origin = urlParams.get('from');
        const destination = urlParams.get('to');

        // Initialize map
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/satellite-streets-v12', // Replace with your preferred map style
            center: [-1.9201908821699905, 30.05638055011094], // Default center coordinates (Kigali, Rwanda)
            zoom: 16.5 // Default zoom level
        });

        // Add navigation control (zoom buttons)
        map.addControl(new mapboxgl.NavigationControl());

        // Add directions control
        const directions = new MapboxDirections({
            accessToken: mapboxgl.accessToken,
            unit: 'metric',
            profile: 'mapbox/driving', // Adjust profile based on your preference
            interactive: true,
            controls: {
                inputs: true,
                instructions: true,
                profileSwitcher: true
            }
        });

        // Add directions control to the map
        map.addControl(directions, 'top-left');

        // If origin and destination are available, set them in the directions input
        if (origin && destination) {
            directions.setOrigin(origin);
            directions.setDestination(destination);
        }

        // Add event listener to display route duration
        directions.on('route', (e) => {
            const routes = e.route;
            if (routes.length > 0) {
                const route = routes[0];
                const duration = Math.floor(route.duration / 60); // Duration in minutes
                alert(`Estimated travel time: ${duration} minutes`);
            }
        });
>>>>>>> Stashed changes
