
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';
import Precognition from 'laravel-precognition-alpine';

import drawer from './components/drawer.js';
import dropdown from './components/dropdown.js';
import outline from './components/outline.js';
import themePicker from './components/themePicker.js';
import precognitionForm from './components/precognitionForm.js';
import copyCode from './components/copyCode.js';

const components = [
    drawer,
    dropdown,
    outline,
    themePicker,
    precognitionForm,
    copyCode
];


Alpine.plugin(focus);
Alpine.plugin(anchor);
Alpine.plugin(collapse)
Alpine.plugin(Precognition)

components.forEach(component => component(Alpine));

window.Alpine = Alpine;
