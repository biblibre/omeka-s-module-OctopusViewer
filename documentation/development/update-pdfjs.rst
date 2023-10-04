Update PDF.js
=============

To update PDF.js:

1. Go to https://github.com/mozilla/pdf.js/releases/latest and download the
   ``pdfjs-<VERSION>-dist.zip`` file.

2. Remove the old files::

    git rm -r asset/vendor/pdf.js

3. Extract the .zip file contents inside ``asset/vendor/pdf.js``::

    unzip pdfjs-<VERSION>-dist.zip -d asset/vendor/pdf.js

4. Commit! ::

    git add asset/vendor/pdf.js && git commit -m 'Update PDF.js to <VERSION>'
