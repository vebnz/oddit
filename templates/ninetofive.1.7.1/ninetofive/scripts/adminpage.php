<?php

function mytheme_admin()
{
 global $themename, $shortname, $options;
 $i = 0;
 if ($_REQUEST['saved'])
  echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' ' . __('settings saved', '9to5') . '.</strong></p></div>';
 if ($_REQUEST['export'])
  echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' ' . __('settings imported', '9to5') . '.</strong></p></div>';
 if ($_REQUEST['reset'])
  echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' ' . __('settings reset', '9to5') . '.</strong></p></div>';
 ?>
    <div class="wrap rm_wrap">
        <div class="icon32" id="icon-options-general"><br></div>
 <h2><?php echo $themename; ?> <?php _e('Settings', '9to5') ?></h2>
        <div class="rm_opts">
            <form method="post">
            <?php
 foreach ($options as $value)
 {
  switch ($value['type'])
  {
   case "open":
    ?>
     <?php
    break;
   case "close":
    ?>
                </div>
            </div>
            <br/>
    <?php
    break;
   case "title":
    ?>
    <p>
     <?php _e('Setup and configure the ' . $themename . ' theme using the options below.'); ?>
    </p>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/colorpicker/colorpicker.css" type="text/css"/>


    <script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/colorpicker/colorpicker.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/colorpicker/eye.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/colorpicker/utils.js"></script>
    <script type="text/javascript"
            src="<?php echo get_template_directory_uri() ?>/scripts/colorpicker/layout.js?ver=1.0.2"></script>
    <script type="text/javascript">
     jQuery(document).ready(function () {
      jQuery('.rm_options').slideUp();

      jQuery('.rm_section h3').click(function () {
       if (jQuery(this).parent().next('.rm_options').css('display') === 'none') {
        jQuery(this).removeClass('inactive').addClass('active').children('img').removeClass('inactive').addClass('active');

       }
       else {
        jQuery(this).removeClass('active').addClass('inactive').children('img').removeClass('active').addClass('inactive');
       }

       jQuery(this).parent().next('.rm_options').slideToggle('slow');
      });

      //if(jQuery('.kfilters:visible').length >= 3)
      {
       //  jQuery('#newfilter').hide();
      }

      jQuery('#newfilter').click(function () {
       jQuery('#filter_3').show();
       jQuery('#deletefilter').show();
       jQuery('#newfilter').hide();
      });

      jQuery('#deletefilter').click(function () {
       jQuery('#filter_3').hide();
       jQuery('#filter_3').val('');
       jQuery('#deletefilter').hide();
       jQuery('#newfilter').show();
      });

      jQuery('#9t5_background_color').ColorPicker({
       onSubmit:function (hsb, hex, rgb, el) {
        jQuery(el).val(hex);
        jQuery(el).ColorPickerHide();
       },
       onBeforeShow:function () {
        jQuery(this).ColorPickerSetColor(this.value);
       }
      })
       .bind('keyup', function () {
        jQuery(this).ColorPickerSetColor(this.value);
       });

     });
    </script>

    <?php
    break;
   case 'text':
    ?>
    <div class="rm_input rm_text">
     <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
     <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>"
            value="<?php
            if (get_option($value['id']) != "") {
             $var = stripslashes(get_option($value['id']));
             echo str_replace('"', "&quot;", $var);
            }
            else
            {
             echo $value['std'];
            }
            ?>"/>
     <small><?php echo $value['desc']; ?></small>
     <div class="clearfix"></div>
    </div>

    <?php
    break;
   case 'fieldset':
    ?>
    <div class="rm_input rm_text">
     <div style="float:left">
      <label><?php echo $value['name']; ?></label><br/>
     </div>
     <div style="float:left" id="filtercontainer">
      <?php
      foreach ($value['fields'] as $field)
      {
       ?>
       <input class="kfilters" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="text"
              value="<?php
               if (get_option($field['id']) != "") {
                $var = stripslashes(get_option($field['id']));
                echo str_replace('"', "&quot;", $var);
               }
               else
               {
                echo $field['std'];
               } ?>"/> <?php
       if ($field['id'] == 'filter_3' && get_option($field['id']) == '') {
        //echo '<a href="#" id="deletefilter" style="display:none;">' . __('Delete', '9to5') . '</a>';
       }
       elseif ($field['id'] == 'filter_3')
       {
        //echo '<a href="#" id="deletefilter">' . __('Delete', '9to5') . '</a>';
       }
       ?>
       <br/>
       <?php } ?>
      <a href="#" id="nfilter" style="display: none"><?php _e('Add', '9to5') ?></a>
     </div>
     <div>
      <small><?php echo $value['desc']; ?></small>
      <div class="clearfix"></div>
     </div>
    </div>

    <?php
    break;
   case 'textarea':
    ?>
    <div class="rm_input rm_textarea">
     <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
     <textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php
      if (get_option($value['id']) != "") {
       echo stripslashes(get_option($value['id']));
      }
      else
      {
       echo $value['std'];
      }
      ?></textarea>
     <small><?php echo $value['desc']; ?></small>
     <div class="clearfix"></div>
    </div>
    <?php
    break;
   case 'select':
    ?>
    <div class="rm_input rm_select">
     <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
     <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
      <?php
      foreach ($value['options'] as $option)
      {
       ?>
       <option <?php
        $selected = get_option($value['id']);
        if ($selected == $option) {
         echo 'selected';
        }
        ?>><?php echo $option; ?></option>
       <?php } ?>
     </select>
     <small><?php echo $value['desc']; ?></small>
     <div class="clearfix"></div>
    </div>
    <?php
    break;
   case "checkbox":
    ?>
    <div class="rm_input rm_checkbox">
     <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
     <?php
     if (get_option($value['id'])) {
      $checked = "checked=\"checked\"";
     }
     else
     {
      $checked = "";
     }
     ?>
     <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
            value="true" <?php echo $checked; ?> />
     <small><?php echo $value['desc']; ?></small>
     <div class="clearfix"></div>
    </div>
    <?php
    break;
   case "section":
    $i++;
    ?>
    <div class="rm_section">
        <div class="rm_title"><h3><img
         src="<?php echo get_template_directory_uri() ?>/images/transparent.png" class="inactive"
         alt="pointer"><?php echo $value['name']; ?></h3><span class="submit"><input
         name="save<?php echo $i; ?>" type="submit"
         value="<?php _e('Save changes', '9to5') ?>"/>
            </span>

         <div class="clearfix"></div>
        </div>
        <div class="rm_options">
        <?php
    break;
  }
 }
 ?>
 <input type="hidden" name="action" value="save"/>
 </form>


 <form style="float:left; padding-right:10px;" method="post" onsubmit="return confirm('<?php _e('Warning: This will reset your settings back to the factory default settings! Continue?', '9to5') ?>');">
   <span class="submit">
       <input name="reset" type="submit" value="<?php _e('Reset', '9to5') ?>"/>
       <input type="hidden" name="action" value="reset"/>
   </span>
 </form>
 <form method="post" style="float:left; padding-right:10px;">
   <span class="submit">
       <input name="export" type="submit" value="<?php _e('Export settings', '9to5') ?>"/>
       <input type="hidden" name="action" value="export"/>
   </span>
 </form>
 <form style="float:left; padding-right:10px;">
   <span class="submit">
       <input name="import" type="button" value="<?php _e('Import settings', '9to5') ?>" onClick="jQuery('#uploadFile').slideToggle();"/>
   </span>
 </form>
 <form enctype="multipart/form-data" method="post" style="display:none;" id="uploadFile">
  <input type="file" name="file"/>
  <input type="hidden" name="action" value="import"/>
  <span class="submit">
      <input name="import" type="submit" value="<?php _e('OK', '9to5') ?>"/>
  </span>
 </form>
            </div>
<?php } ?>
