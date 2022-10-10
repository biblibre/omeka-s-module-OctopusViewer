(function () {
    'use strict';

    class MediaViewerPdfObject extends HTMLElement {
        connectedCallback () {
            const url = this.getAttribute('data-url');
            const config = JSON.parse(this.getAttribute('data-config'));
            PDFObject.embed(url, this, config);
        }
    }

    window.customElements.define('mediaviewer-pdfobject', MediaViewerPdfObject);
})();
