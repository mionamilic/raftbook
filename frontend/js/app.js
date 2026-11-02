
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
// Tour data loaded from MySQL
let tours = {};

// Keep the existing CSS image classes
const tourImages = {
    rafting: "rafting-image",
    hiking: "hiking-image",
    canyoning: "canyoning-image"
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
        console.log("Tour data is not loaded yet.");
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
    loadTourCards();
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

    loginForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const email = document.getElementById("login-email").value.trim();
        const password = document.getElementById("login-password").value;

        try {
            const response = await fetch(
                "http://127.0.0.1:8000/routes/LoginRoutes.php",
                {
                    method: "POST",
                    credentials: "include",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                }
            );

            const result = await response.json();

            if (!response.ok) {
                alert(result.error || "Login failed.");
                return;
            }

            alert("Welcome, " + result.user.full_name + "!");
            window.location.hash = "dashboard";

        } catch (error) {
            console.error("Login error:", error);
            alert("Could not connect to the server.");
        }
    });
}


    
if (pageId === "register") {
    const registerForm = document.getElementById("register-form");

    registerForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const fullName = document.getElementById("register-name").value.trim();
        const email = document.getElementById("register-email").value.trim();
        const password = document.getElementById("register-password").value;

        try {
            const response = await fetch(
                "http://localhost:8000/routes/RegisterRoutes.php",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        full_name: fullName,
                        email: email,
                        password: password
                    })
                }
            );

            const result = await response.json();

            if (!response.ok) {
                alert(result.error || "Registration failed.");
                return;
            }

            alert(result.message);
            window.location.hash = "login";

        } catch (error) {
            console.error("Registration error:", error);
            alert("Could not connect to the server.");
        }
    });
}

}

// Listen for navigation changes
window.addEventListener("hashchange", navigate);

// Load initial page
navigate();

async function loadToursFromDatabase() {
    try {
        const response = await fetch(
            "http://localhost:8000/routes/TourRoutes.php"
        );

        if (!response.ok) {
            throw new Error("Failed to load tours");
        }

        const databaseTours = await response.json();

        const tourKeys = ["rafting", "hiking", "canyoning"];

        tours = {};

        databaseTours.forEach((tour) => {
            const tourKey = tourKeys[Number(tour.tour_id) - 1];

            if (tourKey) {
                tours[tourKey] = {
                    title: tour.title,
                    description: tour.description,
                    duration: tour.duration,
                    price: `${tour.price} BAM`,
                    location: tour.location,
                    image: tourImages[tourKey]
                };
            }
        });

        console.log("Tours loaded from MySQL:", tours);

        // Refresh the currently displayed page
        if (window.location.hash === "#tour-details") {
            showTourDetails(selectedTour);
        }

    } catch (error) {
        console.error("Error loading tours:", error);
    }
}

loadToursFromDatabase();

async function loadTourCards() {
    const container = document.getElementById("tour-container");

    if (!container) return;

    try {
        const response = await fetch(
            "http://localhost:8000/routes/TourRoutes.php"
        );

        if (!response.ok) {
            throw new Error("Failed to load tours");
        }

        const data = await response.json();

        const tourList = Array.isArray(data) ? data : data.tours;

        if (!Array.isArray(tourList)) {
            throw new Error("Invalid tours data");
        }

        const imageClasses = {
            1: "rafting-image",
            2: "hiking-image",
            3: "canyoning-image"
        };

        const tourNames = {
            1: "rafting",
            2: "hiking",
            3: "canyoning"
        };

        container.innerHTML = "";

        tourList.forEach((tour) => {
            const card = document.createElement("div");
            card.className = "tour-card";

            const image = document.createElement("div");
            image.className =
                "tour-image " + (imageClasses[tour.tour_id] || "");

            const info = document.createElement("div");
            info.className = "tour-info";

            const title = document.createElement("h3");
            title.textContent = tour.title;

            const description = document.createElement("p");
            description.textContent = tour.description;

            const duration = document.createElement("p");
            const durationLabel = document.createElement("strong");
            durationLabel.textContent = "Duration: ";
            duration.append(durationLabel, tour.duration);

            const price = document.createElement("p");
            const priceLabel = document.createElement("strong");
            priceLabel.textContent = "Price: ";
            price.append(priceLabel, `${tour.price} BAM`);

            const button = document.createElement("a");
            button.href = "#tour-details";
            button.className = "tour-button";
            button.textContent = "View details";

            button.addEventListener("click", () => {
                selectedTour = tourNames[tour.tour_id] || "rafting";
            });

            info.append(title, description, duration, price, button);
            card.append(image, info);
            container.appendChild(card);
        });

        console.log("Tour cards loaded from MySQL:", tourList);

    } catch (error) {
        console.error("Error loading tour cards:", error);
        container.textContent = "Unable to load adventures.";
    }
}

