<?php
 /* Template Name: Lost Password */
get_header();?>
<div class="focus">
 <?php
 if (have_posts()) : while (have_posts()) : the_post();
  ?>

  <div class="post whiteboard">

   <div class="title <?php echo get_option('9t5_color_tertiary'); ?>">
    <h1>
     <?php the_title() ?>
    </h1>
    <?php the_content(); ?>
   </div>

   <div class="description">

    <?php
    global $auth_errors;
    global $auth_messages;
    if (isset($auth_errors)) {
     echo '<div class="message inline red"><div class="push">';
     foreach ($auth_errors as $error)
     {
      echo '<p>' . $error . '<p/>';
     }
     echo '</div></div>';
    }
    if (isset($auth_messages)) {
     echo '<div class="message inline green"><div class="push">';
     foreach ($auth_messages as $message)
     {
      echo '<p>' . $message . '<p/>';
     }
     echo '</div></div>';
    }
    ?>

    <form method="post" class="loginform">
     <div class="form_post">
      <div class="fieldset_set">
       <fieldset class="first last">
        <label class="label" for="regusername"><?php _e('Username or Email', '9to5') ?></label>

        <div class="field mod">
         <div class="input_wrap">
          <input name="ident" type="text" id="regusername"/>
         </div>
        </div>
       </fieldset>
      </div>
      <div class="button_set mod">
       <input name="action" type="hidden" value="lost"/>
       <input type="submit" value="<?php _e('Recover Password', '9to5') ?>"/>
      </div>
     </div>
    </form>

   </div>

  </div>

  <?php endwhile;
 endif; ?>

</div>

<?php get_sidebar();
get_footer(); ?>

