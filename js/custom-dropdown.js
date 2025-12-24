class CustomDropdown {
    constructor(button, listbox, onSelect) {
        this.button = button;
        this.listbox = listbox;
        this.options = listbox.querySelectorAll('[role="option"]');
        this.onSelect = onSelect;
        
        this.isOpen = false;
        this.currentOptionIndex = 0;
        this.lastTypedChar = '';
        this.lastMatchingIndex = 0;
        
        this.init();
    }
    
    init() {
        this.button.addEventListener('click', () => this.toggleDropdown());
        this.button.addEventListener('keydown', (e) => this.handleKeyPress(e));
        this.listbox.addEventListener('click', (e) => this.handleClick(e));
        
        // Close dropdown when clicking outside
        this.documentClickHandler = (e) => this.handleDocumentClick(e);
        document.addEventListener('click', this.documentClickHandler);
    }
    
    toggleDropdown() {
        this.listbox.classList.toggle('active');
        this.isOpen = !this.isOpen;
        this.button.setAttribute('aria-expanded', this.isOpen.toString());
        
        if (this.isOpen) {
            this.focusCurrentOption();
        } else {
            this.button.focus();
        }
    }
    
    focusCurrentOption() {
        const currentOption = this.options[this.currentOptionIndex];
        currentOption.classList.add('current');
        currentOption.focus();
        currentOption.scrollIntoView({ block: 'nearest' });
        
        this.options.forEach(option => {
            if (option !== currentOption) {
                option.classList.remove('current');
            }
        });
    }
    
    moveFocusDown() {
        if (this.currentOptionIndex < this.options.length - 1) {
            this.currentOptionIndex++;
        } else {
            this.currentOptionIndex = 0;
        }
        this.focusCurrentOption();
    }
    
    moveFocusUp() {
        if (this.currentOptionIndex > 0) {
            this.currentOptionIndex--;
        } else {
            this.currentOptionIndex = this.options.length - 1;
        }
        this.focusCurrentOption();
    }
    
    selectOption(optionElement) {
        const optionValue = optionElement.dataset.value;
        const optionText = optionElement.textContent;

        this.button.querySelector('.filter-button-text').textContent = optionText;
        
        this.options.forEach(option => {
            option.classList.remove('active');
            option.setAttribute('aria-selected', 'false');
        });
        
        optionElement.classList.add('active');
        optionElement.setAttribute('aria-selected', 'true');
        
        this.toggleDropdown();
        
        // Call the callback with the selected value
        if (this.onSelect) {
            this.onSelect(optionValue);
        }
    }
    
    handleAlphanumericKeyPress(key) {
        const typedChar = key.toLowerCase();
        
        if (this.lastTypedChar !== typedChar) {
            this.lastMatchingIndex = 0;
        }
        
        const matchingOptions = Array.from(this.options).filter(option =>
            option.textContent.toLowerCase().startsWith(typedChar)
        );
        
        if (matchingOptions.length) {
            if (this.lastMatchingIndex === matchingOptions.length) {
                this.lastMatchingIndex = 0;
            }
            
            const value = matchingOptions[this.lastMatchingIndex];
            const index = Array.from(this.options).indexOf(value);
            this.currentOptionIndex = index;
            this.focusCurrentOption();
            this.lastMatchingIndex += 1;
        }
        
        this.lastTypedChar = typedChar;
    }
    
    handleKeyPress(event) {
        const { key } = event;
        const openKeys = ['ArrowDown', 'ArrowUp', 'Enter', ' '];
        
        if (!this.isOpen && openKeys.includes(key)) {
            event.preventDefault();
            this.toggleDropdown();
        } else if (this.isOpen) {
            event.preventDefault();
            switch (key) {
                case 'Escape':
                    this.toggleDropdown();
                    break;
                case 'ArrowDown':
                    this.moveFocusDown();
                    break;
                case 'ArrowUp':
                    this.moveFocusUp();
                    break;
                case 'Enter':
                case ' ':
                    this.selectOption(this.options[this.currentOptionIndex]);
                    break;
                default:
                    if (key.length === 1) {
                        this.handleAlphanumericKeyPress(key);
                    }
                    break;
            }
        }
    }
    
    handleClick(event) {
        const clickedOption = event.target.closest('[role="option"]');
        
        if (clickedOption && this.listbox.contains(clickedOption)) {
            this.selectOption(clickedOption);
        }
    }
    
    handleDocumentClick(event) {
        const isClickInsideButton = this.button.contains(event.target);
        const isClickInsideListbox = this.listbox.contains(event.target);
        
        if (!isClickInsideButton && !isClickInsideListbox && this.isOpen) {
            this.toggleDropdown();
        }
    }
    
    destroy() {
        // Clean up event listeners
        document.removeEventListener('click', this.documentClickHandler);
    }
}

export default CustomDropdown;