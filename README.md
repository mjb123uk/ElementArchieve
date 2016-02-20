ElementArchive
==============

This Modx extra is a Custom Manager Page (CMP) that can be used to take a snapshot / archieve of the elements in your site.

It is useful during the development stages of a new web site foundation so that changes to the underlying templates and other
elements can be preserved at a point in the process.

This CMP does not copy any of the resources or assets, you should rely on backups or alternate extras for these.

You can create an archive with a defined title in a 'system setting' selected folder.  By default this is within the 
{assets_path}components/elementarchive/archive/ folder.  It is recommended that this be changed to somewhere out of the
web root e.g. if you have moved the core then {core_path}export/elementarchive/.  Note - if the path does not exist but
is writable then it will be created when you first launch the elementarchieve CMP.

The create/build option will save a copy of each element (Templates, Template Variables, Chunks, Snippets & Plugins)
in a structure that matches the element tree.

The content of Templates and Chunks are saved as 'HTML' files.

The content of Snippets and Plugins are saved as 'PHP' files.

Template Variables objects are stored as JSON data.

You can view the saved content files in a simple viewer, the JSON object data is viewed as a tree.

Credit to Gabor Turi ( http://jsonviewer.stack.hu/ ) for example of JSON tree for Extjs.

This CMP uses the Modx 2.3+ menu system and by default creates a menu entry under the Extras menu.

