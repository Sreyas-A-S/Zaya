// Country Selector using Tom Select
import TomSelect from 'tom-select';
import countries from './countries';

export function initCountrySelector(selector = '#nationality-select', defaultValue = 'IN') {
    const element = document.querySelector(selector);
    if (!element) return null;

    const tomSelect = new TomSelect(element, {
        options: countries.map(country => ({
            value: country.code, // store ISO code to prevent arbitrary text
            text: country.name,
            code: country.code.toLowerCase() // For flag-icons CSS class
        })),
        valueField: 'value',
        labelField: 'text',
        searchField: ['text', 'value'],
        maxItems: 1,
        maxOptions: 300,
        create: false,
        persist: false,
        closeAfterSelect: true,
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
        let targetValue = defaultValue.toUpperCase();
        const matchingCountry = countries.find(c => c.code === targetValue || c.name === defaultValue);
        if (matchingCountry) {
            targetValue = matchingCountry.code;
        }
        tomSelect.setValue(targetValue, true);
    }

    return tomSelect;
}

window.initCountrySelector = initCountrySelector;

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function () {
    // Check if nationality select exists on the page
    const nationalitySelect = document.querySelector('#nationality-select');
    if (nationalitySelect) {
        const defaultValue = nationalitySelect.dataset.default || 'IN';
        initCountrySelector('#nationality-select', defaultValue);
    }

    // Check if country-select exists on the page (practitioner register form)
    const countrySelect = document.querySelector('#country-select');
    if (countrySelect) {
        const defaultValue = countrySelect.dataset.default || 'IN';
        initCountrySelector('#country-select', defaultValue);
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
