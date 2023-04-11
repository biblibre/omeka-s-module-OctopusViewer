Embed the viewer on another site
================================

As an iframe
------------

The easiest way to embed the viewer is by using ``<iframe>``.

Add the following HTML code to your site:

.. code-block:: html

    <iframe
        src="{BASE_URL}/s/{SITE_SLUG}/octopusviewer/viewer/embed?octopusviewer_title={TITLE}&{MEDIA_QUERY}"
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
        src="https://omeka.example.com/s/home/octopusviewer/viewer/embed?octopusviewer_title=OctopusViewer&item_id=23"
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

    <script src="{BASE_URL}/modules/OctopusViewer/asset/js/octopusviewer-viewer.js"></script>
    <octopusviewer-viewer
        media-query="{MEDIA_QUERY}"
        site-slug="{SITE_SLUG}"
        style="display:block; height: 50vmin; min-height: 450px"
    >
        <span slot="title">{TITLE}</span>
    </octopusviewer-viewer>

Then replace:

* ``{BASE_URL}`` by the base URL of Omeka,
* ``{MEDIA_QUERY}`` by a list of URL parameters separated by ``&`` (this will
  define which media will be displayed),
* ``{SITE_SLUG}`` by the identifier of an existing Omeka S site, and
* ``{TITLE}`` by the text to be displayed in the viewer header.

For instance:

.. code-block:: html

    <script src="https://omeka.example.com/modules/OctopusViewer/asset/js/octopusviewer-viewer.js"></script>
    <octopusviewer-viewer
        media-query="item_id=23"
        site-slug="home"
        style="display:block; height: 50vmin; min-height: 450px"
    >
        <span slot="title">All media of item #23</span>
    </octopusviewer-viewer>

.. note::

    The style is inlined for convenience, but you can remove it and use your
    own styles
