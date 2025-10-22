(function () {
    'use strict';

    const src = document.currentScript.src;
    const baseUrlEnd = src.indexOf('/modules/OctopusViewer/asset/js/octopusviewer-viewer.js');
    const baseUrl = src.substring(0, baseUrlEnd) + '/';

    class OctopusViewerViewer extends HTMLElement {
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
            mediaSelector.siteSlug = this.siteSlug;
            mediaSelector.mediaQuery = this.mediaQuery;
            mediaSelector.showMediaSelector = this.showMediaSelector;
            this.shadowRoot.querySelector('.octopusviewer-media-selector .sidebar-content').appendChild(mediaSelector);

            const mediaView = document.createElement('octopusviewer-media-view');
            mediaView.siteSlug = this.siteSlug;
            this.shadowRoot.querySelector('.octopusviewer-media-view').appendChild(mediaView);

            const mediaInfo = document.createElement('octopusviewer-media-info');
            mediaInfo.siteSlug = this.siteSlug;
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

            this.shadowRoot.querySelector('.octopusviewer-fullscreen').addEventListener('click', () => {
                if (document.fullscreenElement === this) {
                    document.exitFullscreen();
                } else {
                    this.requestFullscreen();
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

        get showMediaInfo () {
            return this.getAttribute('show-media-info') ?? 'always';
        }

        set showMediaInfo (showMediaInfo) {
            this.setAttribute('show-media-info', showMediaInfo);
        }

        get extraStylesheet () {
            return this.getAttribute('extra-stylesheet') ?? '';
        }

        set extraStylesheet (extraStylesheet) {
            this.setAttribute('extra-stylesheet', extraStylesheet);
        }
    }

    const template = document.createElement('template');
    template.innerHTML = `
<div class="octopusviewer-viewer">
    <div class="octopusviewer-header">
        <div class="octopusviewer-title"><slot name="title"></slot></div>
        <a class="octopusviewer-fullscreen"><i class="octopusviewer-icon-fullscreen"></i></a>
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
})();
