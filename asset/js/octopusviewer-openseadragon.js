(function () {
    'use strict';

    class OctopusViewerOpenSeadragon extends HTMLElement {
        connectedCallback () {
            const config = JSON.parse(this.getAttribute('data-config'));
            config.element = this;

            if (typeof config.tileSources === 'string' && config.fallbackUrl) {
                fetch(config.tileSources, { method: 'HEAD' }).then(res => {
                    if (!res.ok) {
                        config.tileSources = {
                            type: 'image',
                            url: config.fallbackUrl,
                        }
                    }
                    OpenSeadragon(config);
                });
            } else {
                OpenSeadragon(config);
            }
        }
    }

    window.customElements.define('octopusviewer-openseadragon', OctopusViewerOpenSeadragon);
})();
