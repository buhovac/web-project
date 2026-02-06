class Carousel {
    constructor(root) {
        this.root = root;
        this.track = root.querySelector("[data-carousel-track]");
        this.slides = Array.from(this.track?.children ?? []);
        this.prevBtn = root.querySelector("[data-carousel-prev]");
        this.nextBtn = root.querySelector("[data-carousel-next]");
        this.dotsRoot = root.querySelector("[data-carousel-dots]");
        this.index = 0;

        if (!this.track || this.slides.length === 0) return;

        this.renderDots();
        this.bind();
        this.update();

        window.addEventListener("resize", () => this.update());
    }

    slidesPerView() {
        if (window.innerWidth >= 1024) return 2;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }

    maxIndex() {
        return Math.max(0, this.slides.length - this.slidesPerView());
    }

    renderDots() {
        if (!this.dotsRoot) return;
        this.dotsRoot.innerHTML = "";
        const dotCount = this.maxIndex() + 1;

        for (let i = 0; i < dotCount; i++) {
            const b = document.createElement("button");
            b.type = "button";
            b.className = "carousel__dot";
            b.dataset.carouselDot = String(i);
            b.setAttribute("aria-label", `Aller à ${i + 1}`);
            this.dotsRoot.appendChild(b);
        }
    }

    bind() {
        this.prevBtn?.addEventListener("click", () => this.goTo(this.index - 1));
        this.nextBtn?.addEventListener("click", () => this.goTo(this.index + 1));

        this.dotsRoot?.addEventListener("click", (e) => {
            const btn = e.target.closest("button[data-carousel-dot]");
            if (!btn) return;
            this.goTo(parseInt(btn.dataset.carouselDot, 10));
        });

        // Swipe (mobile-friendly)
        let startX = 0;
        this.root.addEventListener("touchstart", (e) => (startX = e.touches[0].clientX), { passive: true });
        this.root.addEventListener("touchend", (e) => {
            const endX = e.changedTouches[0].clientX;
            const dx = endX - startX;
            if (Math.abs(dx) < 40) return;
            if (dx > 0) this.goTo(this.index - 1);
            else this.goTo(this.index + 1);
        });
    }

    goTo(i) {
        this.index = Math.max(0, Math.min(this.maxIndex(), i));
        this.update();
    }

    update() {
        // ako se breakpoint promijeni, recalculiraj dots i clamp index
        this.index = Math.min(this.index, this.maxIndex());
        this.renderDots();

        const slideWidth = this.slides[0].getBoundingClientRect().width;
        this.track.style.transform = `translateX(${-this.index * slideWidth}px)`;

        // dots active
        const dots = Array.from(this.dotsRoot?.querySelectorAll(".carousel__dot") ?? []);
        dots.forEach((d, idx) => d.classList.toggle("is-active", idx === this.index));

        // buttons
        if (this.prevBtn) this.prevBtn.disabled = this.index === 0;
        if (this.nextBtn) this.nextBtn.disabled = this.index === this.maxIndex();
    }
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-carousel]").forEach((root) => new Carousel(root));

    // Mobile Navigation Toggle
    const toggleBtn = document.querySelector('.nav-toggle');
    const nav = document.getElementById('main-nav');

    if (toggleBtn && nav) {
        toggleBtn.addEventListener('click', () => {
            const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
            toggleBtn.setAttribute('aria-expanded', !isExpanded);
            nav.classList.toggle('is-open');
        });
    }
});


// API integration
async function loadWeather() {
    const out = document.getElementById("weather");
    if (!out) return;

    try {
        const res = await fetch(
            "https://api.open-meteo.com/v1/forecast?latitude=50.85&longitude=4.35&current=temperature_2m&timezone=Europe%2FBrussels"
        );
        const data = await res.json();

        out.textContent = `Brussels: ${data.current.temperature_2m}°C`;
    } catch {
        out.textContent = "Weather unavailable.";
    }
}

document.addEventListener("DOMContentLoaded", loadWeather);
