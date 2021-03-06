/*!
 * Float Labels
 * @version: 3.3.5
 * @author: Paul Ryley (http://geminilabs.io)
 * @url: https://geminilabs.github.io/float-labels.js
 * @license: MIT
 */
!function(t,r,e){"use strict";var n=function(t,e){this.t=this.e(t)?r.querySelectorAll(t):[t],this.n=[],this.i=e,this.r=[],this.o(),this.destroy=function(){this.u(function(t){t.removeEventListener("reset",this.events.reset),this.s(t)},function(t){this.a(t)})},this.rebuild=function(){this.u(null,function(t){this.l(t,!0)})}};n.prototype={c:{customEvent:null,customLabel:null,customPlaceholder:null,exclude:".no-label",inputRegex:/email|number|password|search|tel|text|url/,prefix:"fl-",prioritize:"label",requiredClass:"required",style:0,transform:"input,select,textarea"},o:function(){var i=this;i.f(),i.u(function(t,e){var n=i.n[e].style;t.addEventListener("reset",i.events.reset),t.classList.add(i.h("form")),n&&t.classList.add(i.h("style-"+n))},function(t){i.l(t)})},f:function(){var t=this;t.events={blur:t.d.bind(t),change:t.v.bind(t),focus:t.p.bind(t),input:t.v.bind(t),reset:t._.bind(t)}},b:function(t){return t?"add":"remove"},m:function(t){var e=this,n=e.y(t);n&&(t.classList.add(e.h(t.tagName.toLowerCase())),e.L(n,t),e.g(n,t),e.w(n,t),e.x(t,"add"),"function"==typeof e.n[e.S].customEvent&&e.n[e.S].customEvent.call(e,t))},T:function(t,e){var n="string"==typeof t?r.createElement(t):t;for(var i in e=e||{})e.hasOwnProperty(i)&&n.setAttribute(i,e[i]);return n},C:function(){var t=[].slice.call(arguments),n=t[0],i=t.slice(1);return Object.keys(i).forEach(function(t){for(var e in i[t])i[t].hasOwnProperty(e)&&(n[e]=i[t][e])}),n},l:function(t,e){var n=this;if(n.P(t)){if(n.j(t)){if(!0!==e)return;n.a(t)}n.m(t)}},y:function(t){var e='label[for="'+t.getAttribute("id")+'"]',n=this.t[this.S].querySelectorAll(e);return 1<n.length&&(n=t.parentNode.querySelectorAll(e)),1===n.length&&n[0]},q:function(t,e){var n=t.textContent.replace(/[*:]/g,"").trim(),i=e.getAttribute("placeholder");return(!n||n&&i&&"placeholder"===this.n[this.S].prioritize)&&(n=i),n},x:function(e,n){var i=this.events;["blur","input","focus"].forEach(function(t){"input"!==t||"file"!==e.type&&"SELECT"!==e.nodeName||(t="change"),e[n+"EventListener"](t,i[t])})},j:function(t){return t.parentNode.classList.contains(this.h("wrap"))},e:function(t){return"[object String]"===Object.prototype.toString.call(t)},P:function(t){var e="INPUT"===t.tagName&&!this.n[this.S].inputRegex.test(t.getAttribute("type")),n="SELECT"===t.tagName&&null!==t.getAttribute("multiple");return t.getAttribute("id")&&!e&&!n},u:function(t,e){for(var n=this,i=0;i<n.t.length;++i){if(void 0===n.r[i]){var r=n.C({},n.c,n.i,n.t[i].getAttribute("data-options")),o=":not("+r.exclude.split(/[\s,]+/).join("):not(")+")";n.r[i]=r.transform.replace(/,/g,o+",")+o,n.n[i]=r}var u=n.t[i].querySelectorAll(n.r[i]);n.S=i,"function"==typeof t&&t.call(n,n.t[i],i);for(var s=0;s<u.length;++s)"function"==typeof e&&e.call(n,u[s],i)}},d:function(t){t.target.parentNode.classList.remove(this.h("has-focus"))},v:function(t){t.target.parentNode.classList[this.b(t.target.value.length)](this.h("is-active"))},p:function(t){t.target.parentNode.classList.add(this.h("has-focus"))},_:function(){setTimeout(this.F.bind(this))},h:function(t){return this.n[this.S].prefix+t},s:function(t){var e=this.n[this.S].prefix,n=t.className.split(" ").filter(function(t){return 0!==t.lastIndexOf(e,0)});t.className=n.join(" ").trim()},a:function(t){var e=t.parentNode;if(this.j(t)){for(var n=r.createDocumentFragment();e.firstElementChild;){var i=e.firstElementChild;this.s(i),n.appendChild(i)}e.parentNode.replaceChild(n,e),this.O(t),this.x(t,"remove")}},F:function(){for(var t=this,e=t.t[t.S].querySelectorAll(t.r[t.S]),n=0;n<e.length;++n)e[n].parentNode.classList[t.b("SELECT"===e[n].tagName&&""!==e[n].value)](t.h("is-active"))},O:function(t){var e="data-placeholder",n=t.getAttribute(e);null!==n&&(t.removeAttribute(e),t.setAttribute("placeholder",n))},L:function(t,e){var n=this;t.classList.add(n.h("label")),t.textContent=n.q(t,e),"function"==typeof n.n[n.S].customLabel&&(t.textContent=n.n[n.S].customLabel.call(n,t,e))},g:function(t,e){var n=this,i=e.getAttribute("placeholder");"label"!==n.n[n.S].prioritize&&i||(i&&e.setAttribute("data-placeholder",i),i=n.q(t,e)),"function"==typeof n.n[n.S].customPlaceholder&&(i=n.n[n.S].customPlaceholder.call(n,i,e,t)),"SELECT"===e.tagName?n.R(e,i):e.setAttribute("placeholder",i)},R:function(t,e){var n=t.firstElementChild;n.hasAttribute("value")&&n.value?(t.insertBefore(new Option(e,""),n),!1===t.options[t.selectedIndex].defaultSelected&&(t.selectedIndex=0)):n.setAttribute("value",""),""===n.textContent&&(n.textContent=e)},w:function(t,e){var n=this,i=n.T("div",{class:n.h("wrap")+" "+n.h("wrap-"+e.tagName.toLowerCase())});void 0!==e.value&&e.value.length&&i.classList.add(n.h("is-active")),(null!==e.getAttribute("required")||e.classList.contains(n.n[n.S].requiredClass))&&i.classList.add(n.h("is-required")),e.parentNode.insertBefore(i,e),i.appendChild(t),i.appendChild(e)}},"function"==typeof define&&define.amd?define([],function(){return n}):"object"==typeof module&&module.exports?module.exports=n:t.FloatLabels=n}(window,document);

var floatlabels = new FloatLabels('.fl-labels', {
  prioritize: 'placeholder',
  /**
   * Function runs immediately after an element has been transformed.
   */
  customEvent: function (el) {
    // wc_address_i18n_params is required to continue, ensure the object exists
    if (typeof wc_address_i18n_params === 'undefined') {
      return
    }

    let field = el.id !== 'undefined' ? document.getElementById(el.id + '_field') : null

    // Insert label required abbreviation if not exist yet.
    if (field !== null && field.classList.contains('validate-required')) {
      if (field.querySelector('label .required') === null) {
        field.querySelector('label').insertAdjacentHTML('beforeend', '&nbsp;<abbr class="required" title="' + wc_address_i18n_params.i18n_required_text + '">*</abbr>')
      }
    }
  }
})
;