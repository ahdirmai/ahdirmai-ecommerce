import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

import "./bootstrap";

// Mobile menu toggle
document.addEventListener("DOMContentLoaded", function () {
    const mobileMenuToggle = document.getElementById("mobileMenuToggle");
    const mobileMenu = document.getElementById("mobileMenu");

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener("click", () => {
            mobileMenu.classList.toggle("active");
            const icon = mobileMenuToggle;
            if (mobileMenu.classList.contains("active")) {
                icon.classList.remove("fa-bars");
                icon.classList.add("fa-times");
            } else {
                icon.classList.remove("fa-times");
                icon.classList.add("fa-bars");
            }
        });
    }

    // Dark mode toggle
    const darkModeToggle = document.getElementById("darkModeToggle");
    const html = document.documentElement;

    // Check for saved dark mode preference or default to light mode
    const currentMode = localStorage.getItem("darkMode") || "light";
    if (currentMode === "dark") {
        html.classList.add("dark");
        if (darkModeToggle) {
            darkModeToggle.classList.remove("fa-moon");
            darkModeToggle.classList.add("fa-sun");
        }
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener("click", () => {
            html.classList.toggle("dark");

            if (html.classList.contains("dark")) {
                localStorage.setItem("darkMode", "dark");
                darkModeToggle.classList.remove("fa-moon");
                darkModeToggle.classList.add("fa-sun");
            } else {
                localStorage.setItem("darkMode", "light");
                darkModeToggle.classList.remove("fa-sun");
                darkModeToggle.classList.add("fa-moon");
            }
        });
    }

    // Close mobile menu when clicking on a link
    const mobileMenuLinks = document.querySelectorAll("#mobileMenu a");
    mobileMenuLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (mobileMenu) {
                mobileMenu.classList.remove("active");
            }
            if (mobileMenuToggle) {
                mobileMenuToggle.classList.remove("fa-times");
                mobileMenuToggle.classList.add("fa-bars");
            }
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });
});
