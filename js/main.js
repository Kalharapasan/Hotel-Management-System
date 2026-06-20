document.addEventListener("DOMContentLoaded", function () {

    // 1. Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const targetId = this.getAttribute("href");
            const target = document.querySelector(targetId);

            if (target) {
                target.scrollIntoView({ behavior: "smooth" });
            }
        });
    });

    // 2. Scroll Animation (simple)
    const elements = document.querySelectorAll(".animate-on-scroll");

    function showOnScroll() {
        elements.forEach(function (el) {
            const position = el.getBoundingClientRect().top;

            if (position < window.innerHeight - 100) {
                el.classList.add("animate-fade-in");
            }
        });
    }

    window.addEventListener("scroll", showOnScroll);
    showOnScroll(); // run on load

    // 3. Mobile Menu Toggle
    const btn = document.getElementById("mobile-menu-btn");
    const menu = document.getElementById("mobile-menu");

    if (btn && menu) {
        btn.addEventListener("click", function () {
            menu.classList.toggle("hidden");
        });
    }

});