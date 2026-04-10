import './bootstrap';
import { createIcons, icons } from 'lucide';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
createIcons({icons});

import '../css/bell.css';
import '../css/404.css';
import '../css/403.css';

import flatpickr from "flatpickr"; 

import 'flatpickr/dist/flatpickr.min.css';

import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

function initStatusSelects(root = document, options = {}) {
    const { force = false } = options;
    const selects = root.querySelectorAll('select.js-status-select');
    selects.forEach((select) => {
        const isIgnored = !!select.closest('[wire\\:ignore]');
        if (select.tomselect) {
            if (!force || isIgnored) {
                return;
            }
            try {
                select.tomselect.destroy();
            } catch (e) {
                // if destroy fails, continue to re-init anyway
            }
        }

        const placeholder = Object.prototype.hasOwnProperty.call(select.dataset, 'placeholder')
            ? select.dataset.placeholder
            : 'Seleccione una opción';

        const instance = new TomSelect(select, {
            create: false,
            maxItems: 1,
            placeholder,
            allowEmptyOption: true,
            plugins: ['dropdown_input'],
            sortField: {
                field: 'text',
                direction: 'asc',
            },
            render: {
                option(data, escape) {
                    return `<div>${escape(data.text)}</div>`;
                },
                item(data, escape) {
                    return `<div>${escape(data.text)}</div>`;
                },
            },
            onChange(value) {
                const model = select.dataset.livewireModel;
                if (!model || !window.Livewire) return;
                const component = select.closest('[wire\\:id]');
                if (!component) return;
                const componentId = component.getAttribute('wire:id');
                const livewire = window.Livewire.find(componentId);
                if (livewire) {
                    select.dataset.currentValue = value;
                    livewire.set(model, value === '' ? null : value);
                }
            },
        });

        // Asegurar valor inicial (Livewire puede hidratar después)
        const datasetValue = select.dataset.currentValue;
        const currentValue = (datasetValue !== undefined && datasetValue !== null && datasetValue !== '')
            ? datasetValue
            : select.value;
        if (currentValue !== '' && currentValue !== null && currentValue !== undefined) {
            instance.setValue(currentValue, true);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => initStatusSelects());
document.addEventListener('livewire:navigated', () => initStatusSelects(document, { force: true }));

if (window.Livewire && typeof window.Livewire.hook === 'function') {
    // Livewire v2 compatibility
    window.Livewire.hook('message.processed', () => initStatusSelects(document, { force: true }));
    // Livewire v3
    window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => initStatusSelects(document, { force: true }));
    });
}

function normalizeUnknownText(root = document) {
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
    const toReplace = ['UNKNOWN', 'UNKNOW', 'N/A', 'NA', 'NULL'];

    let node;
    while ((node = walker.nextNode())) {
        const raw = node.textContent ?? '';
        const trimmed = raw.trim();
        if (toReplace.includes(trimmed)) {
            node.textContent = raw.replace(trimmed, '-');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => normalizeUnknownText());
document.addEventListener('livewire:navigated', () => normalizeUnknownText());

if (window.Livewire && typeof window.Livewire.hook === 'function') {
    window.Livewire.hook('message.processed', () => normalizeUnknownText());
    window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => normalizeUnknownText());
    });
}

// Fallback: observe DOM changes to replace UNKNOWN values after any re-render
if (typeof MutationObserver !== 'undefined') {
    let scheduled = false;
    const observer = new MutationObserver(() => {
        if (scheduled) return;
        scheduled = true;
        requestAnimationFrame(() => {
            scheduled = false;
            normalizeUnknownText();
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (document.body) {
            observer.observe(document.body, { childList: true, subtree: true });
        }
    });
}
