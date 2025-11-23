// import CustomDropdown from './custom-dropdown';

class GalleryItem {
    constructor(data) {
        this.id = data.id;
        this.title = data.title.rendered;
        this.link = data.link;
        this.content = data.content.rendered;
        this.featuredImage = this.extractFeaturedImage(data);
        this.projectTypes = this.getTermsByTaxonomy(data, 'project_type');
        this.projectSubjects = this.getTermsByTaxonomy(data, 'project_subject');
        this.projectStages = this.getTermsByTaxonomy(data, 'project_stage');
        this.projectMedia = this.getTermsByTaxonomy(data, 'project_media');
    }
    
    extractFeaturedImage(data) {
        const featuredMedia = data._embedded?.['wp:featuredmedia']?.[0];
        
        if (!featuredMedia) {
            return null;
        }
        
        const sizes = featuredMedia.media_details?.sizes;
        if (sizes?.thumbnail?.source_url) {
            return sizes.thumbnail.source_url;
        }
        
        return sizes?.full?.source_url || featuredMedia.source_url || null;
    }
    
    getTermsByTaxonomy(data, taxonomyName) {
        const allTerms = data._embedded?.['wp:term'] || [];
        
        for (let i = 0; i < allTerms.length; i++) {
            const termGroup = allTerms[i];
            
            if (termGroup.length > 0 && termGroup[0].taxonomy === taxonomyName) {
                return termGroup.map(term => ({
                    id: term.id,
                    name: term.name,
                    slug: term.slug,
                    link: term.link
                }));
            }
        }
        
        // Return empty array if taxonomy not found
        return [];
    }
    
    getTermsString(taxonomyName, separator = ', ') {
        const terms = this[taxonomyName] || [];
        return terms.map(term => term.name).join(separator);
    }
}

class GalleryFeed {
    constructor() {
        this.galleryItems = [];
        this.filteredGalleryItems = [];
        this.filters = {
            projectTypes: 'all',
            projectSubjects: 'all',
            projectStages: 'all',
            projectMedia: 'all'
        };
        this.dropdowns = [];
        this.init();
    }

    async init() {
        await this.loadGalleryItems();
        this.buildFilters();
        this.displayGalleryItems();
    }

    async loadGalleryItems() {
        try {
            const data = await fetch('/wp-json/wp/v2/projects?_embed').then(response => response.json());
            this.galleryItems = data.map(item => new GalleryItem(item));
            this.filteredGalleryItems = [...this.galleryItems];
        } catch (error) {
            console.error(`Something went wrong: ${error}`);
        }
    }

    displayGalleryItems() {
        const galleryFeed = document.querySelector('#gallery-feed');

        // Error handling
        if(this.filteredGalleryItems.length < 1) {
            galleryFeed.innerHTML = '<div class="no-contacts">No contacts found</div>';
            return;
        }

        const galleryFeedHTML = this.filteredGalleryItems.map(
            item => this.renderGalleryItem(item)
        ).join('');
        galleryFeed.innerHTML = `<div class="feed-grid">${galleryFeedHTML}</div>`;
    }

    renderGalleryItem(item) {
        const {title, link, featuredImage} = item;

        const projectTagsParts = [
            item.projectTypes?.map(term => term.name).join(', '),
            item.projectSubjects?.map(term => term.name).join(', '),
            item.projectStages?.map(term => term.name).join(', '),
            item.projectMedia?.map(term => term.name).join(', ')
        ].filter(Boolean);
        const projectTagsString = projectTagsParts.join(', ');

        const galleryCard = `<div class="gallery-item">
            <a href="${link}" class="gallery-item-link">
                <div class="gallery-item-image" style="background-image:url(${featuredImage})"></div>
                <div class="gallery-item-text">
                    <h2 class="gallery-item-heading">${title}</h2>
                    <p class="gallery-item-tags">${projectTagsString}</p>
                </div>
            </a>
        </div>`;
        return galleryCard;
    }

    getAllTermsByTaxonomy(taxonomyProperty) {
        const termsMap = new Map();
        
        this.galleryItems.forEach(item => {
            item[taxonomyProperty]?.forEach(term => {
                if (!termsMap.has(term.id)) {
                    termsMap.set(term.id, term);
                }
            });
        });
        
        return Array.from(termsMap.values()).sort((a, b) => 
            a.name.localeCompare(b.name)
        );
    }

    buildFilters() {
        const filterContainer = document.querySelector('#gallery-filters');
        if (!filterContainer) return;

        const taxonomies = [
            { property: 'projectTypes', label: 'Types' },
            { property: 'projectSubjects', label: 'Subjects' },
            { property: 'projectStages', label: 'Stages' },
            { property: 'projectMedia', label: 'Media' }
        ];

        const filtersHTML = taxonomies.map(taxonomy => {
            const terms = this.getAllTermsByTaxonomy(taxonomy.property);
            
            const optionsHTML = [
                `<li role="option" data-value="all">All ${taxonomy.label}</li>`,
                ...terms.map(term => 
                    `<li role="option" data-value="${term.slug}">${term.name}</li>`
                )
            ].join('');

            return `
                <div class="filter-group filter-group--${taxonomy.property}">
                    <label for="filter-${taxonomy.property}" class="filter-label">
                        ${taxonomy.label}
                    </label>
                    <button
                        role="combobox"
                        id="filter-${taxonomy.property}"
                        class="filter-button"
                        data-taxonomy="${taxonomy.property}"
                        aria-controls="listbox-${taxonomy.property}"
                        aria-haspopup="listbox"
                        aria-expanded="false"
                        tabindex="0"
                    >
                        <span class="filter-button-text">All ${taxonomy.label}</span>
                        <span class="icon i-chevron down"></span>
                    </button>
                    <ul 
                        role="listbox" 
                        id="listbox-${taxonomy.property}"
                        class="filter-listbox"
                    >
                        ${optionsHTML}
                    </ul>
                </div>
            `;
        }).join('');

        filterContainer.innerHTML = `<div class="filter-container">${filtersHTML}</div>`;
        
        // Initialize dropdowns
        this.initializeDropdowns(taxonomies);
    }

    initializeDropdowns(taxonomies) {
        taxonomies.forEach(taxonomy => {
            const button = document.getElementById(`filter-${taxonomy.property}`);
            const listbox = document.getElementById(`listbox-${taxonomy.property}`);
            
            const dropdown = new CustomDropdown(
                button,
                listbox,
                (value) => {
                    this.filters[taxonomy.property] = value;
                    this.applyFilters();
                }
            );
            
            this.dropdowns.push(dropdown);
        });
    }

    applyFilters() {
        this.filteredGalleryItems = this.galleryItems.filter(item => {
            for (let [taxonomyProperty, filterValue] of Object.entries(this.filters)) {
                if (filterValue === 'all') continue;
                
                const hasMatch = item[taxonomyProperty]?.some(
                    term => term.slug === filterValue
                );
                
                if (!hasMatch) return false;
            }
            
            return true;
        });

        this.displayGalleryItems();
    }

    destroy() {
        // Clean up all dropdowns
        this.dropdowns.forEach(dropdown => dropdown.destroy());
    }
}

const galleryFeed = new GalleryFeed();
        
window.galleryFeed = galleryFeed;