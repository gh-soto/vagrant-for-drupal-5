// $Id: jlightbox_inline.js,v 1.1.2.1 2007/09/28 14:11:52 sun Exp $
/**
 * jLightbox Inline integration.
 * 
 * Automatically adds all inline images in a node to one jLightbox gallery.
 */
if (Drupal.jsEnabled) {
		$(document).ready(function() {
				$('.inline-image-link').each(function() {
					this.rel = 'lightbox[inline]';
					$(this).attr('onclick', 'return false;');
				});
		});
}
