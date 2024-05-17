// import './bootstrap';
import '@material/web/button/filled-button.js';
import '@material/web/button/outlined-button.js';
import '@material/web/checkbox/checkbox.js';
import {MDCRipple} from '@material/ripple';
import {MDCTabBar} from '@material/tab-bar';
import {MDCSelect} from '@material/select';
import {MDCSlider} from '@material/slider';
import {MDCSwitch} from '@material/switch';
import Alpine from 'alpinejs'
import Clipboard from '@ryangjchandler/alpine-clipboard'
import {MDCDialog} from '@material/dialog';

Alpine.plugin(Clipboard)
Alpine.start()

const tabBar = new MDCTabBar(document.querySelector('.mdc-tab-bar'));

import {MDCTextField} from '@material/textfield';

const textFields = document.querySelectorAll('.mdc-text-field');
textFields.forEach(element => {
    new MDCTextField(element);
});

const selector = '.mdc-button, .mdc-icon-button, .mdc-card__primary-action';
const ripples = [].map.call(document.querySelectorAll(selector), function (el) {
    return new MDCRipple(el);
});

function initSlider (element, callback){
    const slider = new MDCSlider(element);
    slider.listen('MDCSlider:change', callback);
    return slider;
}
window.initSlider = initSlider;

window.MDCSelect = MDCSelect;
window.MDCTextField = MDCTextField;

function initDialog (element){
    return new MDCDialog(element);
}
window.initDialog = initDialog;

function initSwitch (element){
    return new MDCSwitch(element);
}
window.initSwitch = initSwitch;

