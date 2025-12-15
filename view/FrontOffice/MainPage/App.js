gsap.registerPlugin(Draggable);

let currentSlide = 1;
const totalSlides = 4;
let isExpanded = false;
let isSliding = false;
let isExpanding = false;

const slider = document.querySelector(".slider-container");
const overlay = document.querySelector(".overlay");
const overlayWrap = document.querySelector(".overlay-preview-wrap");
const previews = document.querySelectorAll(".overlay-slide-preview");
const navbar = document.querySelector(".navbar");
const preloader = document.querySelector(".preloader");
const controls = document.querySelector(".slider-controls");

// Images array
const images = [
  "hands.jpg",
  "women.jpg",
  "assets/images/people.jpg",
  "assets/images/smile.jpg"
];

let loadedCount = 0;

// Preload + set backgrounds
images.forEach((src, i) => {
  const img = new Image();
  img.onload = () => {
    loadedCount++;
    previews[i].style.background = `url(${src}) center/cover no-repeat`;
    if (loadedCount === 4) {
      // Set initial background once loaded
      slider.style.background = `url(${images[0]}) center/cover no-repeat`;
      // Hide preloader and start
      gsap.to(preloader, {
        y: "-100%",
        duration: 1,
        ease: "power2.inOut",
        onComplete: () => {
          preloader.remove();
          onloadAnimation();
        }
      });
    }
  };
  img.src = src;
});

// Fallback: If images cached/super fast, force start after timeout
setTimeout(() => {
  if (loadedCount < 4) {
    console.warn("Images may not have loaded — using fallback.");
    slider.style.background = `url(${images[0]}) center/cover no-repeat`; // Fallback initial bg
    preloader.remove();
    onloadAnimation();
  }
}, 5000); // Increased timeout for slower connections

function onloadAnimation() {
  gsap.timeline()
    .fromTo(".slide-number-container", { x: "3%" }, { opacity: 1, x: 0, duration: 0.32 }, "+=0.56")
    .fromTo(".slide-title", { y: "200%" }, { y: 0, duration: 0.64 }, "-=0.64")
    .to(".slide-info-container", { opacity: 1 }, "+=0.64")
    .to(".slide-info-box", { clipPath: "polygon(0% 100%, 100% 100%, 100% 0%, 0% 0%)" }, "-=0.64")
    .to(".slide-info-box a, .slide-info-box h4", { opacity: 1 }, "+=0.18")
    .to(controls, { opacity: 1, duration: 0.32 }, "-=0.64");
}

function goToSlide(n) {
  if (isSliding || n < 1 || n > totalSlides || n === currentSlide) return;
  isSliding = true;

  const direction = n > currentSlide ? "-=100%" : "+=100%";
  currentSlide = n;

  slider.style.background = `url(${images[n-1]}) center/cover no-repeat`;

  gsap.timeline({
    onComplete: () => isSliding = false
  })
    .to(".slide-title.active", { clipPath: "polygon(0% 0%, 0% 100%, 0% 100%, 0% 0%)", duration: 0.32 })
    .to(".title-wrap", { y: direction, duration: 0.44, ease: "power2.inOut" }, "-=0.32")
    .to(".number-wrap", { y: direction, duration: 0.4, ease: "power2.inOut" }, "-=0.44")
    .to(".slide-number.active", { opacity: 0, duration: 0.32 })
    .set(".slide-number", { opacity: 1 })
    .to(".slide-title", { clipPath: "polygon(0% 0%, 0% 100%, 100% 100%, 100% 0%)", duration: 0.64 })
    .to(".slide-info", { y: direction, opacity: 0, duration: 0.32 }, "-=0.64")
    .to(".slide-info", { opacity: 1, duration: 0.32 })

    // Update active classes
    .add(() => {
      document.querySelectorAll(".slide-title, .slide-number, .slide-info").forEach(el => el.classList.remove("active"));
      document.querySelectorAll(".slide-title")[n-1].classList.add("active");
      document.querySelectorAll(".slide-number")[n-1].classList.add("active");
      document.querySelectorAll(".slide-info")[n-1].classList.add("active");
    });
}

function toggleOverlay() {
  if (isExpanding) return;
  isExpanding = true;

  if (!isExpanded) {
    gsap.timeline({
      onComplete: () => { isExpanding = false; isExpanded = true; }
    })
      .to(".slide-title.active", { clipPath: "polygon(0% 0%, 0% 100%, 0% 100%, 0% 0%)", duration: 0.4 })
      .to(".slide-number.active", { y: "+=100%" }, "-=0.4")
      .to(".slide-info-box", { opacity: 0 }, "-=0.4")
      .to(navbar, { y: "-100%" }, 0.4)
      .set(overlay, { visibility: "visible" })
      .to(overlay, { autoAlpha: 1, delay: 0.4 })
      .fromTo(".overlay-nav-heading, .overlay-close", { clipPath: "polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)" }, { clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)", duration: 0.5, stagger: 0.1 })
      .fromTo(previews, { clipPath: "polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)" }, { clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)", duration: 0.8, stagger: 0.2, ease: "expo.inOut" }, "-=0.4")
      .fromTo(".overlay-preview-title-text", { clipPath: "polygon(0% 100%, 100% 100%, 100% 100%, 0% 100%)" }, { clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)", stagger: 0.32, duration: 0.56, ease: "sine" }, "-=0.48")
      .fromTo(".overlay-preview-title-number", { opacity: 0 }, { opacity: 1, stagger: 0.32, duration: 0.48 }, "-=0.48");
  } else {
    gsap.timeline({
      onComplete: () => {
        overlay.style.visibility = "hidden";
        isExpanding = false;
        isExpanded = false;
        onloadAnimation();
      }
    })
      .to(overlay, { autoAlpha: 0 })
      .to(navbar, { y: 0, duration: 0.6 }, "-=0.4");
  }
}

// Buttons
document.querySelector(".slide-prev-btn").onclick = () => goToSlide(currentSlide - 1);
document.querySelector(".slide-next-btn").onclick = () => goToSlide(currentSlide + 1);
document.querySelector(".slide-overlay-btn").onclick = toggleOverlay;
document.querySelector(".overlay-close").onclick = toggleOverlay;

// Click on preview → go to that slide + close overlay
previews.forEach((p, i) => {
  p.onclick = () => {
    goToSlide(i + 1);
    gsap.to(overlayWrap, { x: `${-i * 25}%`, duration: 0.5 });
    toggleOverlay();
  };
});

// Draggable overlay
Draggable.create(overlayWrap, {
  type: "x",
  bounds: ".overlay-slide-container",
  inertia: true,
  dragResistance: 0.55
});

// Mobile menu
document.querySelector(".navbar-toggle").onclick = () => {
  const expanded = navbar.dataset.expanded === "true";
  navbar.dataset.expanded = !expanded;
  gsap.to(navbar, { height: expanded ? "40px" : "100%", duration: 0.4 });
};