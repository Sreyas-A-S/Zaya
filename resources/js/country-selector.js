// Country Selector using Tom Select
import TomSelect from 'tom-select';
import countries from './countries';

export function initCountrySelector(selector = '#nationality-select', defaultValue = 'IN') {
    const element = document.querySelector(selector);
    if (!element) return null;

    const tomSelect = new TomSelect(element, {
        options: countries.map(country => ({
            value: country.code,
            text: country.name,
            code: country.code.toLowerCase() // For flag-icons CSS class
        })),
        valueField: 'value',
        labelField: 'text',
        searchField: ['text'],
        maxItems: 1,
        create: false,
        placeholder: 'Select Country',
        render: {
            option: function (data, escape) {
                return `<div class="country-option">
                    <span class="fi fi-${data.code} country-option-flag"></span>
                    <span class="country-option-name">${escape(data.text)}</span>
                </div>`;
            },
            item: function (data, escape) {
                return `<div class="country-item">
                    <span class="fi fi-${data.code} country-item-flag"></span>
                    <i class="ri-arrow-down-s-line country-item-arrow"></i>
                    <span class="country-item-name">${escape(data.text)}</span>
                </div>`;
            }
        }
    });

    // Set default value
    if (defaultValue) {
        tomSelect.setValue(defaultValue);
    }

    return tomSelect;
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function () {
    // Check if nationality select exists on the page
    const nationalitySelect = document.querySelector('#nationality-select');
    if (nationalitySelect) {
        // Get default value from data attribute or use India
        const defaultValue = nationalitySelect.dataset.default || 'IN';
        initCountrySelector('#nationality-select', defaultValue);
    }

    // Auto-initialize Education Country Selectors
    const educationSelects = document.querySelectorAll('.education-country-select');
    educationSelects.forEach(select => {
        const id = select.id;
        const defaultValue = select.dataset.default || '';
        if (id) {
            initCountrySelector('#' + id, defaultValue);
        }
    });
});
