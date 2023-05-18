/*! get-video-id v3.6.4 | @license MIT © Michael Wuergler | https://github.com/radiovisual/get-video-id */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self).getVideoId=t()}(this,function(){"use strict";function e(e){return e.includes("?")&&(e=e.split("?")[0]),e.includes("/")&&(e=e.split("/")[0]),e.includes("&")&&(e=e.split("&")[0]),e}function t(t){var r=t;r=(r=r.replace(/#t=.*$/,"")).replace(/^https?:\/\//,"");var i=/youtube:\/\/|youtu\.be\/|y2u\.be\//g;if(i.test(r))return e(r.split(i)[1]);var n=/\/shorts\//g;if(n.test(r))return e(r.split(n)[1]);var o=/v=|vi=/g;if(o.test(r))return e(r.split(o)[1].split("&")[0]);var s=/\/v\/|\/vi\/|\/watch\//g;if(s.test(r))return e(r.split(s)[1]);var u=/\/an_webp\//g;if(u.test(r))return e(r.split(u)[1]);var c=/\/e\//g;if(c.test(r))return e(r.split(c)[1]);var a=/\/embed\//g;if(a.test(r))return e(r.split(a)[1]);if(!/\/user\/([a-zA-Z\d]*)$/g.test(r)){if(/\/user\/(?!.*videos)/g.test(r))return e(r.split("/").pop());var l=/\/attribution_link\?.*v%3D([^%&]*)(%26|&|$)/;return l.test(r)?e(r.match(l)[1]):void 0}}function r(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var r=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==r)return;var i,n,o=[],s=!0,u=!1;try{for(r=r.call(e);!(s=(i=r.next()).done)&&(o.push(i.value),!t||o.length!==t);s=!0);}catch(e){u=!0,n=e}finally{try{s||null==r.return||r.return()}finally{if(u)throw n}}return o}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return i(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(e);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return i(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function i(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,i=new Array(t);r<t;r++)i[r]=e[r];return i}function n(e){var t,i,n=e;if(n.includes("#")){var o=n.split("#");n=r(o,1)[0]}if(n.includes("?")&&!n.includes("clip_id=")){var s=n.split("?");n=r(s,1)[0]}var u=/https?:\/\/vimeo\.com\/event\/(\d+)$/.exec(n);if(u&&u[1])return u[1];var c=/https?:\/\/vimeo\.com\/(\d+)/.exec(n);if(c&&c[1])return c[1];var a=["https?://player.vimeo.com/video/[0-9]+$","https?://vimeo.com/channels","groups","album"].join("|");if(new RegExp(a,"gim").test(n))(i=n.split("/"))&&i.length>0&&(t=i.pop());else if(/clip_id=/gim.test(n)){if((i=n.split("clip_id="))&&i.length>0)t=r(i[1].split("&"),1)[0]}return t}function o(e){var t=/https:\/\/vine\.co\/v\/([a-zA-Z\d]*)\/?/.exec(e);if(t&&t.length>1)return t[1]}function s(e){var t;if(e.includes("embed"))return t=/embed\/(\w{8})/,e.match(t)[1];t=/\/v\/(\w{8})/;var r=e.match(t);return r&&r.length>0?r[1]:void 0}function u(e){var t=(e.includes("embed")?/https:\/\/web\.microsoftstream\.com\/embed\/video\/([a-zA-Z\d-]*)\/?/:/https:\/\/web\.microsoftstream\.com\/video\/([a-zA-Z\d-]*)\/?/).exec(e);if(t&&t.length>1)return t[1]}function c(e){var t=/tiktok\.com(.*)\/video\/(\d+)/gm.exec(e);if(t&&t.length>2)return t[2]}function a(e){var t=/dailymotion\.com(.*)(video)\/([a-zA-Z\d]+)/gm.exec(e);if(t)return t[3];var r=/dai\.ly\/([a-zA-Z\d]+)/gm.exec(e);if(r&&r.length>1)return r[1];var i=/dailymotion\.com(.*)video=([a-zA-Z\d]+)/gm.exec(e);return i&&i.length>2?i[2]:void 0}return function(e){if("string"!=typeof e)throw new TypeError("get-video-id expects a string");var r=e;/<iframe/gi.test(r)&&(r=function(e){if("string"!=typeof e)throw new TypeError("getSrc expected a string");var t=/src="(.*?)"/gm.exec(e);if(t&&t.length>=2)return t[1]}(r)),r=(r=(r=r.trim()).replace("-nocookie","")).replace("/www.","/");var i={id:null,service:null};if(/\/\/google/.test(r)){var l=r.match(/url=([^&]+)&/);l&&(r=decodeURIComponent(l[1]))}return/youtube|youtu\.be|y2u\.be|i.ytimg\./.test(r)?i={id:t(r),service:"youtube"}:/vimeo/.test(r)?i={id:n(r),service:"vimeo"}:/vine/.test(r)?i={id:o(r),service:"vine"}:/videopress/.test(r)?i={id:s(r),service:"videopress"}:/microsoftstream/.test(r)?i={id:u(r),service:"microsoftstream"}:/tiktok\.com/.test(r)?i={id:c(r),service:"tiktok"}:/(dailymotion\.com|dai\.ly)/.test(r)&&(i={id:a(r),service:"dailymotion"}),i}});
//# sourceMappingURL=get-video-id.min.js.map