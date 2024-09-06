// server.js
const express = require('express');
const app = express();
const port = 3000; // Port to listen on

// Sample data for ULK campus locations
const locations = [
    [
        { "longitude": 30.055050, "latitude": -1.922207, "name": "ULK Stadium" },
        { "longitude": 30.056263, "latitude": -1.921177, "name": "Clinic" },
        { "longitude": 30.057480, "latitude": -1.921900, "name": "Finance" },
        { "longitude": 30.055290, "latitude": -1.920800, "name": "Church" },
        { "longitude": 30.056490, "latitude": -1.920200, "name": "Masters Building" },
        { "longitude": 30.057880, "latitude": -1.920870, "name": "Cafeteria" },
        { "longitude": 30.057540, "latitude": -1.922260, "name": "Law" },
    ]
    
    // Add more locations as needed
];

// Endpoint to get ULK campus locations
app.get('/ulk-locations', (req, res) => {
    res.json(locations);
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
