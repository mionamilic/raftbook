
const pages = document.querySelectorAll("main section");

// Show only the selected page
function showPage(pageId) {
    pages.forEach((page) => {
        if (page.id === pageId) {
            page.style.display = "";
        } else {
            page.style.display = "none";
        }
    });
}

// SPA navigation
function navigate() {
    const pageId = window.location.hash.substring(1) || "home";
    const page = document.getElementById(pageId);

    if (page && page.tagName === "SECTION") {
        showPage(pageId);
    } else {
        showPage("home");
    }

    window.scrollTo(0, 0);
}

window.addEventListener("hashchange", navigate);

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

// Show details of selected tour
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

// Connect View details buttons
const tourButtons = document.querySelectorAll(".tour-button[data-tour]");

tourButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const tourName = button.getAttribute("data-tour");
        showTourDetails(tourName);
    });
});

// Book Now button
const bookButton = document.getElementById("book-button");

if (bookButton) {
    bookButton.addEventListener("click", () => {
        alert("Please log in to book this adventure.");
        window.location.hash = "login";
    });
}

// Prevent forms from reloading the page.
// Real login and registration will be added with the backend.
const loginForm = document.getElementById("login-form");

if (loginForm) {
    loginForm.addEventListener("submit", (event) => {
        event.preventDefault();
        alert("Login will be available when the backend is connected.");
    });
}

const registerForm = document.getElementById("register-form");

if (registerForm) {
    registerForm.addEventListener("submit", (event) => {
        event.preventDefault();
        alert("Registration will be available when the backend is connected.");
    });
}

// Open the correct page when the website starts
navigate();
