Cantaloupe
==========

We'll assume Omeka S is served by Apache on a Debian system.

Install Cantaloupe
------------------

Start by installing Java::

    apt-get install openjdk-11-jre-headless

Then install Cantaloupe using the `latest release
<https://github.com/cantaloupe-project/cantaloupe/releases/latest>`_ ZIP file::

    wget https://github.com/cantaloupe-project/cantaloupe/releases/download/v5.0.5/cantaloupe-5.0.5.zip
    unzip cantaloupe-5.0.5.zip
    mv cantaloupe-5.0.5 /opt/cantaloupe

Configure Cantaloupe
--------------------

Copy the sample configuration file::

    cp /opt/cantaloupe/cantaloupe.properties.sample /opt/cantaloupe/cantaloupe.properties

Then edit the copy. You will need to define the path to your pyramid images::

    FilesystemSource.BasicLookupStrategy.path_prefix = <path_to_pyramid_images>/

You can use `PyramidImageBuilder`_ to create these pyramid images. If that's
what you use, pyramid images will be located in ``<OMEKA_PATH>/files/pyramid/``.

.. _PyramidImageBuilder: https://github.com/biblibre/omeka-s-module-PyramidImageBuilder

.. note::

   The trailing slash in ``path_prefix`` is important!

Start Cantaloupe
----------------

The simplest way to start Cantaloupe is to run the following commands::

    cd /opt/cantaloupe
    java -Dcantaloupe.config=cantaloupe.properties -Xmx2g -jar cantaloupe-5.0.5.jar

Configure Apache to proxy requests to Cantaloupe
------------------------------------------------

Enable Apache modules ``proxy`` and ``proxy_http``::

    a2enmod proxy proxy_http

In Apache's ``<VirtualHost>`` for Omeka S (probably located in
``/etc/apache2/sites-enabled/``), add the following lines::

    ProxyPass "/iiif" "http://localhost:8182/iiif"
    ProxyPassReverse "/iiif" "http://localhost:8182/iiif"

Then restart Apache::

    systemctl restart apache2.service

Configure MediaViewer
---------------------

In the module configuration, change IIIF Image URI template to::

    https://<yourhost>/iiif/3/{storage_id}/info.json

This assumes the pyramid image files are named after the Omeka S media storage
ID, which is what `PyramidImageBuilder`_ does. If your images are named
differently, modify the URI template accordingly.
