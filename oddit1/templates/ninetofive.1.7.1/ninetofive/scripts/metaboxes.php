<?php
$meta_boxes =
 array(
  "company_name" => array(
   "name" => "company_name",
   "type" => "text",
   "colour" => "#fff",
   "std" => "",
   "title" => __('Company Name', '9to5'),
   "description" => __('Please enter the name of the company under which you would like this job to be posted.', '9to5')),
  "location" => array(
   "name" => "location",
   "type" => "text",
   "colour" => "#efefef",
   "std" => __('Anywhere', '9to5'),
   "title" => __('Location', '9to5'),
   "description" => __('Please enter the location of the position, please format it with the city name followed by the state or country e.g. SAN DIEGO, CA', '9to5')),
  "position_type" => array(
   "name" => "position_type",
   "type" => "dropdown",
   "colour" => "#fff",
   "std" => get_option('filter_1'),
   "options" => array(get_option('filter_1'), get_option('filter_2'), get_option('filter_3')),
   "title" => __('Position Type', '9to5'),
   "description" => __('Please select the position type of the job.', '9to5')),
  "skills" => array(
   "name" => "skills",
   "type" => "list",
   "colour" => "#efefef",
   "std" => "",
   "title" => __('Skills', '9to5'),
   "description" => __('Please enter any particular skills you would like the applicant to posses and a list will be automatically generated. Separate with a comma e.g. HTML Skills, CSS Skills', '9to5')),
  "salary" => array(
   "name" => "salary",
   "type" => "text",
   "colour" => "#fff",
   "std" => '',
   "title" => __('Salary', '9to5'),
   "description" => __('Please, indicate the proposed salary including currency symbol or letters.', '9to5')),
  "salary_description" => array(
   "name" => "salary_description",
   "type" => "textarea",
   "colour" => "#efefef",
   "std" => "",
   "title" => __('Benefits', '9to5'),
   "description" => __('Fill in as much detail as you can what can you offer the applicant.', '9to5')),
  "how_to_apply" => array(
   "name" => "how_to_apply",
   "type" => "textarea",
   "colour" => "#fff",
   "std" => "",
   "title" => __('How to Apply', '9to5'),
   "description" => __('Please enter any information you would like applicants to send to you, and the means by which to do so e.g. Please send examples of past work and your CV to info@example.com', '9to5')),
  "expire" => array(
   "name" => "exp",
   "type" => "text",
   "colour" => "#efefef",
   "std" => "",
   "title" => __('Expiry Date', '9to5'),
   "description" => __('Please enter an expiry date for this job listing in the format YYYY/MM/DD - without any spaces. If you don\'t want this job listing to expire enter \"noexpire\" without the quotation marks.', '9to5')),
  "map" => array(
   "name" => "map",
   "type" => "textarea",
   "colour" => "#fff",
   "std" => "",
   "title" => __('Map Code', '9to5'),
   "description" => __('Please enter the map code, this can be found at Google Maps.', '9to5')),
  "enableapply" => array(
   "name" => "enableapply",
   "type" => "checkbox",
   "colour" => "#efefef",
   "std" => "off",
   "title" => __('Application Form', '9to5'),
   "description" => __('Allow users to apply for this job on the listing page.', '9to5')),
  "email" => array(
   "name" => "email",
   "type" => "text",
   "colour" => "#fff",
   "std" => "",
   "title" => __('Application Form', '9to5'),
   "description" => __('If you enable the application form above, enter an email address here to send applications to.', '9to5')),
  "email_message" => array(
   "name" => "email_message",
   "type" => "textarea",
   "colour" => "#efefef",
   "std" => "",
   "title" => __('Email Message', '9to5'),
   "description" => __('If you enable the application form above, enter a message to send to applications who (the message will always begin with Hi [Applicant Name],).', '9to5')),
 );

