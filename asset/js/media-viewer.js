(function () {
    'use strict';

    function MediaViewer (options) {
        const mediaViewer = this;

        mediaViewer.options = options;
        mediaViewer._init();

        const el = mediaViewer.options.element;
        const firstMedia = el.querySelector('.media-viewer-media-selector-element');
        if (firstMedia) {
            mediaViewer.showMedia(firstMedia);
        }
        el.querySelector('.media-viewer-fullscreen').addEventListener('click', function () {
            if (document.fullscreenElement === el) {
                document.exitFullscreen();
            } else {
                el.requestFullscreen();
            }
        });

        return mediaViewer;
    }

    MediaViewer.prototype._init = function _init () {
        const mediaViewer = this;
        const el = mediaViewer.options.element;
        if (!el) {
            throw new Error('MediaViewer: no element specified');
        }

        el.querySelector('.media-viewer-media-selector').addEventListener('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            const el = ev.target.closest('.media-viewer-media-selector-element');
            if (!el) {
                return;
            }

            mediaViewer.showMedia(el);
        });
    };

    MediaViewer.prototype.showMedia = function showMedia (mediaElement) {
        const mediaViewer = this.options.element;
        const mediaRenderUrl = mediaElement.getAttribute('data-mediaviewer-render-url');
        const mediaInfoUrl = mediaElement.getAttribute('data-mediaviewer-info-url');
        const mediaView = mediaViewer.querySelector('.media-viewer-media-view');
        const mediaInfo = mediaViewer.querySelector('.media-viewer-media-info');

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
    }

    document.addEventListener('DOMContentLoaded', function () {
        const mediaViewers = document.querySelectorAll('.media-viewer');
        for (const mediaViewer of mediaViewers) {
            const mv = new MediaViewer({
                element: mediaViewer,
            });
        }
    })
})();
