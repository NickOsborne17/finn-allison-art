// Add this to your theme's JavaScript file or create a custom plugin

(function() {
  'use strict';

  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {
    initImageZoom();
  });

  function initImageZoom() {
    // Find all WordPress block images with lightbox enabled
    const images = document.querySelectorAll('.wp-block-image img[data-id]');
    
    images.forEach(function(img) {
      // Check if parent has the lightbox behaviour
      const figure = img.closest('figure');
      if (!figure) return;
      
      // Override the default lightbox click
      img.addEventListener('click', function(e) {
        // Check if image has lightbox enabled (Gutenberg adds specific classes)
        if (figure.classList.contains('is-style-default') || 
            img.hasAttribute('data-behaviors') ||
            figure.querySelector('[data-behaviors*="lightbox"]')) {
          e.preventDefault();
          e.stopPropagation();
          openZoomModal(img);
        }
      });
    });
  }

  function openZoomModal(img) {
    // Create modal overlay
    const modal = document.createElement('div');
    modal.className = 'image-zoom-modal';
    modal.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.95);
      z-index: 999999;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: zoom-out;
    `;

    // Create image container
    const container = document.createElement('div');
    container.className = 'zoom-image-container';
    container.style.cssText = `
      position: relative;
      max-width: 90%;
      max-height: 90%;
      overflow: auto;
      cursor: zoom-in;
    `;

    // Clone the image
    const zoomedImg = document.createElement('img');
    zoomedImg.src = img.src;
    zoomedImg.srcset = img.srcset || '';
    zoomedImg.style.cssText = `
      max-width: 100%;
      max-height: 90vh;
      display: block;
      transition: transform 0.3s ease;
      transform-origin: center center;
    `;

    let scale = 1;
    let panning = false;
    let pointX = 0;
    let pointY = 0;
    let start = { x: 0, y: 0 };

    // Zoom on image click
    container.addEventListener('click', function(e) {
      e.stopPropagation();
      
      if (scale === 1) {
        scale = 2.5;
        container.style.cursor = 'zoom-out';
        
        // Zoom towards click point
        const rect = zoomedImg.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;
        
        pointX = (50 - x * 100) * (scale - 1);
        pointY = (50 - y * 100) * (scale - 1);
        
        zoomedImg.style.transform = `scale(${scale}) translate(${pointX}%, ${pointY}%)`;
      } else {
        scale = 1;
        pointX = 0;
        pointY = 0;
        container.style.cursor = 'zoom-in';
        zoomedImg.style.transform = 'scale(1) translate(0, 0)';
      }
    });

    // Mouse wheel zoom
    container.addEventListener('wheel', function(e) {
      e.preventDefault();
      
      const delta = e.deltaY > 0 ? 0.9 : 1.1;
      scale *= delta;
      scale = Math.min(Math.max(1, scale), 4);
      
      if (scale === 1) {
        pointX = 0;
        pointY = 0;
        container.style.cursor = 'zoom-in';
      } else {
        container.style.cursor = 'move';
      }
      
      zoomedImg.style.transform = `scale(${scale}) translate(${pointX}%, ${pointY}%)`;
    });

    // Panning when zoomed
    container.addEventListener('mousedown', function(e) {
      if (scale > 1) {
        e.preventDefault();
        panning = true;
        start = { x: e.clientX - pointX, y: e.clientY - pointY };
        container.style.cursor = 'grabbing';
      }
    });

    document.addEventListener('mousemove', function(e) {
      if (!panning) return;
      e.preventDefault();
      pointX = (e.clientX - start.x);
      pointY = (e.clientY - start.y);
      zoomedImg.style.transform = `scale(${scale}) translate(${pointX}px, ${pointY}px)`;
      zoomedImg.style.transformOrigin = '0 0';
    });

    document.addEventListener('mouseup', function() {
      if (panning) {
        panning = false;
        container.style.cursor = scale > 1 ? 'move' : 'zoom-in';
      }
    });

    // Close modal on background click
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        document.body.style.overflow = '';
        modal.remove();
      }
    });

    // Close on Escape key
    const escapeHandler = function(e) {
      if (e.key === 'Escape') {
        document.body.style.overflow = '';
        modal.remove();
        document.removeEventListener('keydown', escapeHandler);
      }
    };
    document.addEventListener('keydown', escapeHandler);

    // Assemble and show modal
    container.appendChild(zoomedImg);
    modal.appendChild(container);
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
  }
})();