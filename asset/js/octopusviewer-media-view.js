(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/OctopusViewer/asset/js/octopusviewer-media-view.js');
    const baseUrl = src.substring(0, baseUrlEnd) + '/';

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
                    const mediaRenderUrl = new URL(`s/${this.siteSlug}/octopusviewer/media/${mediaId}/render`, baseUrl);
                    fetch(mediaRenderUrl).then(response => {
                        return response.text();
                    }).then(text => {
                        this.querySelector('.octopusviewer-media-view-main').innerHTML = text;
                    });
                });
            }
        }

        connectedCallback () {
            this.appendChild(template.content.cloneNode(true));
            this.loadJsDependencies();

            this.querySelector('.octopusviewer-fullscreen').addEventListener('click', () => {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                } else {
                    const viewer = document.createElement('octopusviewer-viewer');
                    viewer.mediaQuery = 'item_id=87600';
                    viewer.siteSlug = this.siteSlug;
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
            this.setAttribute('media-id', mediaId);
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
        <div class="octopusviewer-media-view-main"></div>
        <div class="octopusviewer-media-view-controls">
            <a class="octopusviewer-fullscreen"><i class="octopusviewer-icon-fullscreen"></i></a>
        </div>
    `;

    window.customElements.define('octopusviewer-media-view', OctopusViewerMediaView);
})();
