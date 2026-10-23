
const app = document.getElementById("app");

// Available pages
const pages = [
    "home",
    "tours",
    "tour-details",
    "weather",
    "login",
    "register",
    "dashboard",
    "bookings",
    "profile"
];

// Tour data
const tours = {
    rafting: {
        title: "Tara River Rafting",
        description: "Enjoy an unforgettable rafting experience through the Tara Canyon.",
        duration: "3–4 hours",
        price: "70 BAM",
        location: "Tara River, Foča",
        image: "rafting-image"
    },

    hiking: {
        title: "Mountain Hiking",
        description: "Explore beautiful mountain trails and enjoy breathtaking views.",
        duration: "Half day",
        price: "40 BAM",
        location: "Sutjeska National Park",
        image: "hiking-image"
    },

    canyoning: {
        title: "Canyoning Adventure",
        description: "Discover waterfalls and hidden passages in Hrcavka Canyon.",
        duration: "3 hours",
        price: "90 BAM",
        location: "Hrčavka, Sutjeska",
        image: "canyoning-image"
    }
};

// Remember which tour was selected
let selectedTour = "rafting";

// Load a view without reloading the entire page
async function navigate() {
    let pageId = window.location.hash.substring(1) || "home";

    if (!pages.includes(pageId)) {
        pageId = "home";
    }

    try {
        const response = await fetch(`views/${pageId}.html`);

        if (!response.ok) {
            throw new Error("Could not load the page.");
        }

        const html = await response.text();
        app.innerHTML = html;

        // Initialize functionality for the loaded view
        initializePage(pageId);

        window.scrollTo(0, 0);
    } catch (error) {
        console.error(error);
        app.innerHTML = "<p>Unable to load this page.</p>";
    }
}

// Display selected tour information
function showTourDetails(tourName) {
    const tour = tours[tourName];

    if (!tour) {
        return;
    }

    document.getElementById("detail-title").textContent = tour.title;
    document.getElementById("detail-description").textContent = tour.description;
    document.getElementById("detail-duration").textContent = tour.duration;
    document.getElementById("detail-price").textContent = tour.price;
    document.getElementById("detail-location").textContent = tour.location;

    const image = document.getElementById("detail-image");

    image.classList.remove(
        "rafting-image",
        "hiking-image",
        "canyoning-image"
    );

    image.classList.add(tour.image);
}

// Connect functionality after a view is loaded
function initializePage(pageId) {

    if (pageId === "tours") {
        const tourButtons = document.querySelectorAll(
            ".tour-button[data-tour]"
        );

        tourButtons.forEach((button) => {
            button.addEventListener("click", () => {
                selectedTour = button.getAttribute("data-tour");

                // If already on the details route, reload it
                if (window.location.hash === "#tour-details") {
                    navigate();
                }
            });
        });
    }

    if (pageId === "tour-details") {
        showTourDetails(selectedTour);

        const bookButton = document.getElementById("book-button");

        bookButton.addEventListener("click", () => {
            alert("Please log in to book this adventure.");
            window.location.hash = "login";
        });
    }

    if (pageId === "login") {
        const loginForm = document.getElementById("login-form");

        loginForm.addEventListener("submit", (event) => {
            event.preventDefault();
            alert("Login will be available when the backend is connected.");
        });
    }

    if (pageId === "register") {
        const registerForm = document.getElementById("register-form");

        registerForm.addEventListener("submit", (event) => {
            event.preventDefault();
            alert("Registration will be available when the backend is connected.");
        });
    }
}

// Listen for navigation changes
window.addEventListener("hashchange", navigate);

// Load initial page
navigate();
