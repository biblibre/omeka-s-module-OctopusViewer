(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/OctopusViewer/asset/js/octopusviewer-media-selector.js');
    const baseUrl = src.substring(0, baseUrlEnd) + '/';

    class OctopusViewerMediaSelector extends HTMLElement {
        static get observedAttributes() {
            return ['media-id'];
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (name === 'media-id') {
                const mediaId = newValue;
                this._markMediaAsSelected(mediaId);
            }
        }

        connectedCallback () {
            this.fetchMediaSelector().then(mediaSelectorHTML => {
                const mediaSelectorContainer = document.createElement('div');
                mediaSelectorContainer.innerHTML = mediaSelectorHTML;

                const allMedia = mediaSelectorContainer.querySelectorAll('.octopusviewer-media-selector-element');
                if (allMedia.length === 0) {
                    this.remove();
                    return;
                }

                const shouldShowMediaSelector =
                    this.showMediaSelector === 'always' ||
                    (this.showMediaSelector === 'auto' && allMedia.length > 1);
                if (!shouldShowMediaSelector) {
                    this.remove();
                    return;
                }

                this.appendChild(template.content.cloneNode(true));

                const mediaSelector = this.querySelector('.octopusviewer-media-selector-list');
                mediaSelector.replaceChildren(...mediaSelectorContainer.childNodes);

                if (this.mediaId) {
                    this._markMediaAsSelected(this.mediaId);
                }

                this.addEventListener('click', this._onClick)
            });
        }

        disconnectedCallback () {
            this.removeEventListener('click', this._onClick);
        }

        fetchMediaSelector () {
            const mediaSelectorUrl = new URL('s/' + this.siteSlug + '/octopusviewer/viewer/media-selector', baseUrl);
            mediaSelectorUrl.search = this.mediaQuery;

            return fetch(mediaSelectorUrl).then(res => res.text())
        }

        _markMediaAsSelected (mediaId) {
            const mediaElement = this.querySelector('[data-media-id="' + mediaId + '"]');
            if (mediaElement) {
                mediaElement.closest('.octopusviewer-media-selector-list').querySelectorAll('.octopusviewer-media-selector-element').forEach(e => {
                    e.classList.remove('octopusviewer-selected');
                });
                mediaElement.classList.add('octopusviewer-selected');
            }
        }

        _onClick (ev) {
            // Allow links to be clicked
            if (ev.target.closest('a[href]')) {
                ev.stopPropagation();
                return;
            }

            ev.preventDefault();
            const el = ev.target.closest('[data-media-id]');
            if (!el) {
                return;
            }

            ev.stopPropagation();

            this.mediaId = el.dataset.mediaId;

            this.dispatchEvent(
                new CustomEvent(
                    'octopus:media-select',
                    {
                        bubbles: true,
                        detail: { mediaId: this.mediaId },
                    }
                )
            );
        }

        get mediaId () {
            return this.getAttribute('media-id');
        }

        set mediaId (mediaId) {
            this.setAttribute('media-id', mediaId);
        }

        get mediaQuery () {
            return this.getAttribute('media-query');
        }

        set mediaQuery (query) {
            this.setAttribute('media-query', query);
        }

        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (slug) {
            this.setAttribute('site-slug', slug);
        }

        get showMediaSelector () {
            return this.getAttribute('show-media-selector') ?? 'auto';
        }

        set showMediaSelector (showMediaSelector) {
            this.setAttribute('show-media-selector', showMediaSelector);
        }
    }

    const template = document.createElement('template');
    template.innerHTML = `<div class="octopusviewer-media-selector-list"></div>`;

    window.customElements.define('octopusviewer-media-selector', OctopusViewerMediaSelector);
})();
