(function () {
    'use strict';

    class OctopusViewerPdfJs extends HTMLElement {
        connectedCallback () {
            const url = this.getAttribute('data-url');
            const config = JSON.parse(this.getAttribute('data-config'));

            const viewer_url = new URL(config.viewer_url);
            viewer_url.searchParams.set('file', url);

            const iframe = document.createElement('iframe');
            iframe.src = viewer_url.toString();
            iframe.allow = 'fullscreen';
            iframe.style = 'width: 100%; height: 100%; border: none';

            if (config.css_url) {
                iframe.addEventListener('load', function (event) {
                    const doc = this.contentDocument;
                    const link = doc.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = config.css_url;
                    doc.head.append(link);
                });
            }

            this.append(iframe);
        }
    }

    window.customElements.define('octopusviewer-pdfjs', OctopusViewerPdfJs);
})();
