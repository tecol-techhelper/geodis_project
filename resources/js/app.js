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

function initStatusSelects(root = document) {
    const selects = root.querySelectorAll('select.js-status-select');
    selects.forEach((select) => {
        if (select.tomselect) {
            return;
        }

        const placeholder = select.dataset.placeholder || 'Seleccione una opción';

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
        });

        // Asegurar valor inicial (Livewire puede hidratar después)
        const currentValue = select.value;
        if (currentValue !== '' && currentValue !== null) {
            instance.setValue(currentValue, true);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => initStatusSelects());
document.addEventListener('livewire:navigated', () => initStatusSelects());

if (window.Livewire && typeof window.Livewire.hook === 'function') {
    window.Livewire.hook('message.processed', () => initStatusSelects());
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
}