function meta_boxes()
{
 global $post, $meta_boxes;
 echo'
  <table class="widefat" cellspacing="0" id="inactive-plugins-table">
   <tbody class="plugins">';
 foreach ($meta_boxes as $meta_box)
 {
  $meta_box_value = get_post_meta($post->ID, $pre . '_value', true);
  if ($meta_box_value == "")
   $meta_box_value = $meta_box['std'];
  if ($meta_box['type'] == 'text') {
   echo'<tr style="background-color:' . $meta_box['colour'];
   echo '">
       <td width="100" align="center">';
   echo'<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
   echo'<h4 style="width: 125px; font-size:14px; margin-top:22%; text-align:left;">' . $meta_box['title'] . '</h4>';
   echo'	</td>
       <td>';

   if ($meta_box['name'] == 'email') {

    $defaultEmail = get_bloginfo("admin_email");
   }
   ?><br/><br/>
  <input type="text" name="<?php echo $meta_box['name'] ?>_value" value="<?php
   if ($meta_box['name'] == "map") {
    echo htmlentities(get_post_meta($post->ID, $meta_box['name'] . '_value', true), ENT_QUOTES);
   }
   else if ($meta_box['name'] == 'exp' && get_post_meta($post->ID, $meta_box['name'] . '_value', true) == NULL) {
    echo date('Y/m/d', strtotime("+30 days"));
   }
   else
   {
    echo get_post_meta($post->ID, $meta_box['name'] . '_value', true);
   }if (get_post_meta($post->ID, $meta_box['name'] . '_value', true) == "" && $meta_box['name'] == 'email') {
    echo $defaultEmail;
   }?>" style="width:98%" size=""/><br/>

  <p><label for="<?php echo $meta_box['name'] ?>'_value"><?php echo $meta_box['description'] ?></label></p>
  <?php
   echo'	</td>
      </tr>';
  }
  else if ($meta_box['type'] == "dropdown") {
   $value = get_post_meta($post->ID, $meta_box['name'] . '_value', true);

   echo'<tr style="background-color:' . $meta_box['colour'];
   echo '">
       <td width="100" align="center">';
   echo'<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
   echo'<h4 style="width: 125px; font-size:14px; margin-top:30%; text-align:left;">' . $meta_box['title'] . '</h4>';
   echo'	</td>
       <td>';

   $selected_1 = "";
   $selected_2 = "";
   $selected_3 = "";

   if ($value == 'filter_1') {
    $selected_1 = 'selected="selected"';
   } else if ($value == 'filter_2') {
    $selected_2 = 'selected="selected"';
   } else if ($value == 'filter_3') {
    $selected_3 = 'selected="selected"';
   }


   echo'<br /><br /><br /><select name="' . $meta_box['name'] . '_value">
                 <option name="filter_1" value="filter_1" ' . $selected_1 . '>' . get_option('filter_1') . '</option>
                 <option name="filter_2" value="filter_2" ' . $selected_2 . '>' . get_option('filter_2') . '</option>
                 <option name="filter_3" value="filter_3" ' . $selected_3 . '>' . get_option('filter_3') . '</option>
                 </select>
                 <br />';

   echo'<p><label for="' . $meta_box['name'] . '_value">' . $meta_box['description'] . '</label></p>';
   echo'	</td>
     </tr>';
  }
  else if ($meta_box['type'] == "textarea") {
   echo'<tr style="background-color:' . $meta_box['colour'];
   echo '">
       <td width="100" align="center">';
   echo'<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
   echo'<h4 style="width: 125px; font-size:14px; margin-top:28%; text-align:left;">' . $meta_box['title'] . '</h4>';
   echo' </td>
       <td>';
   echo'<br /><br /><br />
        <textarea tabindex="8" style="width:98%; height:120px;" name="' . $meta_box['name'] . '_value" id="metavalue">';
   $value = get_post_meta($post->ID, $meta_box['name'] . '_value', true);
   echo $value . '</textarea>
        <br />';
   echo'<p><label for="' . $meta_box['name'] . '_value">' . $meta_box['description'] . '</label></p>';
   echo'	</td>
       </tr>';
  }
  else if ($meta_box['type'] == "list") {
   echo'<tr style="background-color:' . $meta_box['colour'];
   echo '">
       <td width="100" align="center">';
   echo'<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
   echo'<h4 style="width: 125px; font-size:14px; margin-top:14%; text-align:left;">' . $meta_box['title'] . '</h4>';
   echo'	</td>
       <td>';
   $skills = get_post_meta($post->ID, $meta_box['name'] . '_value', true);
   $savedSkills = explode(",", $skills);
   $counter = 0;
   $maxskills = get_option("9t5_max_skills");

   echo'<br /><div class="skills_list">';

   /*foreach($savedSkills as $skill)
   {
       echo '<input type="text" class="skill" name="' . $meta_box['name'] . '_value_' . $counter . '" id="' . $counter . '" value="' . $savedSkills[$counter] . '" style="width:98%" size="" /><br />';
       $counter++;
   }*/

   while ($savedSkills[$counter])
   {

    echo '<input type="text" class="skill" name="' . $meta_box['name'] . '_value_' . $counter . '" id="' . $counter . '" value="' . htmlspecialchars(stripslashes($savedSkills[$counter])) . '" style="width:98%" size="" /><br />';

    $counter++;
   }

   $hiddenskills = $maxskills - $counter;
   $hiddenboxes = $maxskills - $counter; //For JS

   while ($hiddenskills > 0)
   {
    echo '<input type="text" class="skill hidden" name="' . $meta_box['name'] . '_value_' . $counter . '" id="' . $counter . '" value="' . htmlspecialchars(stripslashes($savedSkills[$counter])) . '" style="width:98%; display:none;" size="" />';

    $hiddenskills--;
    $counter++;
   }

   if ($skills == "") {
    echo '<input type="text" class="skill" name="' . $meta_box['name'] . '_value_' . $counter . '" id="' . $counter . '" value="' . htmlspecialchars(stripslashes($savedSkills[$counter])) . '" style="width:98%" size="" /><br />';

    $counter++;
    $hiddenboxes--;
   }

   echo '</div><br/><a href="#" id="addskill" name="addskill">[+] ' . _e('Add another', '9to5') . '</a>';
   echo'<p><label for="' . $meta_box['name'] . '_value">' . $meta_box['description'] . '</label></p>
     <textarea id="skillsBox" name="skills_value" style="display:none;"></textarea>';
   echo'</td></tr>';
   ?>

  <script type="text/javascript">
   jQuery(document).ready(function () {
    jQuery("#publish").addClass("skillAdder");
    var hiddenboxes = <?php echo $hiddenboxes ?>;
    var maxskills = <?php echo get_option("9t5_max_skills"); ?>;
    jQuery("#addskill").click(function () {
     //console.log("Hidden boxes: "+hiddenboxes+", Max: "+maxskills);
     if (hiddenboxes != 0) {
      jQuery(".skills_list").children("#" + (maxskills - hiddenboxes)).show();
      jQuery(".skills_list").children("#" + (maxskills - hiddenboxes)).removeClass("hidden");
      hiddenboxes--;
     } else {
      jQuery(this).hide();
     }
     return false;
    });
    jQuery("#publish").click(function () {
     jQuery("#skillsBox").text('').append(jQuery("input.skill").map(
      function () {
       return jQuery(this).val() || null;
      }).get().join(","));
     jQuery(".skill").remove();
    });
   })
  </script>

  <?php
  }
  else if ($meta_box['type'] == "checkbox") {
   echo'<tr style="background-color:' . $meta_box['colour'];
   echo '">
   <td width="100" align="center">';
   echo'<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
   echo'<h4 style="width: 125px; font-size:14px; margin-top:15%; text-align:left;">' . $meta_box['title'] . '</h4>';
   echo'	</td>
   <td>';
   echo'<br />
   <p><input type="checkbox" tabindex="8" style="margin-right:10px;" name="' . $meta_box['name'] . '_value"';
   $value = get_post_meta($post->ID, $meta_box['name'] . '_value', true);
   if ($value == "yes" || $value == "on") {
    echo 'checked="checked"';
   }
   echo '/><label for="' . $meta_box['name'] . '_value">' . $meta_box['description'] . '</label></p>';
   ;
   echo'	</td>
    </tr>';
  }
 }
 echo'
  </tbody>
 </table>';
}

function create_meta_box()
{
 global $theme_name;
 if (function_exists('add_meta_box')) {
  add_meta_box('new-meta-boxes', __('Job Options', '9to5'), 'meta_boxes', 'post', 'normal', 'high');
 }
}

function save_postdata($post_id)
{
 global $post, $meta_boxes;
 foreach ($meta_boxes as $meta_box)
 {
  if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
   return $post_id;
  }
  if ('page' == $_POST['post_type']) {
   if (!current_user_can('edit_page', $post_id))
    return $post_id;
  } else
  {
   if (!current_user_can('edit_post', $post_id))
    return $post_id;
  }
  $data = $_POST[$meta_box['name'] . '_value'];
  if (get_post_meta($post_id, $meta_box['name'] . '_value') == "")
   add_post_meta($post_id, $meta_box['name'] . '_value', $data, true);
  elseif ($data != get_post_meta($post_id, $pre . '_value', true))
   update_post_meta($post_id, $meta_box['name'] . '_value', $data);
  elseif ($data == "")
   delete_post_meta($post_id, $meta_box['name'] . '_value', get_post_meta($post_id, $meta_box['name'] . '_value', true));
 }
}

add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');
?>
