/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte/dist/js/adminlte.js');

window.Vue = require('vue');

//to use lodash in templates
Vue.prototype._ = _;

// ladda.js
import * as Ladda from 'ladda';

//accounting.js for currency formatting
import accounting from 'accounting-js';
window.__formatCurrency = function(money){
    var currency_symbol = APP.CURRENCY_SYMBOL+' ';
    var formatted_currency = accounting.formatMoney(money, { symbol: currency_symbol});
    return formatted_currency;
}

window.__convert_currency_in_datatable = function (elements) {
    elements.find('.currency').each(function(){
        var money = $(this).text();
        var formatted_currency = __formatCurrency(money);
        $(this).text(formatted_currency);
    });
}
/**
 * global event bus for communication between
 * two different component
 */
Vue.prototype.$eventBus = new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
window.validationRules = [
    {'rule' : 'email', 'display': 'Email', 'applies_to':['text']},
    {'rule' : 'url', 'display': 'URL', 'applies_to':['text']},
    {'rule' : 'minlength', 'display': 'Minlength', 'applies_to':['text', 'dropdown', 'checkbox', 'textarea']},
    {'rule' : 'maxlength', 'display': 'Maxlength', 'applies_to':['text', 'dropdown', 'checkbox', 'textarea']},
    {'rule' : 'digits', 'display': 'Digits', 'applies_to':['text']},
    {'rule' : 'number', 'display': 'Number', 'applies_to':['text']},
    {'rule' : 'alphanumeric', 'display': 'Alphanumeric', 'applies_to':['text']},
    {'rule' : 'lettersonly', 'display': 'Letters Only', 'applies_to':['text']},
    {'rule' : 'phone', 'display': 'Phone', 'applies_to':['text']},
    {'rule' : 'phoneus', 'display': 'Phone US', 'applies_to':['text']},
    {'rule' : 'creditcard', 'display': 'Credit Card', 'applies_to':['text']},
];
// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
Vue.component('create-form', require('./components/CreateForm.vue').default);
Vue.component('show-form', require('./components/ShowForm.vue').default);
Vue.component('tool-tip', require('./components/TooltipComponent.vue').default);

//common function
import functions from './functions';
Vue.mixin(functions);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

$('body').tooltip({
    selector: '[data-toggle="tooltip"]'
});

const app = new Vue({
    el: '#app',
});