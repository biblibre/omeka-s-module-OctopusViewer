(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/MediaViewer/asset/js/mediaviewer-viewer.js');
    const baseUrl = src.substring(0, baseUrlEnd);

    class MediaViewerViewer extends HTMLElement {
        constructor() {
            super();

            const shadowRoot = this.attachShadow({ mode: "open" });
        }

        connectedCallback () {
            const link = document.createElement('link');
            link.setAttribute('rel', 'stylesheet');
            link.setAttribute('href', new URL('/modules/MediaViewer/asset/css/mediaviewer-viewer.css', baseUrl));
            this.shadowRoot.appendChild(link);
            this.shadowRoot.appendChild(template.content.cloneNode(true));

            const mediaSelectorPromise = this.fetchMediaSelector();
            const jsDependenciesPromise = this.loadJsDependencies();

            Promise.all([mediaSelectorPromise, jsDependenciesPromise])
                .then(([mediaSelectorHTML]) => {
                    this.shadowRoot.querySelector('.mediaviewer-media-selector').innerHTML = mediaSelectorHTML;
                    const firstMedia = this.shadowRoot.querySelector('.mediaviewer-media-selector-element');
                    if (firstMedia) {
                        this.showMedia(firstMedia);
                    }

                    this.attachEventListeners();
                });
        }

        fetchMediaSelector () {
            const mediaSelectorUrl = new URL('/s/' + this.siteSlug + '/mediaviewer/viewer/media-selector', baseUrl);
            mediaSelectorUrl.search = this.mediaQuery;

            return fetch(mediaSelectorUrl).then(res => res.text())
        }

        loadJsDependencies () {
            const jsDependenciesUrl = new URL('/s/' + this.siteSlug + '/mediaviewer/viewer/js-dependencies', baseUrl);
            return fetch(jsDependenciesUrl)
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

        attachEventListeners () {
            this.shadowRoot.querySelector('.mediaviewer-fullscreen').addEventListener('click', () => {
                if (document.fullscreenElement === this) {
                    document.exitFullscreen();
                } else {
                    this.shadowRoot.querySelector('.mediaviewer-viewer').requestFullscreen();
                }
            });

            const mediaSelector = this.shadowRoot.querySelector('.mediaviewer-media-selector');
            mediaSelector.addEventListener('click', ev => {
                ev.preventDefault();
                ev.stopPropagation();
                const el = ev.target.closest('.mediaviewer-media-selector-element');
                if (!el) {
                    return;
                }

                this.showMedia(el);
            });
        }

        showMedia (mediaElement) {
            const mediaViewer = this.shadowRoot.querySelector('.mediaviewer-viewer');
            const mediaRenderUrl = new URL(mediaElement.getAttribute('data-mediaviewer-render-url'), baseUrl);
            const mediaInfoUrl = new URL(mediaElement.getAttribute('data-mediaviewer-info-url'), baseUrl);
            const mediaView = mediaViewer.querySelector('.mediaviewer-media-view');
            const mediaInfo = mediaViewer.querySelector('.mediaviewer-media-info');

            mediaView.innerHTML = '';
            fetch(mediaRenderUrl).then(response => {
                return response.text();
            }).then(text => {
                mediaView.innerHTML = text;
            });

            mediaInfo.innerHTML = '';
            fetch(mediaInfoUrl).then(response => {
                return response.text();
            }).then(text => {
                mediaInfo.innerHTML = text;
            });

            mediaElement.closest('.mediaviewer-media-selector').querySelectorAll('.mediaviewer-media-selector-element').forEach(e => {
                e.classList.remove('mediaviewer-selected');
            });
            mediaElement.classList.add('mediaviewer-selected');
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
    }

    const template = document.createElement('template');
    template.innerHTML = `
<div class="mediaviewer-viewer">
    <div class="mediaviewer-header">
        <div class="mediaviewer-title"><slot name="title"></slot></div>
        <a class="mediaviewer-fullscreen"><i class="mediaviewer-icon-fullscreen"></i></a>
    </div>
    <div class="mediaviewer-body">
        <div class="mediaviewer-media-selector"></div>
        <div class="mediaviewer-media-view"></div>
        <div class="mediaviewer-media-info"></div>
    </div>
    <div class="mediaviewer-footer"></div>
</div>
    `;

    window.customElements.define('mediaviewer-viewer', MediaViewerViewer);
})();
