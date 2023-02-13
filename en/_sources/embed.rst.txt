Embed the viewer on another site
================================

As an iframe
------------

The easiest way to embed the viewer is by using ``<iframe>``.

Add the following HTML code to your site:

.. code-block:: html

    <iframe
        src="{BASE_URL}/s/{SITE_SLUG}/mediaviewer/viewer/embed?mediaviewer_title={TITLE}&{MEDIA_QUERY}"
        height="450px"
        width="100%"
        allow="fullscreen"
        sandbox="allow-same-origin allow-scripts"
    ></iframe>

Then replace:

* ``{BASE_URL}`` by the base URL of Omeka,
* ``{MEDIA_QUERY}`` by a list of URL parameters separated by ``&`` (this will
  define which media will be displayed),
* ``{SITE_SLUG}`` by the identifier of an existing Omeka S site, and
* ``{TITLE}`` by the text to be displayed in the viewer header.

For instance:

.. code-block:: html

    <iframe
        src="https://omeka.example.com/s/home/mediaviewer/viewer/embed?mediaviewer_title=MediaViewer&item_id=23"
        height="450px"
        width="100%"
        allow="fullscreen"
        sandbox="allow-same-origin allow-scripts"
    ></iframe>

As a web component
------------------

.. warning::

    This feature is experimental

If iframes cannot be used, the viewer can also be embedded on another website
as a `web component <https://developer.mozilla.org/en-US/docs/Web/Web_Components>`_.

Add the following HTML code to your site:

.. code-block:: html

    <script src="{BASE_URL}/modules/MediaViewer/asset/js/mediaviewer-viewer.js"></script>
    <mediaviewer-viewer
        media-query="{MEDIA_QUERY}"
        site-slug="{SITE_SLUG}"
        style="display:block; height: 50vmin; min-height: 450px"
    >
        <span slot="title">{TITLE}</span>
    </mediaviewer-viewer>

Then replace:

* ``{BASE_URL}`` by the base URL of Omeka,
* ``{MEDIA_QUERY}`` by a list of URL parameters separated by ``&`` (this will
  define which media will be displayed),
* ``{SITE_SLUG}`` by the identifier of an existing Omeka S site, and
* ``{TITLE}`` by the text to be displayed in the viewer header.

For instance:

.. code-block:: html

    <script src="https://omeka.example.com/modules/MediaViewer/asset/js/mediaviewer-viewer.js"></script>
    <mediaviewer-viewer
        media-query="item_id=23"
        site-slug="home"
        style="display:block; height: 50vmin; min-height: 450px"
    >
        <span slot="title">All media of item #23</span>
    </mediaviewer-viewer>

.. note::

    The style is inlined for convenience, but you can remove it and use your
    own styles
