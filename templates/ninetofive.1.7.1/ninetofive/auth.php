<?php 
/* Template Name: Log In or Register */
get_header(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery.validate.min.js"></script>
<script type="text/javascript">
 jQuery(document).ready(function () {
  jQuery("#loginForm").validate({
   rules:{
    username:{
     required:true
    },
    password:{
     required:true
    }
   },
   messages:{
    username:{
     required:"<?php _e('Username is required!', '9to5') ?>"
    },
    password:{
     required:"<?php _e('Password is required!', '9to5') ?>"
    }
   },
   errorPlacement:function (error, element) {
    var er = element.attr("name");
    jQuery('#label' + er).removeClass('yes');
    jQuery('#label' + er).addClass('no');
   }
  });
  jQuery('.validation').blur(function () {
   if (jQuery(this).valid()) {
    var nm = jQuery(this).attr("name");
    jQuery('#label' + nm).removeClass('no');
    jQuery('#label' + nm).addClass('yes');
   }
  });
  jQuery("#registerForm").validate({
   rules:{
    regusername:{
     required:true
    },
    regemail:{
     required:true,
     email:true
    },
    regpassword:{
     required:true
    }
   },
   messages:{
    regusername:{
     required:"<?php _e('Username is required!', '9to5') ?>"
    },
    regemail:{
     required:"<?php _e('Email is required.', '9to5') ?>",
     email:"<?php _e('Incorrect email format.', '9to5') ?>"
    },
    regpassword:{
     required:"<?php _e('Password is required!', '9to5') ?>"
    }
   },
   errorPlacement:function (error, element) {
    var er = element.attr("name");
    jQuery('#label' + er).removeClass('yes');
    jQuery('#label' + er).addClass('no');
   }
  });
  jQuery('.validation').blur(function () {
   if (jQuery(this).valid()) {
    var nm = jQuery(this).attr("name");
    jQuery('#label' + nm).removeClass('no');
    jQuery('#label' + nm).addClass('yes');
   }
  });
 });
</script>
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
    if (!is_user_logged_in()) {
     global $auth_errors;

     if (get_option('pass_change') == 'true') {
      update_option('pass_change', 'false');
      echo '<div class="required">' . _e('New password was sent to you by email.', '9to5') . '</div>';
     }

     if (isset($auth_errors)) {
      echo '<div align="center" class="required">';
      foreach ($auth_errors as $error)
      {
       echo $error . '<br/>';
      }
      echo '</div>';
     }
     ?>
     <form method="post" id="loginForm" class="loginform">
      <div class="form_post">
       <h3><?php _e('Log In', '9to5') ?></h3>

       <div class="fieldset_set">
        <fieldset class="first">
         <span class="required" id="labelusername">Required</span>
         <label class="label" for="username"><?php _e('Username', '9to5') ?></label>

         <div class="field mod">
          <div class="input_wrap">
           <input id="username" class="validation" name="username" type="text"/>
          </div>
         </div>
        </fieldset>
        <fieldset class="last">
         <span class="required" id="labelpassword">Required</span>
         <label class="label" for="password"><?php _e('Password', '9to5') ?></label>

         <div class="field mod">
          <div class="input_wrap">
           <input id="password" class="validation" name="password" type="password"/>
          </div>
         </div>
        </fieldset>
       </div>
       <div class="button_set mod">
        <input name="action" type="hidden" value="login"/>
        <input id="loginsubmit" name="loginsubmit" type="submit" value="<?php _e('Login', '9to5') ?>"/>

        <p>
         <?php
         $lost_password_link = get_option('9t5_link_password');
         if ($lost_password_link == "") {
          $lost_password_link = 'wp-login.php?action=lostpassword';
         }
         ?>
         <a href="<?php echo site_url() ?>/<?php echo $lost_password_link; ?>"
            title="<?php _e('Lost Password', '9to5') ?>"><?php _e('Lost Password', '9to5') ?></a>
        </p>
       </div>
      </div>
     </form>

     <form method="post" id="registerForm" class="loginform">
      <div class="form_post">
       <h3><?php _e('Register', '9to5') ?></h3>

       <div class="fieldset_set">
        <fieldset class="first">
         <span class="required" id="labelregusername">Required</span>
         <label class="label" for="regusername"><?php _e('Username', '9to5') ?></label>

         <div class="field mod">
          <div class="input_wrap">
           <input id="regusername" class="validation" name="regusername" type="text"/>
          </div>
         </div>
        </fieldset>
        <fieldset>
         <span class="required" id="labelregemail">Required</span>
         <label class="label" for="regemail"><?php _e('Email', '9to5') ?></label>

         <div class="field mod">
          <div class="input_wrap">
           <input id="regemail" class="validation" name="regemail" type="text"/>
          </div>
         </div>
        </fieldset>
        <fieldset class="last">
         <span class="required" id="labelregpassword">Required</span>
         <label class="label" for="regpassword"><?php _e('Password', '9to5') ?></label>

         <div class="field mod">
          <div class="input_wrap">
           <input id="regpassword" class="validation" name="regpassword" type="password"/>
          </div>
         </div>
        </fieldset>
       </div>
       <div class="button_set mod">
        <input name="action" type="hidden" value="registration"/>
        <input id="regsubmit" name="regsubmit" type="submit" value="<?php _e('Register', '9to5') ?>"/>
       </div>
      </div>
     </form>

     <?php
    }
    else
    {
     ?>
    <div class="cms">
     <h3><?php _e('You already logged in!', '9to5') ?></h3><br/>
     <p><?php _e('You will be redirected now.', '9to5') ?></p>
   </div>
     <script type="text/javascript">
      jQuery(document).ready(function () {
       idTimer = window.setTimeout("window.location = '<?php echo $Magnet->post_a_job_url; ?>'", 3000);
      });
     </script>
     <?php } ?>
   </div>

  </div>

  <?php endwhile;
 endif; ?>

</div>

<?php get_sidebar();
get_footer(); ?>

