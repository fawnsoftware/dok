
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';
import Precognition from 'laravel-precognition-alpine';

import drawer from './components/drawer.js';
import dropdown from './components/dropdown.js';
import outline from './components/outline.js';
import precognitionForm from './components/precognitionForm.js';
import copyCode from './components/copyCode.js';
import search from './components/search.js';

import { truncate, mark } from './helpers/string.js';
import { inline } from './helpers/json.js';

import theme from './stores/theme.js';
import dialogs from './stores/dialogs.js';

const components = [
    drawer,
    dropdown,
    outline,
    precognitionForm,
    copyCode,
    search
];

const stores = [
    theme,
    dialogs
];


Alpine.plugin(focus);
Alpine.plugin(anchor);
Alpine.plugin(collapse)
Alpine.plugin(Precognition)

stores.forEach(store => store(Alpine));

components.forEach(component => component(Alpine));


Alpine.magic('str', () => ({ mark, truncate }));
Alpine.magic('json', () => ({ inline }));


window.Alpine = Alpine;
