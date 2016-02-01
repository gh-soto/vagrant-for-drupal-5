
UIEForum is a complete forum system, that is intended to replace the existing
drupal forum module.

This module's design is not based around the drupal node-system. It uses it's own tables for
storing forum data. This will change at some stage, but for now it's the easiest, and most
logical method to proceed.

UIEForum was originally developed as a 'homebrew', standalone forum for www.unreal.ie - and has
since been ported over to suit drupal's code structure and rules, as the site is due to move to
the drupal system in the coming months. This is still a work in progress, so I fully expect there
to be various bugs, however alot of work has gone into the development of the original system,
and now the drupal port, so here's hoping that it's not too buggy.

The module allows for the use of groups (roles) to provide security on specific forums, and as
such, can be a suitable replacement for users that are currently debating moving from a VBB
(or similar) environment.
At the moment, a fixed list of smilies are available, but this will be moved to a database list.

Development of this module is ongoing, so be sure to check back frequently.


List of Current Features:

Administration

   1. Unrestricted access for group 'administer uieforum'
   2. Security system integration with drupal
   3. Multiple custom private/public forum setups possible
   4. Multiple admin levels possible
      - Non recursive ... ie:
             --> Private Forum
               --> Private in private
          A mod in the 1st private forum, will NOT automatically be a mod in the forum within it
   5. Super-users can reply to locked threads
   6. Admin-configurable directories for forum images - Making it possible to change icon sets in
      seconds
   7. System wide disable abilities for:
      - BBCode
      - Smilies
      - Online status indicator
      - User ranks
      - Registered-date display
      - Post counts
      - Signatures
      - (user) Post counts
      - (forum) Thread + post counts
      - Auto-greeting for new forums (whether or not a new forum will have a new post inserted
        by default)
   8. Thread splitting/merging
   9. Post moving between threads
  10. Moderator access per-forum allows trusted users to help monitor posters
  11. Ability to add/remove email addresses used to send post-reports to

Forums

   1. Multiple forums and subforums are possible
   2. Forums are lockable (therefore making them "containers" ... ie: forums that allow sub-forums, 
      but not new threads/posts - existing threads/posts will remain there, untouched)
   3. Forum listing includes forum title, number of threads within that forum (and any subforums),
      number of posts within that forum (and any subforums) and a link to the last post within that
      forum (or any subforum)
   5. On the fly forum creation, deletion and moving
   6. Forums and Threads are easily recognisable by different icons alongside their titles

Threads

   1. Threads can be subscribed to by reply to them, and unsubscribed in the users's drupal control
      panel
   2. Threads are lockable and stickyable (is that a word?)
   3. Forums and Threads are easily recognisable by different icons alongside their titles
   4. Thread listing denotes new threads, locked threads, stickied threads and hot topics
   5. Thread listing includes thread title, creator, number of replies, number of views and a link
      to the last post in each thread
   6. Threads with new posts are denoted by distinct icons

Posts

   1. Post displays are in line with the 'accepted norm'
   2. Online status indicator
   3. User ranks available
   4. Display of avatars alongside posts
   5. Join date, post count, and user avatar display
   6. New Post page allows for insertion of certain BBCode, and has a formatting toolbar as an aid
   7. BBCode includes: QUOTE, CODE, IMG, URL, B(old), I(talic), U(nderline), RANT and more
   8. Preview Post option
   9. Editing of own posts (withing an admin-configurable time frame) is possible, and super-users
      can edit any post, at any time
  10. Editing of posts creates extra information directly below post to show that it has been edited
      (and when/why)
  11. Post-spam protection - Admin-configurable timeout prevents users from (accidentally?) posting
      more than one message in a given time frame
  12. Signature support for the built-in drupal signatures
  13. Post reporting is possible via an icon below every post (if enabled)

Themes/Images

   1. Smiley lists available and customisable
   2. Fully customisable icon set - can be replaced with any other set you wish. Note that the default
      icons have an Unreal Tournament slant to them as www.unreal.ie is a UT site.
   3. Forum Module is completely CSS customisable

General

   1. "Latest posts" block allows for the last $num posts to be posted on any forum (that a given user
      has access to) to be listed in a block on a normal page ($num is admin-conigurable)
   2. Window title contains current forum, or thread, or 'new post' to help to distinguish between history items and multiple windows
   3. Forum searching is possible, and is secured via the same security system mentioned above
