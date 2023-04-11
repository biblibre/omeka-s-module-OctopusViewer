IIPImage
========

We'll assume Omeka S is served by Apache on a Debian system.

Install IIPImage
----------------

Install IIPImage using the debian package::

    apt-get install iipimage-server

Disable the Apache module, we won't use it::

    a2dismod iipsrv
    systemctl restart apache2.service

Configure IIPImage
------------------

Create ``/etc/default/iipsrv`` with the following contents::

    FILESYSTEM_PREFIX=<path_to_pyramid_images>/
    URI_MAP=iiif=>IIIF
    BASE_URL=https://<yourhost>/iiif/

Replace ``<yourhost>`` by the hostname of your Omeka S installation

Replace ``<path_to_pyramid_images>`` by the absolute path of the directory that
contain your pyramid image files. You can use `PyramidImageBuilder`_ to create
these files.

If you use `PyramidImageBuilder`_, pyramid images will be located in
``<OMEKA_PATH>/files/pyramid/``.

.. _PyramidImageBuilder: https://github.com/biblibre/omeka-s-module-PyramidImageBuilder

.. note::

   The trailing slash in FILESYSTEM_PREFIX is important!

Once the configuration file is ready, restart IIPImage::

    systemctl restart iipsrv.service

Configure Apache to proxy requests to IIPImage
----------------------------------------------

Enable Apache modules ``proxy`` and ``proxy_fcgi``::

    a2enmod proxy proxy_fcgi

In Apache's ``<VirtualHost>`` for Omeka S (probably located in
``/etc/apache2/sites-enabled/``), add the following lines::

    ProxyPass "/iiif" "fcgi://localhost:9000/"
    ProxyPassReverse "/iiif" "fcgi://localhost:9000/"

Then restart Apache::

    systemctl restart apache2.service

Configure OctopusViewer
-----------------------

In the module configuration, change IIIF Image URI template to::

    https://<yourhost>/iiif/{storage_id}/info.json

This assumes the pyramid image files are named after the Omeka S media storage
ID, which is what `PyramidImageBuilder`_ does. If your images are named
differently, modify the URI template accordingly.
