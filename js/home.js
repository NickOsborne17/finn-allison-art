import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

if (document.body.classList.contains('page-template-template-home')) {
  const heroLogo = document.querySelector('.page-template-template-home .hero-logo'),
    trigger = document.querySelector('.page-template-template-home .header-trigger'),
    navLogo = document.querySelector('.main-navigation .desktop-logo'),
    bottomSection = document.querySelector('.page-template-template-home .bottom-section');
  
  let observer = null;
  let wheelListener = null;
  let resizeTimeout;

  function handleViewportChange() {
    const isMobile = window.innerWidth <= 767;
    
    if (!isMobile && !observer) {
      // Create observer for desktop
      observer = new IntersectionObserver(
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
    } else if (isMobile && observer) {
      // Clean up observer and classes on mobile
      observer.disconnect();
      observer = null;
      navLogo.classList.remove('visible');
      heroLogo.classList.remove('hidden');
    }
  }

  handleViewportChange();

  // Logo animation - still controlled by scroll position
  gsap.set(heroLogo, { scale: 1.6 });
  const logoScrollTrigger = gsap.to(heroLogo, {
    scale: 1,
    scrollTrigger: {
      trigger: trigger,
      start: "bottom bottom",
      end: "bottom 45%",
      scrub: true
    }
  });

  // Text animation
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

  // Scroll snapping initialization
  function initScrollSnap() {
    const isMobile = window.innerWidth <= 767;
    
    // Remove existing wheel listener if it exists
    if (wheelListener) {
      window.removeEventListener('wheel', wheelListener);
      wheelListener = null;
    }
    
    // Only init scroll snap on desktop
    if (!isMobile) {
      let isSnapping = false;
      
      function getCurrentSection() {
        const scrollPos = window.scrollY;
        const viewportHeight = window.innerHeight;
        return Math.round(scrollPos / viewportHeight);
      }

      function snapToSection(direction) {
        if (isSnapping) return;
        
        isSnapping = true;
        const currentSection = getCurrentSection();
        const viewportHeight = window.innerHeight;
        let targetSection;
        
        if (direction > 0 && currentSection < 1) {
          targetSection = 1;
        } else if (direction < 0 && currentSection > 0) {
          targetSection = 0;
        } else {
          targetSection = currentSection;
        }
        
        const targetScroll = targetSection * viewportHeight;
        const snapDuration = 2;
        
        gsap.to(heroLogo, {
          scale: targetSection === 1 ? 1 : 1.6,
          duration: snapDuration * 0.6,
          ease: "power2.inOut"
        });
        
        gsap.to(window, {
          scrollTo: targetScroll,
          duration: snapDuration,
          ease: "power2.inOut",
          onComplete: () => {
            isSnapping = false;
          }
        });
      }

      let lastScrollTime = 0;
      
      wheelListener = (e) => {
        const now = Date.now();
        const direction = e.deltaY > 0 ? 1 : -1;
        
        if (now - lastScrollTime > 150) {
          e.preventDefault();
          snapToSection(direction);
        }
        
        lastScrollTime = now;
      };
      
      window.addEventListener('wheel', wheelListener, { passive: false });
    }
    
    ScrollTrigger.refresh();
  }
  
  // Initialize on load
  initScrollSnap();
  
  // Debounced resize handler
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      handleViewportChange();
      initScrollSnap();
    }, 250);
  });
}