(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/OctopusViewer/asset/js/octopusviewer-media-info.js');
    const baseUrl = src.substring(0, baseUrlEnd) + '/';

    class OctopusViewerMediaInfo extends HTMLElement {
        static get observedAttributes() {
            return ['media-id'];
        }

        #loadInfo () {
            const mediaInfoUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${this.mediaId}/info`, baseUrl);

            fetch(mediaInfoUrl).then(response => {
                return response.json();
            }).then(data => {
                this.innerHTML = data.content;
            }, err => {
                console.error(err);
                this.innerHTML = '';
            });
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (oldValue === newValue) return;

            if (name === 'media-id') {
                this.#loadInfo();
            }
        }

        get mediaId () {
            return this.getAttribute('media-id');
        }

        set mediaId (mediaId) {
            this.setAttribute('media-id', mediaId);
        }

        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (slug) {
            this.setAttribute('site-slug', slug);
        }
    }

    window.customElements.define('octopusviewer-media-info', OctopusViewerMediaInfo);
})();
