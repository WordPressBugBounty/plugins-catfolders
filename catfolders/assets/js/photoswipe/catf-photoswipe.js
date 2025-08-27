var catfLightbox={template:`
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--share" title="Share"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
    </div>`,createGallery:function(t){document.getElementsByClassName("pswp").length||document.body.insertAdjacentHTML("beforeend",catfLightbox.template),catfLightbox.initPhotoSwipeFromDOM(t)},parseThumbnailElements:function(t){for(var e,i,s,o=t.childNodes,n=o.length,a=[],l=0;l<n;l++)i=(e=o[l]).querySelector("img"),s=(e=e.querySelector("figure")).querySelector("figcaption")||document.createElement("figcaption"),s={src:i.getAttribute("src"),w:parseInt(i.getAttribute("width"),10),h:parseInt(i.getAttribute("height"),10),title:i.getAttribute("alt")+' <div class="catf-gallery-caption">'+s.innerHTML+"</div>",msrc:i.getAttribute("src"),el:e},a.push(s);return a},openPhotoSwipe:function(t,e,i,s){var o=document.querySelectorAll(".pswp")[0],n=catfLightbox.parseThumbnailElements(e),a={galleryUID:e.getAttribute("data-pswp-uid"),getThumbBoundsFn:function(t){var t=n[t].el.getElementsByTagName("img")[0],e=window.pageYOffset||document.documentElement.scrollTop,t=t.getBoundingClientRect();return{x:t.left,y:t.top+e,w:t.width}}};if(s)if(a.galleryPIDs){for(var l=0;l<n.length;l++)if(n[l].pid==t){a.index=l;break}}else a.index=parseInt(t,10)-1;else a.index=parseInt(t,10);isNaN(a.index)||(i&&(a.showAnimationDuration=0),new PhotoSwipe(o,PhotoSwipeUI_Default,n,a).init())},onThumbnailsClick:function(t){(t=t||window.event).preventDefault?t.preventDefault():t.returnValue=!1;var e=(t.target||t.srcElement).closest(".catf-blocks-gallery-item");if(e){for(var i,t=e.parentNode,s=e.parentNode.childNodes,o=s.length,n=0,a=0;a<o;a++)if(1===s[a].nodeType){if(s[a]===e){i=n;break}n++}return 0<=i&&catfLightbox.openPhotoSwipe(i,t),!1}},initPhotoSwipeFromDOM:function(t){for(var e=document.querySelectorAll(t),i=0,s=e.length;i<s;i++)e[i].setAttribute("data-pswp-uid",i+1),e[i].onclick=catfLightbox.onThumbnailsClick}};document.addEventListener("DOMContentLoaded",function(){catfLightbox.createGallery(".catf-gallery-lightbox")});