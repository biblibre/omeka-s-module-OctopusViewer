(function () {
    'use strict';

    document.addEventListener('octopus:media-select', function (ev) {
        for (const el of document.querySelectorAll('octopusviewer-media-selector, octopusviewer-media-view, octopusviewer-media-info')) {
            if (el === ev.target) continue;

            el.mediaId = ev.detail.mediaId;
        }
    });
})();
