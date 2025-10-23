(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/OctopusViewer/asset/js/octopusviewer-viewer.js');
    const baseUrl = src.substring(0, baseUrlEnd) + '/';

    class OctopusViewerViewer extends HTMLElement {
        static get observedAttributes() {
            return ['media-id'];
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (oldValue === newValue) return;

            if (name === 'media-id') {
                for (const el of this.shadowRoot.querySelectorAll('octopusviewer-media-selector,octopusviewer-media-view,octopusviewer-media-info')) {
                    el.mediaId = newValue;
                }
            }
        }
        constructor () {
            super();

            this.attachShadow({ mode: 'open' });
        }

        connectedCallback () {
            this.appendStylesheet('modules/OctopusViewer/asset/css/octopusviewer-viewer.css');
            if (this.extraStylesheet) {
                this.appendStylesheet(this.extraStylesheet);
            }

            // TODO Remove viewer if no media

            this.shadowRoot.appendChild(template.content.cloneNode(true));

            const mediaSelector = document.createElement('octopusviewer-media-selector');
            const mediaView = document.createElement('octopusviewer-media-view');
            const mediaInfo = document.createElement('octopusviewer-media-info');

            for (const el of [mediaSelector, mediaView, mediaInfo]) {
                for (const property of ['mediaQuery', 'siteSlug', 'showMediaSelector', 'showMediaInfo', 'mediaId']) {
                    el[property] = this[property];
                }
            }

            this.shadowRoot.querySelector('.octopusviewer-media-selector .sidebar-content').appendChild(mediaSelector);
            this.shadowRoot.querySelector('.octopusviewer-media-view').appendChild(mediaView);
            this.shadowRoot.querySelector('.octopusviewer-media-info-metadata').appendChild(mediaInfo);

            this.attachEventListeners();

            this.shadowRoot.querySelector('.octopusviewer-viewer').classList.add('loaded');

            // TODO Redo this
            //const shouldShowMediaSelector =
            //    this.showMediaSelector === 'always' ||
            //    (this.showMediaSelector === 'auto' && allMedia.length > 1);
            //if (shouldShowMediaSelector) {
            //    this.shadowRoot.querySelector('.octopusviewer-media-selector').classList.remove('hidden');
            //}

            if (this.showMediaInfo === 'always') {
                this.shadowRoot.querySelector('.octopusviewer-media-info').classList.remove('hidden');
            }
        }

        attachEventListeners () {
            this.shadowRoot.addEventListener('octopus:media-select', ev => {
                for (const el of this.shadowRoot.querySelectorAll('octopusviewer-media-selector, octopusviewer-media-view, octopusviewer-media-info')) {
                    if (el === ev.target) continue;

                    el.mediaId = ev.detail.mediaId;
                }
            });

            this.shadowRoot.querySelector('.octopusviewer-viewer').addEventListener('click', ev => {
                // Allow links to be clicked
                if (ev.target.closest('a[href]')) {
                    ev.stopPropagation();
                    return;
                }

                ev.preventDefault();
                const el = ev.target.closest('.collapse-toggle');
                if (!el) {
                    return;
                }

                ev.stopPropagation();

                ev.target.closest('.sidebar').classList.toggle('collapsed');
            });
        }

        appendStylesheet (url) {
            const link = document.createElement('link');
            link.setAttribute('rel', 'stylesheet');
            link.setAttribute('href', new URL(url, baseUrl));
            this.shadowRoot.appendChild(link);
        }

        get mediaId () {
            return this.getAttribute('media-id');
        }

        set mediaId (mediaId) {
            this.setAttribute('media-id', mediaId ?? '');
        }

        get mediaQuery () {
            return this.getAttribute('media-query');
        }

        set mediaQuery (query) {
            this.setAttribute('media-query', query ?? '');
        }

        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (slug) {
            this.setAttribute('site-slug', slug ?? '');
        }

        get showMediaSelector () {
            return this.getAttribute('show-media-selector') ?? 'auto';
        }

        set showMediaSelector (showMediaSelector) {
            this.setAttribute('show-media-selector', showMediaSelector ?? '');
        }

        get showMediaInfo () {
            return this.getAttribute('show-media-info') ?? 'always';
        }

        set showMediaInfo (showMediaInfo) {
            this.setAttribute('show-media-info', showMediaInfo ?? '');
        }

        get extraStylesheet () {
            return this.getAttribute('extra-stylesheet') ?? '';
        }

        set extraStylesheet (extraStylesheet) {
            this.setAttribute('extra-stylesheet', extraStylesheet ?? '');
        }
    }

    const template = document.createElement('template');
    template.innerHTML = `
<div class="octopusviewer-viewer">
    <div class="octopusviewer-header">
        <div class="octopusviewer-title"><slot name="title"></slot></div>
    </div>
    <div class="octopusviewer-body">
        <div class="octopusviewer-media-selector sidebar sidebar-left">
            <div class="sidebar-header collapse-toggle">
                <i class="octopusviewer-icon-collapse"></i>
            </div>
            <div class="sidebar-content"></div>
        </div>
        <div class="octopusviewer-media-view"></div>
        <div class="octopusviewer-media-info sidebar sidebar-right hidden">
            <div class="sidebar-header collapse-toggle">
                <i class="octopusviewer-icon-collapse"></i>
            </div>
            <div class="sidebar-content octopusviewer-media-info-metadata"></div>
            <div class="sidebar-footer octopusviewer-media-info-footer"></div>
        </div>
    </div>
    <div class="octopusviewer-footer"></div>
</div>
    `;

    window.customElements.define('octopusviewer-viewer', OctopusViewerViewer);

    class OctopusViewerMediaInfo extends HTMLElement {
        static get observedAttributes() {
            return ['media-id'];
        }

        #loadInfo () {
            if (this.mediaId) {
                const mediaInfoUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${this.mediaId}/info`, baseUrl);

                fetch(mediaInfoUrl).then(response => {
                    return response.json();
                }).then(data => {
                    this.innerHTML = data.content;
                }, err => {
                    console.error(err);
                    this.innerHTML = '';
                });
            } else {
                this.innerHTML = '';
            }
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
            this.setAttribute('media-id', mediaId ?? '');
        }

        get mediaQuery () {
            return this.getAttribute('media-query');
        }

        set mediaQuery (query) {
            this.setAttribute('media-query', query ?? '');
        }

        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (slug) {
            this.setAttribute('site-slug', slug ?? '');
        }

        get showMediaSelector () {
            return this.getAttribute('show-media-selector') ?? 'auto';
        }

        set showMediaSelector (showMediaSelector) {
            this.setAttribute('show-media-selector', showMediaSelector ?? '');
        }

        get showMediaInfo () {
            return this.getAttribute('show-media-info') ?? 'always';
        }

        set showMediaInfo (showMediaInfo) {
            this.setAttribute('show-media-info', showMediaInfo ?? '');
        }
    }

    window.customElements.define('octopusviewer-media-info', OctopusViewerMediaInfo);

    class OctopusViewerMediaView extends HTMLElement {
        #jsDepsPromise;

        static get observedAttributes() {
            return ['media-id'];
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (oldValue === newValue) return;

            if (name === 'media-id') {
                const mediaId = newValue;

                this.loadJsDependencies().then(() => {
                    if (mediaId) {
                        const mediaRenderUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${mediaId}/render`, baseUrl);
                        fetch(mediaRenderUrl).then(response => {
                            return response.text();
                        }).then(html => {
                            this.replaceChildren(viewTemplate.content.cloneNode(true));
                            this.querySelector('.octopusviewer-media-view-main').innerHTML = html;
                        });
                    } else {
                        this.replaceChildren(viewTemplate.content.cloneNode(true));
                    }
                });
            }
        }

        connectedCallback () {
            this.appendChild(viewTemplate.content.cloneNode(true));
            this.loadJsDependencies();

            this.addEventListener('click', ev => {
                if (ev.target.closest('.octopusviewer-fullscreen')) {
                    ev.preventDefault();

                    if (document.fullscreenElement) {
                        document.exitFullscreen();
                    } else {
                        const viewer = document.createElement('octopusviewer-viewer');
                        viewer.mediaQuery = 'item_id=87600';
                        viewer.siteSlug = this.siteSlug;
                        viewer.mediaId = this.mediaId;
                        viewer.style.position = 'absolute';
                        viewer.style.top = '-20000px';
                        viewer.style.left = '-20000px';
                        viewer.addEventListener('fullscreenchange', () => {
                            if (!document.fullscreenElement) {
                                viewer.remove();
                            }
                        });
                        document.body.appendChild(viewer);
                        viewer.requestFullscreen();
                    }
                } else if (ev.target.closest('.octopusviewer-download')) {
                    ev.preventDefault();

                    const mediaDownloadUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${this.mediaId}/download`, baseUrl);
                    fetch(mediaDownloadUrl).then(res => res.json()).then(data => {
                        window.open(data.originalUrl, '_blank');
                    }).catch(console.error);
                }
            });
        }

        loadJsDependencies () {
            if (!this.#jsDepsPromise) {
                const jsDependenciesUrl = new URL('s/' + this.siteSlug + '/octopusviewer/viewer/js-dependencies', baseUrl);
                this.#jsDepsPromise = fetch(jsDependenciesUrl)
                    .then(res => res.json())
                    .then(data => {
                        const promises = [];
                        for (const jsDependency of data.jsDependencies) {
                            if (document.scripts.namedItem(jsDependency)) {
                                continue;
                            }
                            promises.push(this.loadScript(jsDependency));
                        }
                        return Promise.allSettled(promises);
                    });

            }

            return this.#jsDepsPromise;
        }

        loadScript (src) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.id = src;
                script.src = new URL(src, baseUrl);
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        get mediaId () {
            return this.getAttribute('media-id');
        }

        set mediaId (mediaId) {
            this.setAttribute('media-id', mediaId ?? '');
        }

        get mediaQuery () {
            return this.getAttribute('media-query');
        }

        set mediaQuery (query) {
            this.setAttribute('media-query', query ?? '');
        }

        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (slug) {
            this.setAttribute('site-slug', slug ?? '');
        }

        get showMediaSelector () {
            return this.getAttribute('show-media-selector') ?? 'auto';
        }

        set showMediaSelector (showMediaSelector) {
            this.setAttribute('show-media-selector', showMediaSelector ?? '');
        }

        get showMediaInfo () {
            return this.getAttribute('show-media-info') ?? 'always';
        }

        set showMediaInfo (showMediaInfo) {
            this.setAttribute('show-media-info', showMediaInfo ?? '');
        }
    }

    const viewTemplate = document.createElement('template');
    viewTemplate.innerHTML = `
        <div class="octopusviewer-media-view-main"></div>
        <div class="octopusviewer-media-view-controls">
            <a class="octopusviewer-download" title="Download"><i class="octopusviewer-icon-download"></i></a>
            <a class="octopusviewer-fullscreen" title="Toggle fullscreen"><i class="octopusviewer-icon-fullscreen"></i></a>
        </div>
    `;

    window.customElements.define('octopusviewer-media-view', OctopusViewerMediaView);

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

                this.appendChild(selectorTemplate.content.cloneNode(true));

                const mediaSelector = this.querySelector('.octopusviewer-media-selector-list');
                mediaSelector.replaceChildren(...mediaSelectorContainer.childNodes);

                if (this.mediaId) {
                    this._markMediaAsSelected(this.mediaId);
                } else {
                    this.mediaId = allMedia[0].dataset.mediaId;
                    this._dispatchMediaSelectEvent();
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

            this._dispatchMediaSelectEvent();
        }

        _dispatchMediaSelectEvent () {
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
            this.setAttribute('media-id', mediaId ?? '');
        }

        get mediaQuery () {
            return this.getAttribute('media-query');
        }

        set mediaQuery (query) {
            this.setAttribute('media-query', query ?? '');
        }

        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (slug) {
            this.setAttribute('site-slug', slug ?? '');
        }

        get showMediaSelector () {
            return this.getAttribute('show-media-selector') ?? 'auto';
        }

        set showMediaSelector (showMediaSelector) {
            this.setAttribute('show-media-selector', showMediaSelector ?? '');
        }

        get showMediaInfo () {
            return this.getAttribute('show-media-info') ?? 'always';
        }

        set showMediaInfo (showMediaInfo) {
            this.setAttribute('show-media-info', showMediaInfo ?? '');
        }
    }

    const selectorTemplate = document.createElement('template');
    selectorTemplate.innerHTML = `<div class="octopusviewer-media-selector-list"></div>`;

    window.customElements.define('octopusviewer-media-selector', OctopusViewerMediaSelector);

    document.addEventListener('octopus:media-select', function (ev) {
        for (const el of document.querySelectorAll('octopusviewer-media-selector, octopusviewer-media-view, octopusviewer-media-info')) {
            if (el === ev.target) continue;

            el.mediaId = ev.detail.mediaId;
        }
    });
})();
