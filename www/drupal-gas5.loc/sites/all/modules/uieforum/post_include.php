<?php
/** Smiley + Formatting specific code. This will eventually be database driven, but for now, hard coding will do **/

	drupal_add_js(drupal_get_path('module', uieforum_get_module_name()) .'/js/new_post_scripts.js');

	$uie_rows = array();
  if(variable_get('uieforum_use_bbcode', 1))
  {
    $fff_rows = uieforum_get_formatting_icons();
		$formatting_table = null;
		if(count($fff_rows))
		{
	    $formatting_table = theme('table', null, $fff_rows, array('class' => 'forum'));
		}

    if(variable_get('uieforum_use_smilies', 1))
      $uie_rows[] = array(
        array('data' => null),
        array('data' => $formatting_table)
      );
    else
      $uie_rows[] = array(
        array('data' => $formatting_table)
      );
  }


/** Smiley + Formatting specific code. This will eventually be database driven, but for now, hard coding will do **/
  if(variable_get('uieforum_use_smilies', 1))
  {
    $smiley_rows = array();
    $SmilieSamples = uieforum_get_smiley_samples();
    $smilies_array = uieforum_get_smiley_list();
    foreach ($SmilieSamples as $SmilieSample)
    {
      $tt_cols = array();

      foreach ($SmilieSample as $Smilie)
        $tt_cols[] = '<a href="javascript:Process(\''.$smilies_array[$Smilie][0].'\')">'.$smilies_array[$Smilie][2].'</a>';
      $smiley_rows[] = $tt_cols;
    }
  }

/** Generate actual table **/
  if($_POST['PreviewPost'] && !form_get_errors())
  {
    $form = drupal_validate_form('unreal', $uieforum_form);
    $_POST = NULL;
  }
  $form = drupal_get_form('uieforum_submit_post_form', $editpost);

  if(variable_get('uieforum_use_smilies', 1))
  {
    $uieforum_form['#method'] = 'post';
    $uieforum_form['#action'] = null;
    $uieforum_form['#attributes'] = array('name' => 'unreal');

    $uie_rows[] = array(
      array('data' => theme('table', null, $smiley_rows), 'style' => 'vertical-align: top;'),
      array('data' => $form, 'style' => 'vertical-align: top;')
    );
  }
  else
  {
    $uieforum_form['#method'] = 'post';
    $uieforum_form['#action'] = null;
    $uieforum_form['#attributes'] = array('name' => 'unreal');

    $uie_rows[] = array(
      array('data' => $form)
    );
  }

  $ForumTable .= theme('table', null, $uie_rows);
