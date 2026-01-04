import PhotoSwipeLightbox from 'photoswipe/lightbox';
import PhotoSwipe from 'photoswipe';
import 'photoswipe/style.css';

document.addEventListener('DOMContentLoaded', function() {
    const lightbox = new PhotoSwipeLightbox({
        gallery: '.wp-block-image',
        children: 'a',
        pswpModule: PhotoSwipe,
        
        bgOpacity: 1,
        
        zoom: true,
        wheelToZoom: true,
        initialZoomLevel: 'fit',
        // secondaryZoomLevel: 1,
        maxZoomLevel: 1,
        
        showHideAnimationType: 'zoom'
    });
    
    lightbox.init();
});