(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/OctopusViewer/asset/js/octopusviewer-viewer.js');
    const baseUrl = src.substring(0, baseUrlEnd) + '/';

    const v = (new URL(src)).searchParams.get('v') ?? '';

    class OctopusViewer extends EventTarget {
        #selectedMedia;
        #selectedMediaIndex;

        static #instances = new Map();
        static #instancesByElement = new WeakMap();

        static getInstance(element) {
            if (!(element instanceof OctopusViewerBaseElement)) {
                throw new Error('element is not an instance of OctopusViewerBaseElement');
            }

            if (!this.#instancesByElement.has(element)) {
                const instanceKey = element.instanceKey;
                if (!this.#instances.has(instanceKey)) {
                    const instance = new OctopusViewer(element.siteSlug, element.mediaQuery)
                    this.#instances.set(instanceKey, instance);
                }

                this.#instancesByElement.set(element, this.#instances.get(instanceKey));
            }

            return this.#instancesByElement.get(element);
        }

        static setInstance(element, instance) {
            this.#instancesByElement.set(element, instance);
        }

        constructor (siteSlug, mediaQuery) {
            super();

            this.siteSlug = siteSlug;
            this.mediaQuery = mediaQuery;

            this.loadMediaList();
        }

        loadMediaList () {
            const mediaListUrl = new URL('s/' + this.siteSlug + '/octopusviewer/viewer/media-list', baseUrl);
            mediaListUrl.search = this.mediaQuery;

            fetch(mediaListUrl)
                .then(res => res.json())
                .then(res => {
                    this.media = res.media;

                    this.dispatchEvent(
                        new CustomEvent(
                            'octopus:media-list-loaded',
                            {
                                detail: { media: this.media },
                            }
                        )
                    );

                    this.selectMedia(0);
                });
        }

        get selectedMedia () {
            return this.#selectedMedia;
        }

        selectMedia (index) {
            this.#selectedMedia = this.media.at(index);
            this.#selectedMediaIndex = this.#selectedMedia ? index : undefined;

            this.dispatchEvent(
                new CustomEvent(
                    'octopus:media-select',
                    {
                        detail: { media: this.selectedMedia },
                    }
                )
            );
        }

        selectMediaById (id) {
            const index = this.media.findIndex(m => m['o:id'] == id);
            if (index < 0) {
                throw new Error('Attempt to select a media that is not in the list');
            }

            this.selectMedia(index);
        }

        selectMediaPrevious () {
            if (this.hasPrevious()) {
                this.selectMedia(this.#selectedMediaIndex - 1);
            }
        }

        selectMediaNext () {
            if (this.hasNext()) {
                this.selectMedia(this.#selectedMediaIndex + 1);
            }
        }

        hasPrevious () {
            return this.media && this.#selectedMediaIndex > 0;
        }

        hasNext () {
            return this.media && this.#selectedMediaIndex < this.media.length - 1;
        }

        createFullscreenViewer (options = {}) {
            const viewer = document.createElement('octopusviewer-viewer');
            viewer.instance = this;

            const title = document.createElement('span');
            title.setAttribute('slot', 'title');
            title.innerText = options.fullscreenTitle;
            viewer.appendChild(title);

            viewer.mediaQuery = this.mediaQuery;
            viewer.siteSlug = this.siteSlug;
            viewer.showMediaSelector = options.showMediaSelector;
            viewer.showMediaInfo = options.showMediaInfo;
            viewer.showDownloadLink = options.showDownloadLink;
            viewer.extraStylesheet = options.extraStylesheet;

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
    }

    class OctopusViewerBaseElement extends HTMLElement {
        get siteSlug () {
            return this.getAttribute('site-slug');
        }

        set siteSlug (siteSlug) {
            this.setAttribute('site-slug', siteSlug ?? '');
        }

        get mediaQuery () {
            return this.getAttribute('media-query');
        }

        set mediaQuery (mediaQuery) {
            this.setAttribute('media-query', mediaQuery ?? '');
        }

        get instanceKey () {
            return this.getAttribute('instance-key') ?? `${this.siteSlug}:${this.mediaQuery}`;
        }

        set instanceKey (instanceKey) {
            this.setAttribute('instance-key', instanceKey);
        }

        get instance () {
            return OctopusViewer.getInstance(this);
        }

        set instance (instance) {
            OctopusViewer.setInstance(this, instance);
        }
    }

    class OctopusViewerViewerElement extends OctopusViewerBaseElement {
        constructor () {
            super();

            this.attachShadow({ mode: 'open' });
        }

        connectedCallback () {
            this.appendStylesheet(`modules/OctopusViewer/asset/css/octopusviewer-viewer.css?v=${v}`);
            if (this.extraStylesheet) {
                this.appendStylesheet(this.extraStylesheet);
            }

            // TODO Remove viewer if no media

            this.shadowRoot.appendChild(template.content.cloneNode(true));

            const mediaSelector = document.createElement('octopusviewer-media-selector');
            const mediaView = document.createElement('octopusviewer-media-view');
            const mediaInfo = document.createElement('octopusviewer-media-info');

            for (const el of [mediaSelector, mediaView, mediaInfo]) {
                for (const property of ['instance', 'mediaQuery', 'siteSlug', 'showDownloadLink']) {
                    el[property] = this[property];
                }
            }

            mediaView.fullscreenExtraStylesheet = this.extraStylesheet;

            const titleSlot = this.shadowRoot.querySelector('slot[name="title"]');
            titleSlot.addEventListener('slotchange', ev => {
                mediaView.fullscreenTitle = ev.target.assignedNodes().map(node => node.textContent).join(' ');
            });

            this.shadowRoot.querySelector('.octopusviewer-media-selector .sidebar-content').appendChild(mediaSelector);
            this.shadowRoot.querySelector('.octopusviewer-media-view').appendChild(mediaView);
            this.shadowRoot.querySelector('.octopusviewer-media-info-metadata').appendChild(mediaInfo);

            this.shadowRoot.addEventListener('click', this);
            this.instance.addEventListener('octopus:media-list-loaded', this);

            this.shadowRoot.querySelector('.octopusviewer-viewer').classList.add('loaded');

            if (this.showMediaInfo === 'always') {
                this.shadowRoot.querySelector('.octopusviewer-media-info').classList.remove('hidden');
            }

            this.#setMediaSelectorVisibility();
        }

        disconnectedCallback () {
            this.shadowRoot.removeEventListener('click', this);
            this.instance.removeEventListener('octopus:media-list-loaded', this);
        }

        handleEvent (ev) {
            if (ev.type === 'click') {
                return this.#onClick(ev);
            }
            if (ev.type === 'octopus:media-list-loaded') {
                return this.#onMediaListLoaded(ev);
            }
        }

        appendStylesheet (url) {
            const link = document.createElement('link');
            link.setAttribute('rel', 'stylesheet');
            link.setAttribute('href', new URL(url, baseUrl));
            this.shadowRoot.appendChild(link);
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

        get showDownloadLink () {
            return this.getAttribute('show-download-link') ?? '';
        }

        set showDownloadLink (showDownloadLink) {
            this.setAttribute('show-download-link', showDownloadLink ?? '');
        }

        get extraStylesheet () {
            return this.getAttribute('extra-stylesheet') ?? '';
        }

        set extraStylesheet (extraStylesheet) {
            this.setAttribute('extra-stylesheet', extraStylesheet ?? '');
        }

        #onClick (ev) {
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
        }

        #onMediaListLoaded (ev) {
            this.#setMediaSelectorVisibility();
        }

        #setMediaSelectorVisibility () {
            const shouldShowMediaSelector =
                this.showMediaSelector === 'always' ||
                (this.showMediaSelector === 'auto' && this.instance.media && this.instance.media.length > 1);

            if (shouldShowMediaSelector) {
                this.shadowRoot.querySelector('.octopusviewer-media-selector').classList.remove('hidden');
            } else {
                this.shadowRoot.querySelector('.octopusviewer-media-selector').classList.add('hidden');
            }
        }
    }

    const template = document.createElement('template');
    template.innerHTML = `
<div class="octopusviewer-viewer">
    <div class="octopusviewer-header">
        <div class="octopusviewer-title"><slot name="title"></slot></div>
    </div>
    <div class="octopusviewer-body">
        <div class="octopusviewer-media-selector sidebar sidebar-left hidden">
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
        </div>
    </div>
</div>
    `;

    window.customElements.define('octopusviewer-viewer', OctopusViewerViewerElement);

    class OctopusViewerMediaInfoElement extends OctopusViewerBaseElement {
        connectedCallback () {
            this.#render();

            this.instance.addEventListener('octopus:media-select', this);
        }

        disconnectedCallback () {
            this.instance.removeEventListener('octopus:media-select', this);
        }

        handleEvent (ev) {
            if (ev.type === 'octopus:media-select') {
                return this.#onMediaSelect(ev);
            }
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

        #render () {
            const mediaId = this.instance.selectedMedia?.['o:id'];
            if (mediaId) {
                const siteSlug = this.instance.siteSlug;
                const mediaInfoUrl = new URL(`s/${siteSlug}/octopusviewer/media/${mediaId}/info`, baseUrl);

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

        #onMediaSelect (ev) {
            this.#render();
        }
    }

    window.customElements.define('octopusviewer-media-info', OctopusViewerMediaInfoElement);

    class OctopusViewerMediaViewElement extends OctopusViewerBaseElement {
        #jsDepsPromise;

        connectedCallback () {
            this.#render();

            this.addEventListener('click', this.#onClick)
            this.instance.addEventListener('octopus:media-select', this);
        }

        disconnectedCallback () {
            this.removeEventListener('click', this);

            this.instance.removeEventListener('octopus:media-select', this);
        }

        applyTemplate () {
            const fragment = viewTemplate.content.cloneNode(true)

            if (this.showDownloadLink !== 'controls' && this.showDownloadLink !== 'media-selector|controls') {
                fragment.querySelector('.octopusviewer-download')?.remove();
            }

            if (window.Omeka?.jsTranslate) {
                fragment.querySelector('.octopusviewer-download')?.setAttribute('title', Omeka.jsTranslate('Download'));
                fragment.querySelector('.octopusviewer-fullscreen')?.setAttribute('title', Omeka.jsTranslate('Toggle fullscreen'));
                fragment.querySelector('.octopusviewer-previous')?.setAttribute('title', Omeka.jsTranslate('Previous'));
                fragment.querySelector('.octopusviewer-next')?.setAttribute('title', Omeka.jsTranslate('Next'));
            }

            const downloadButton = fragment.querySelector('button.octopusviewer-download');
            if (downloadButton) {
                const selectedMedia = this.instance.selectedMedia;
                downloadButton.disabled = !selectedMedia || !selectedMedia['o:original_url']
            }

            const nextButton = fragment.querySelector('button.octopusviewer-next');
            if (nextButton) {
                nextButton.disabled = !this.instance.hasNext();
            }
            const previousButton = fragment.querySelector('button.octopusviewer-previous');
            if (previousButton) {
                previousButton.disabled = !this.instance.hasPrevious();
            }

            this.replaceChildren(fragment);
        }

        handleEvent (ev) {
            if (ev.type === 'click') {
                return this.#onClick(ev);
            }
            if (ev.type === 'octopus:media-select') {
                return this.#onMediaSelect(ev);
            }
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

        #onClick (ev) {
            if (ev.target.closest('.octopusviewer-fullscreen')) {
                ev.preventDefault();

                this.toggleFullscreen();
            } else if (ev.target.closest('.octopusviewer-download')) {
                ev.preventDefault();

                this.download();
            } else if (ev.target.closest('.octopusviewer-previous')) {
                ev.preventDefault();

                this.instance.selectMediaPrevious();
            } else if (ev.target.closest('.octopusviewer-next')) {
                ev.preventDefault();

                this.instance.selectMediaNext();
            }
        }

        #onMediaSelect (ev) {
            this.#render();
        }

        #render() {
            const mediaId = this.instance.selectedMedia?.['o:id'];
            this.loadJsDependencies().then(() => {
                if (mediaId) {
                    const mediaRenderUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${mediaId}/render`, baseUrl);
                    fetch(mediaRenderUrl).then(response => {
                        return response.text();
                    }).then(html => {
                        this.applyTemplate();
                        this.querySelector('.octopusviewer-media-view-main').innerHTML = html;
                    });
                } else {
                    this.applyTemplate();
                }
            });
        }

        toggleFullscreen () {
            if (document.fullscreenElement) {
                document.exitFullscreen();
                return;
            }

            const options = {
                fullscreenTitle: this.fullscreenTitle,
                showMediaSelector: this.showMediaSelector,
                showMediaInfo: this.showMediaInfo,
                showDownloadLink: this.showDownloadLink,
                extraStylesheet: this.fullscreenExtraStylesheet,
            };
            this.instance.createFullscreenViewer(options);
        }

        download () {
            const mediaId = this.instance.selectedMedia?.['o:id'];
            if (mediaId) {
                const mediaDownloadUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${mediaId}/download`, baseUrl);
                fetch(mediaDownloadUrl)
                    .then(res => res.json())
                    .then(data => {
                        if (data.originalUrl) {
                            window.open(data.originalUrl, '_blank');
                        } else {
                            console.error('Attempt to download a media that has no original url');
                        }
                    }).catch(console.error);
            }
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

        get showDownloadLink () {
            return this.getAttribute('show-download-link') ?? '';
        }

        set showDownloadLink (showDownloadLink) {
            this.setAttribute('show-download-link', showDownloadLink ?? '');
        }

        get fullscreenTitle () {
            return this.getAttribute('fullscreen-title') ?? '';
        }

        set fullscreenTitle (fullscreenTitle) {
            this.setAttribute('fullscreen-title', fullscreenTitle ?? '');
        }

        get fullscreenExtraStylesheet () {
            return this.getAttribute('fullscreen-extra-stylesheet') ?? '';
        }

        set fullscreenExtraStylesheet (fullscreenExtraStylesheet) {
            this.setAttribute('fullscreen-extra-stylesheet', fullscreenExtraStylesheet ?? '');
        }
    }

    const viewTemplate = document.createElement('template');
    viewTemplate.innerHTML = `
        <div class="octopusviewer-media-view-main"></div>
        <div class="octopusviewer-media-view-controls">
            <div class="octopusviewer-media-view-controls-region-left">
                <button class="octopusviewer-download" title="Download"><i class="octopusviewer-icon-download"></i></button>
            </div>
            <div class="octopusviewer-media-view-controls-region-center">
                <button class="octopusviewer-previous" title="Previous"><i class="octopusviewer-icon-previous"></i></button>
                <button class="octopusviewer-next" title="Next"><i class="octopusviewer-icon-next"></i></button>
            </div>
            <div class="octopusviewer-media-view-controls-region-right">
                <button class="octopusviewer-fullscreen" title="Toggle fullscreen"><i class="octopusviewer-icon-fullscreen"></i></button>
            </div>
        </div>
    `;

    window.customElements.define('octopusviewer-media-view', OctopusViewerMediaViewElement);

    class OctopusViewerMediaSelectorElement extends OctopusViewerBaseElement {
        connectedCallback () {
            this.instance.addEventListener('octopus:media-list-loaded', this);
            this.instance.addEventListener('octopus:media-select', this);

            this.addEventListener('click', this.#onClick)

            this.#loadMediaSelector();
        }

        disconnectedCallback () {
            this.instance.removeEventListener('octopus:media-list-loaded', this);
            this.instance.removeEventListener('octopus:media-select', this);

            this.removeEventListener('click', this);
        }

        handleEvent (ev) {
            if (ev.type === 'octopus:media-list-loaded') {
                this.#onMediaListLoaded(ev);
            } else if (ev.type === 'octopus:media-select') {
                this.#onMediaSelect(ev);
            } else if (ev.type === 'click') {
                this.#onClick(ev);
            }
        }

        #loadMediaSelector () {
            const media = this.instance.media;

            if (!media || media.length === 0 || (media.length === 1 && this.showMediaSelector === 'auto')) {
                this.replaceChildren();
                return;
            }

            this.fetchMediaSelector(this.instance.mediaQuery, this.instance.siteSlug).then(mediaSelectorHTML => {
                const mediaSelectorContainer = document.createElement('div');
                mediaSelectorContainer.innerHTML = mediaSelectorHTML;

                this.replaceChildren(selectorTemplate.content.cloneNode(true));

                const mediaSelector = this.querySelector('.octopusviewer-media-selector-list');
                mediaSelector.replaceChildren(...mediaSelectorContainer.childNodes);

                this.#markMediaAsSelected(this.instance.selectedMedia?.['o:id']);
            });
        }

        #onMediaListLoaded (ev) {
            this.#loadMediaSelector();
        }

        #onMediaSelect (ev) {
            this.#markMediaAsSelected(ev.detail.media?.['o:id']);
        }

        fetchMediaSelector (mediaQuery, siteSlug) {
            const mediaSelectorUrl = new URL('s/' + siteSlug + '/octopusviewer/viewer/media-selector', baseUrl);
            mediaSelectorUrl.search = mediaQuery;

            return fetch(mediaSelectorUrl).then(res => res.text())
        }

        #markMediaAsSelected (mediaId) {
            this.querySelectorAll('[data-media-id]').forEach(e => {
                if (mediaId && mediaId == e.dataset.mediaId) {
                    e.classList.add('octopusviewer-selected');
                } else {
                    e.classList.remove('octopusviewer-selected');
                }
            });
        }

        #onClick (ev) {
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

            this.instance.selectMediaById(el.dataset.mediaId);
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

    window.customElements.define('octopusviewer-media-selector', OctopusViewerMediaSelectorElement);
})();
