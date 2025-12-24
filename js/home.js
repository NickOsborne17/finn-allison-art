import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

if (document.body.classList.contains('page-template-template-home')) {
  const heroLogo = document.querySelector('.page-template-template-home .hero-logo'),
    trigger = document.querySelector('.page-template-template-home .header-trigger'),
    navLogo = document.querySelector('.main-navigation .desktop-logo');

  // Logo
  gsap.set(heroLogo, { scale: 1.6 });

  gsap.to(heroLogo, {
    scale: 1,
    scrollTrigger: {
      trigger: trigger,
      start: "bottom bottom",
      end: "bottom 45%",
      scrub: true
    }
  });

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          heroLogo.classList.add('hidden');
          navLogo.classList.add('visible');
        } else {
          heroLogo.classList.remove('hidden');
          navLogo.classList.remove('visible');
        }
      });
    },
    { rootMargin: '0px 0px -55% 0px' }
  );

  observer.observe(trigger);

  // Text
  gsap.to(".hero-text", {
    opacity: 0,
    scrollTrigger: {
      trigger: ".hero-text",
      start: "top 45%",
      end: "bottom 40%",
      scrub: true,
      onEnter: () => {
        gsap.set(".hero-text", { display: "block" });
      },
      onLeave: () => {
        gsap.set(".hero-text", { display: "none" });
      },
      onEnterBack: () => {
        gsap.set(".hero-text", { display: "block" });
      },
      onLeaveBack: () => {
        gsap.set(".hero-text", { display: "block" });
      }
    }
  });
}