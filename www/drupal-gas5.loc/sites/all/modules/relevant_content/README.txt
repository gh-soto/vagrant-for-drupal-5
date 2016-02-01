This module provides a set of blocks which contain a number of links to content
that is considered relevant to the current page based on how the current page is
tagged under it's taxonomy.

This module currently only works on node/% pages (where % is a node id). It is
possible to manually extend this by, for example, creating a view and overriding
it in your template.php. Then you can inject terms into the modules local
term_cache function.