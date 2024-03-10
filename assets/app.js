/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/button.css';
import './styles/padding.css';
import './styles/size.css';

import './stimulus';

const $ = require('jquery');

global.$ = global.jQuery = $;