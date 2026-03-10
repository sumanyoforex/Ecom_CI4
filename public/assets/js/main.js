(function () {
    const root = document.documentElement;
    const btn = document.getElementById("themeToggle");
    const icon = btn ? btn.querySelector("i") : null;
    const nav = document.querySelector(".glass-nav");
    const saved = localStorage.getItem("theme");
    const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
    const initialTheme = saved || (prefersDark ? "dark" : "light");

    function applyTheme(theme) {
        root.setAttribute("data-theme", theme);
        if (nav) {
            nav.classList.toggle("navbar-dark", theme === "dark");
            nav.classList.toggle("navbar-light", theme !== "dark");
        }
        if (icon) {
            icon.className = theme === "dark" ? "fa-solid fa-sun" : "fa-solid fa-moon";
        }
    }

    applyTheme(initialTheme);

    if (btn) {
        btn.addEventListener("click", function () {
            const current = root.getAttribute("data-theme") === "dark" ? "dark" : "light";
            const next = current === "dark" ? "light" : "dark";
            localStorage.setItem("theme", next);
            applyTheme(next);
        });
    }

    const popup = document.getElementById("orbPopup");
    const close = document.getElementById("closePopup");

    if (popup && !sessionStorage.getItem("popupSeen")) {
        window.setTimeout(function () {
            popup.classList.add("show");
            popup.setAttribute("aria-hidden", "false");
            sessionStorage.setItem("popupSeen", "1");
        }, 800);
    }

    if (close && popup) {
        close.addEventListener("click", function () {
            popup.classList.remove("show");
            popup.setAttribute("aria-hidden", "true");
        });

        popup.addEventListener("click", function (event) {
            if (event.target === popup) {
                popup.classList.remove("show");
                popup.setAttribute("aria-hidden", "true");
            }
        });
    }

    const slider = document.getElementById("categorySlider");
    if (!slider) {
        return;
    }

    const prevBtn = document.querySelector('[data-category-nav="prev"]');
    const nextBtn = document.querySelector('[data-category-nav="next"]');

    const getGap = function () {
        return parseFloat(getComputedStyle(slider).gap || "16");
    };

    const getStep = function () {
        const firstCard = slider.querySelector(".category-slide");
        if (!firstCard) {
            return slider.clientWidth * 0.8;
        }
        return firstCard.getBoundingClientRect().width + getGap();
    };

    const slide = function (direction) {
        slider.scrollBy({ left: direction * getStep(), behavior: "smooth" });
    };

    if (prevBtn) {
        prevBtn.addEventListener("click", function () {
            slide(-1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", function () {
            slide(1);
        });
    }

    let autoTimer = null;

    const stopAuto = function () {
        if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
        }
    };

    const startAuto = function () {
        stopAuto();
        if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            return;
        }

        autoTimer = setInterval(function () {
            const atEnd = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 4;
            if (atEnd) {
                slider.scrollTo({ left: 0, behavior: "smooth" });
            } else {
                slide(1);
            }
        }, 2600);
    };

    slider.addEventListener("mouseenter", stopAuto);
    slider.addEventListener("mouseleave", startAuto);
    slider.addEventListener("touchstart", stopAuto, { passive: true });
    slider.addEventListener("touchend", startAuto, { passive: true });
    slider.addEventListener("focusin", stopAuto);
    slider.addEventListener("focusout", startAuto);

    startAuto();
})();
