(function () {
    'use strict';

    class MediaViewerOpenSeadragon extends HTMLElement {
        connectedCallback () {
            const config = JSON.parse(this.getAttribute('data-config'));
            config.element = this;
            OpenSeadragon(config);
        }
    }

    window.customElements.define('mediaviewer-openseadragon', MediaViewerOpenSeadragon);
})();
