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
        initialZoomLevel: 'fit',
        secondaryZoomLevel: 2,
        maxZoomLevel: 3,
        
        showHideAnimationType: 'zoom'
    });
    
    lightbox.init();
});