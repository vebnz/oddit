<?php get_header(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/fileuploader.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery.progressbar.min.js"></script>
<div class="focus">
    <div class="listing whiteboard">

        <?php 
    $date = date("dmy");
        $yesterday = date("dmy", strtotime("-1 day")); 
    if (get_option("9t5_new_days") == "")
         {
             $days = 1;
         }
         else
         {
             $days = get_option("9t5_new_days");
         }

         $newtagdisplay = date("m/d/y", time() - ($days * 86400));
    ?>
        <?php if (have_posts ()) : while (have_posts ()) : the_post(); ?>

                <div class="title <?php echo get_option('9t5_color_tertiary'); ?>">
            <?php
                $post_time = get_the_time("m/d/y");
                if (strtotime($post_time) > strtotime($newtagdisplay))
                {
            ?><em class="new <?php echo get_option('9t5_color_secondary'); ?>"><?php _e('New', '9to5'); ?></em><?php } ?>
                <div>
                    <h1>
                    <?php the_title() ?>
                </h1>
            </div>
            <?php
                    if (get_option('9t5_share_job') == "Enabled")
                    {
            ?>
                        <div class="share">
                            <a href="http://twitter.com/home?status=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-twitter.gif"/></a>
                            <a href="http://www.facebook.com/sharer.php?u=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-fb.gif"/></a>
                            <a href="http://del.icio.us/post/%3furl=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-delicious.gif"/></a>
                            <a href="http://digg.com/submit?url=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-digg.gif"/></a>
                            <a href="http://www.addtoany.com/add_to/instapaper?linkurl=<?php echo site_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-instapaper.gif"/></a>
                        </div>
            <?php } ?>
                    <p>
                <?php echo get_post_meta($post->ID, 'company_name_value', true); ?>
                </p>
            </div>


            <div class="sub <?php echo get_option('9t5_color_secondary'); ?>">

                <div class="meta">
                <?php
                    $category = get_the_category();
                    $count = count($category);
                    for ($id = 0; $id < $count; $id++)
                    {


                        echo "<a href='" . site_url() . "/category/" . $category[$id]->category_nicename . "'><span>" . $category[$id]->cat_name . "</span></a>";
                    }
                ?>
                </div>
                <h2>
                <?php _e('This is a', '9to5'); ?>
                    <strong><?php echo get_option(get_post_meta($post->ID, 'position_type_value', true)); ?></strong>
                <?php
                    _e('position', '9to5');
                    if (get_post_meta($post->ID, 'location_value', true) != "")
                    {
                        echo ' ' . __('in', '9to5');
                ?><strong> <?php
                        echo get_post_meta($post->ID, 'location_value', true);
                ?></strong><?php
                    }
                    echo ' ' . __('posted', '9to5');
                ?> <strong><?php
                    if ($post_time == $date)
                    {
                        _e('Today', '9to5');
                    }
                    else
                    {
                        echo the_time("M d");
                    }
                ?></strong>.
            </h2>

        </div>

        <div class="description cms">
            <p><a><?php the_post_thumbnail(); ?></a></p>
            <?php the_content(); ?>
                </div>

        <?php
                    if (get_post_meta($post->ID, 'skills_value', true))
                    {
        ?>
                        <div class="jobskills">
                            <div class="subtitle"><h3><?php _e('Skills Required', '9to5'); ?></h3></div>
                            <ul>
                <?php
                        $skills = explode(",", get_post_meta($post->ID, 'skills_value', true));
                        $counter = count($skills);
                        for ($i = 0; $i < $counter; $i++)
                        {
                            echo '<li>' . esc_html($skills[$i]) . '</li>';
                        }
                ?>
                    </ul>
                </div>
        <?php } ?>

        <?php if(get_post_meta($post->ID, 'salary_value', true) || get_post_meta($post->ID, 'salary_description_value', true)): ?>
                    <div class="package">
                        <div class="subtitle"><h3><?php _e('Package', '9to5'); ?></h3></div>
                        <div>
                            <?php if(get_post_meta($post->ID, 'salary_value', true)): ?>
                            <p><span>Salary:</span> <?php echo get_post_meta($post->ID, 'salary_value', true)?></p>
                            <?php endif; if(get_post_meta($post->ID, 'salary_description_value', true)): ?>
                            <p><span>Benefits:</span> <?php echo get_post_meta($post->ID, 'salary_description_value', true)?></p>
                            <?php endif; ?>
                        </div>
                    </div>
        <?php endif; ?>

        <?php
                    if (get_post_meta($post->ID, "map_value", true) != "")
                    {
                        echo str_replace('\\', "", get_post_meta($post->ID, "map_value", true));
                    }
        ?>

                    <div class="apply">
                        <div class="subtitle"><h3><?php _e('How to Apply', '9to5'); ?></h3></div>

            <?php if (get_post_meta($post->ID, 'enableapply_value', true) == "on" || get_post_meta($post->ID, 'enableapply_value', true) == "yes") : ?>
                        <!-- NEW FORM START -->
                        <img src="<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbg_red.gif" style="display:none;">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbg_orange.gif" style="display:none;">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbg_green.gif" style="display:none;">
            <?php
                        $fileTypes = get_option('9t5_filetypes_allowed');
                        $fileTypes = explode(',', $fileTypes);
      $tempArray = array();
                        foreach ($fileTypes as $filetype)
                        {
                            $tempArray[] = "'" . $filetype . "'";
                        }
                        $fileTypes = implode(', ', $tempArray);
                        $maxFileSize = get_option('9t5_files_maxsize');
                        $realFileMaxSize = ini_get('upload_max_filesize');
                        $realFileMaxSize = str_replace('M', '', $realFileMaxSize);
                        if ($realFileMaxSize < $maxFileSize)
                            $maxFileSize = $realFileMaxSize;
                        $maxFileSize = $maxFileSize * 1024 * 1024;
            ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function(){
                                var filesCounter = 0;
                                var uploadStarted = false;
                                var uploader = new qq.FileUploader({
                                    element: document.getElementById('file-uploader'),
                                    action: '<?php echo get_template_directory_uri(); ?>/scripts/doajaxfileupload.php',
                                    allowedExtensions: [<?php echo  $fileTypes ?>],
                                    sizeLimit: <?php echo  $maxFileSize ?>,
                  multiple: false,
                                    onSubmit: function(id, fileName){
                                        uploadStarted = true;
                                        jQuery('#uploadResults').append('<div>'+fileName+'</div><span id="progress_'+id+'"></span>');
                                        jQuery('#progress_'+id).progressBar({
                                            boxImage: '<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbar.gif',
                                            barImage: {
                                                0:  '<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbg_red.gif',
                                                30: '<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbg_orange.gif',
                                                70: '<?php echo get_template_directory_uri(); ?>/images/progressbar/progressbg_green.gif'
                                            }
                                        });
                                    },
                                    onProgress: function(id, fileName, loaded, total){
                                        var percentage = Math.floor(100 * loaded / total);
                                        jQuery('#progress_'+id).progressBar(percentage);
                                    },
                                    onComplete: function(id, fileName, responseJSON){
                                        filesCounter++;
                                        jQuery('#progress_'+id).progressBar(30);
                                        jQuery('#progress_'+id).progressBar(70);
                                        jQuery('#progress_'+id).progressBar(100);
                                        jQuery('#labelfile').removeClass('no');
                                        jQuery('#labelfile').addClass('yes');
                    uploadStarted = false;
                                    }
                                });

                                jQuery("#apply").validate({
                                    rules: {
                                        apply_name: 'required',
                                        apply_email: {
                                            required: true,
                                            email: true
                                        }<?php if (get_option('9t5_resume_required') == "Yes"): ?>,
                                            file: {
                                                required: function(element) {
                                                    if(filesCounter == 0 || uploadStarted == true) {return true;} else {return false;}
                                                }
                                            }
<?php endif; ?>
                },
                messages: {
                    apply_name: {
                        required: "<?php _e('This field is required!', '9to5') ?>"
                    },
                    apply_email: {
                        required: "<?php _e('This field is required!', '9to5') ?>",
                        email: "<?php _e('Incorrect email format.', '9to5') ?>"
                    }<?php if (get_option('9t5_resume_required') == "Yes"): ?>,
                        file: {
                            required: "<?php _e('Please, upload your CV!', '9to5') ?>"
                        }
<?php endif; ?>
                    },
                    submitHandler: function() {
                        jQuery('#submitLoader').show();
                        var name = jQuery("input[name=apply_name]").val();
                        var email = jQuery("input[name=apply_email]").val();
                        var notes = jQuery("textarea[name=apply_comment]").val();

<?php
                                if (get_post_meta($post->ID, 'email_message_value', true) != "")
                                {
                                    $message = strtr(get_post_meta($post->ID, 'email_message_value', true), array("\n" => '[NEWLINE]', "\r" => ' ', "'" => "\'", '"' => '\"'));
                                    $message = str_replace(" ", "%20", $message);
                                }
                                else
                                {
                                    $message = "none";
                                }
?>
                        var url = '<?php echo get_template_directory_uri() ?>/scripts/';
                        jQuery.post("<?php echo get_template_directory_uri() ?>/scripts/apply.php", {
                            name: name,
                            email: email,
                            subject: '<?php echo get_the_title() ?>',
                            notes: notes,
                            to: '<?php echo get_post_meta($post->ID, 'email_value', true) ?>',
                            message: '<?php echo $message ?>',
                            sitename: '<?php echo get_bloginfo("name") ?>',
                            url: url
                        },
                        function(data){
                            jQuery('#submitLoader').hide();
                            jQuery(".form_apply").slideUp();
                            jQuery(".apply .subtitle h3").fadeOut();
                            jQuery(".apply .subtitle h3").text("<?php _e('Application Sent!', '9to5') ?>");
                            jQuery(".apply .subtitle h3").fadeIn();
                        });
                    },
                    errorPlacement: function(error, element) {
                        var er = element.attr("name");
                        jQuery('#label' + er).removeClass('yes');
                        jQuery('#label' + er).addClass('no');
                    }
                    });
                    jQuery('.validation').blur(function(){
                    if (jQuery(this).valid())
                    {
                        var nm = jQuery(this).attr("name");
                        jQuery('#label' + nm).removeClass('no');
                        jQuery('#label' + nm).addClass('yes');
                    }
                    });
                    });
                                </script>
                                <div class="form_apply">
                                    <p><?php echo get_post_meta($post->ID, 'how_to_apply_value', true) ?></p>
                <?php
                                if (get_post_meta($post->ID, 'exp_value', true) != "noexpire")
                                {
                ?>

                <?php $year = substr(get_post_meta($post->ID, 'exp_value', true), 0, 5); ?>
                <?php
                                    if (get_post_meta($post->ID, 'exp_value', true) != "")
                                    {
                ?>
                                        <div class="clear"></div>
                                        <p><?php _e('Application deadline:', '9to5'); ?> <strong><?php echo substr(get_post_meta($post->ID, 'exp_value', true), 5) . '/' . substr($year, 0, 4); ?></strong></p>
                <?php } ?>

                <?php } ?>
                                <div class="clear"></div>
                                <form id="apply">
                                    <div class="fieldset_set">
                                        <fieldset class="field_name field_inline first">
                                            <label class="label" for="apply_name"><span class="required" id="labelapply_name"><?php _e('Required', '9to5'); ?></span><?php _e('Your Name', '9to5'); ?></label>
                                            <div class="field">
                                                <div class="input_wrap"><input type="text" name="apply_name" class="validation" value="" /></div>
                                            </div>
                                        </fieldset>
                                        <fieldset class="field_email field_inline">
                                            <label class="label" for="apply_email"><span class="required" id="labelapply_email"><?php _e('Required', '9to5'); ?></span><?php _e('Email Address', '9to5'); ?></label>
                                            <div class="field">
                                                <div class="input_wrap"><input type="text" name="apply_email" class="validation" value="" /></div>
                                            </div>
                                        </fieldset>
                                        <fieldset class="field_upload field_inline">
                                            <label class="label" for="apply_resume">
                                <?php if (get_option('9t5_resume_required') == 'Yes'): ?>
                                    <span class="required" id="labelfile"><?php _e('Required', '9to5'); ?></span>
                                <?php endif;
                                    _e('Resume File', '9to5'); ?></label>
                                <div id="uploadField" class="field">
                                    <div id="file-uploader">
                                        <noscript>
                                            <p>Please enable JavaScript to use file uploader.</p>
                                        </noscript>
                                    </div>
                                    <div id="uploadResults">

                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="field_comment field_inline last">
                                <label class="label" for="apply_comment"><?php _e('Additional Notes', '9to5'); ?></label>
                                <div class="field">
                                    <div class="input_wrap"><textarea name="apply_comment" rows="" cols="" ></textarea></div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="button_set">
                            <span id="submitLoader" style="display:none;"><img alt="Loading..." src="<?php echo get_template_directory_uri(); ?>/images/spinner.gif"/></span>
                            <button type="submit" value="Submit" class="button <?php echo get_option('9t5_color_button'); ?> large"><?php _e('Send Application', '9to5'); ?></button>
                        </div>

                    </form>
                </div>
                <!-- NEW FORM END -->

            <?php else : ?>

                                        <p><?php echo get_post_meta($post->ID, 'how_to_apply_value', true) ?></p>

            <?php endif; ?>

                                    </div>

        <?php endwhile;
                                    endif; ?>

                                </div>

                            </div>

<?php get_sidebar(); get_footer(); ?>