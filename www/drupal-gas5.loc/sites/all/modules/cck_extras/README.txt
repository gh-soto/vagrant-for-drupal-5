$Id: README.txt,v 1.1.2.1 2007/02/26 16:40:23 eaton Exp $

CCK Extras
==========

CCK offers a bunch of swanky hooks for editing and displaying the contents of assorted fields. CCK Extras is a module that adds a few convenient (but sometimes esoteric) formatters and editing widgets to popular field modules.


1PixelOut and Button formatters
===============================
AudioField.module, part of the MediaField package, is cool. But it doesn't allow you to display your audio fields as friendly-looking flash widgets the way audio.module does. With these two formatters, you can choose between a swanky-looking slide-out flash player, and an ultra-compact 'button' player.

The button player in particular is nice for use in table views, where you want to display a bunch of audio nodes and let visitors play mp3 files without leaving the listing page.


Textarea
========
Text.module lets you store arbitary chunks of text and display them. Sometimes, though, you want to store stuff that your visitors will copy and paste into another location (for example, a Youtube embed code or a Myspace style snippet). The Textarea formatter lets you output a text field as an actual form element, filled with the contents of the text field.


Raw Node ID
===========
Node Reference module lets you link nodes to each other in a variety of nifty ways. If all you want to do is type in a node ID, though, you're out of luck. This editing widget lets you do just that: type in a nid and nothing else. It still validates properly against the list of allowed nodes.


Viewfield: hidden defaults
==========================
Viewfield module lets you embed a view inside a node type. It's great for creating, say, 'Album' nodes that automatically list all associated 'Track' nodes. Unfortunately, you have to choose the view to display and the args to pass into it every time you create a new node. 'Hidde defaults' is an editor widget for Viewfield fields that hides those fields fields on the node editing form, and *always* populates them with the content type's default values. 