!function(r){var e={};function n(t){if(e[t])return e[t].exports;var a=e[t]={i:t,l:!1,exports:{}};return r[t].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=r,n.c=e,n.d=function(r,e,t){n.o(r,e)||Object.defineProperty(r,e,{enumerable:!0,get:t})},n.r=function(r){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(r,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(r,"__esModule",{value:!0})},n.t=function(r,e){if(1&e&&(r=n(r)),8&e)return r;if(4&e&&"object"==typeof r&&r&&r.__esModule)return r;var t=Object.create(null);if(n.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:r}),2&e&&"string"!=typeof r)for(var a in r)n.d(t,a,function(e){return r[e]}.bind(null,a));return t},n.n=function(r){var e=r&&r.__esModule?function(){return r.default}:function(){return r};return n.d(e,"a",e),e},n.o=function(r,e){return Object.prototype.hasOwnProperty.call(r,e)},n.p="",n(n.s=3)}({3:function(r,e){function n(r,e,n){return e in r?Object.defineProperty(r,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):r[e]=n,r}$((function(){$("a.enable-ics[data-calendar-id]").on({click:function(){if(!confirm(Craft.t("calendar","Are you sure you want to enable ICS sharing for this calendar?")))return!1;var r=n({calendar_id:$(this).data("calendar-id")},Craft.csrfTokenName,Craft.csrfTokenValue);Craft.postActionRequest("calendar/calendars/enable-ics-sharing",r,(function(r){r.errors?Craft.cp.displayError(r.errors.join(". ")):window.location.reload()}))}}),$("a.copy-ics-link[data-link]").on({click:function(){var r=$(this).data("link"),e=Craft.t("calendar","{ctrl}C to copy.",{ctrl:navigator.appVersion.indexOf("Mac")?"⌘":"Ctrl-"});prompt(e,r)}}),$("a.disable-ics[data-calendar-id]").on({click:function(){if(!confirm(Craft.t("calendar","Are you sure you want to disable ICS sharing for this calendar?")))return!1;var r=n({calendar_id:$(this).data("calendar-id")},Craft.csrfTokenName,Craft.csrfTokenValue);Craft.postActionRequest("calendar/calendars/disable-ics-sharing",r,(function(r){r.errors?Craft.cp.displayError(r.errors.join(". ")):window.location.reload()}))}})}))}});