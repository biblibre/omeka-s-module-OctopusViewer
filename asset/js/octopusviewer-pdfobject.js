(function () {
    'use strict';

    class OctopusViewerPdfObject extends HTMLElement {
        connectedCallback () {
            const url = this.getAttribute('data-url');
            const config = JSON.parse(this.getAttribute('data-config'));
            PDFObject.embed(url, this, config);
        }
    }

    window.customElements.define('octopusviewer-pdfobject', OctopusViewerPdfObject);
})();
