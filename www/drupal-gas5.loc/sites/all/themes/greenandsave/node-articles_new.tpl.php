<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php if ($page == 0): ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

 <?php if ($teaser): ?>
 
<div class="content">
<?php print $node->teaser ?>
</div>

<?php else: ?>

<div class="content">
<div id="articlesbreadcrumbs"><?php
$tax = $node->taxonomy;
$keys = array_keys($tax);
$tid = $keys[0];
$parentsTerms = taxonomy_get_parents_all($tid);

foreach($parentsTerms as $key => $parentsTerm){
		if ($key == 0) {
			$output .= isset($nid) ? l($parentsTerm->name, 'articles/' . $parentsTerm->vid . '/' . $parentsTerm->tid) : $parentsTerm->name;
		} else {
			$output = l($parentsTerm->name, 'articles/' . $parentsTerm->vid . '/' . $parentsTerm->tid) . '<span class="articleslist_breadcrumbs_separator">&nbsp;>&nbsp;</span>' . $output;
		}	
	}
	$vocabulary = isset($temp_vid) ? taxonomy_get_vocabulary($temp_vid) : taxonomy_get_vocabulary($parentsTerm->vid);
	if ($tid == null) {
	 	$output =  '<div class="articleslist_breadcrumbs">' . 
    				l('Home', '') . '<span class="articleslist_breadcrumbs_separator">&nbsp;>&nbsp;</span>' . 
    				$vocabulary->name . '</div>';
	} else {
		$nodeTitle = isset($node) ? '<span class="articleslist_breadcrumbs_separator">&nbsp;>&nbsp;</span>' .
			$node->title : '';
		$output =   '<div class="articleslist_breadcrumbs">' . 
			l('Home', '') . '<span class="articleslist_breadcrumbs_separator">&nbsp;>&nbsp;</span>' . 
			l($vocabulary->name, 'articles/' . $parentsTerm->vid) . '<span class="articleslist_breadcrumbs_separator">&nbsp;>&nbsp;</span>' . 
			$output . 
			$nodeTitle .
		'</div>';
	}
	print $output;
    
?></div>

<?php print $node->content['#children'] ?>

</div>

  <?php if ($links): ?>
      <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
<?php endif; ?>

</div>