<?php 
/* Template Name: Post a Job */
get_header(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.validate.min.js"></script>
<script type="text/javascript">
	var skillsCounter = 0;
	jQuery(document).ready(function () {
		jQuery("#companyName").css('color', '#CCCCCC');
		jQuery("#companyLocation").css('color', '#CCCCCC');
		jQuery("#salary").css('color', '#CCCCCC');
		jQuery("#tags").css('color', '#CCCCCC');
		jQuery.validator.addMethod("compname", function (value, element) {
			return this.optional(element) || value != "<?php _e('e.g. Acme Inc.', '9to5') ?>" || value == "<?php _e('Anonymous', '9to5') ?>";
		}, "Please specify company name");
		jQuery.validator.addMethod("comploc", function (value, element) {
			return this.optional(element) || value != "<?php _e('e.g. San Francisco, USA', '9to5') ?>" || value == "<?php _e('Anywhere', '9to5') ?>";
		}, "Please specify company location");
		jQuery("#post").validate({
			rules:{
				post_title:'required',
				content:'required',
				company_name_value:{
					required:function (element) {
						if (jQuery('input[name=anon]').is(':checked')) {
							return false;
						} else {
							return true;
						}
					},
					compname:true
				},
				location_value:{
					required:function (element) {
						if (jQuery('input[name=anywere]').is(':checked')) {
							return false;
						} else {
							return true;
						}
					},
					comploc:true
				},
				how_to_apply_value:'required',
				application_email:{
					required:function (element) {
						return jQuery('#enableApply').is(':checked');
					},
					email:true
				},
				application_email_message:{
					required:function (element) {
						return jQuery('#enableApply').is(':checked');
					}
				},
				expdate:{
					required:function (element) {
						if (jQuery('input[name=noexpire]').is(':checked')) {
							return false;
						} else {
							return true;
						}
					},
					date:true
				},
			<?php
			$maxskills = get_option("9t5_max_skills") + 1;
			$counter = 1;

			while ($maxskills > $counter)
			{
				echo 'skills_value_' . $counter . ': {required: function(element) {
				var tested = 0;
				jQuery(".skills").each(function(){
				if(jQuery(this).val() != "") tested++;
				});
				if(tested == 0)
				{
					jQuery("#labelskills_value").removeClass("yes").removeClass("no").addClass("no");
					return true;
				}
				else
				{
					jQuery("#labelskills_value").removeClass("yes").removeClass("no").addClass("yes");
					return false;
				}
				}},
				';
				$counter++;
			}
			?>
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
		var draftMSG = jQuery.cookie("draftMSG");
		if (draftMSG == "yes") {
			$.cookie("draftMSG", "no", { expires:-10 });
			alert("<?php echo  __('Thanks for submitting your job. It will be reviewed shortly.', '9to5'); ?>");
		}


	});
</script>
<div class="focus">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php if (is_user_logged_in()) { ?>

	<div id="success" class="message green" style="display: none">
		<div class="push">
			<h3><?php _e('Job listing successful', '9to5') ?></h3>
			<p>
				<a href="<?php echo site_url() ?>/wp-admin/edit.php"><strong><?php _e('Manage your Listings', '9to5') ?></strong></a> <?php _e('or', '9to5') ?>
				<a href="<?php echo site_url() ?>/wp-login.php?action=logout&redirect_to=<?php echo site_url() ?>"><?php _e('Logout', '9to5') ?></a>
			</p>
		</div>
	</div>

	<div class="post whiteboard">

		<div class="title <?php echo get_option('9t5_color_tertiary'); ?>">
			<h1><?php the_title() ?></h1>
			<?php the_content(); ?>
		</div>

		<div class="description">
		<?php
		/******************************************************************************/
		/**************************** Only check if have credits **********************/
		/******************************************************************************/
		global $wpdb, $current_user;
		$table_name = $wpdb->prefix . 'transactions';
		ini_set('log_errors', true);
		ini_set('error_log', dirname(__FILE__) . '/ipn_errors.log');
		$have_credits = false;
		$transaction_id = 0;
		$user_id = 0;
		$txn_id = "";

		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if (mysqli_connect_errno()) {
			error_log("Connection error");
		} else {
			if ($stmt = $mysqli->prepare("SELECT transaction_id,txn_id,user_id FROM $table_name WHERE credit_used=0 AND user_id=$current_user->ID LIMIT 1")) {
				//if true is for found credits_used ==0 , es decir, not used
				$stmt->execute();
				$stmt->bind_result($transaction_id, $txn_id, $user_id);
				$stmt->fetch();
				$have_credits = $txn_id;
			}
		}
		$mysqli->close();

		if (!$have_credits && get_option("9t5_pp_enabled") == "true") {
			$paypal_suffix = (get_option("9t5_pp_sandbox") == "true") ? "sandbox." : "";
			?>
			<form target="_parent" action="https://www.<?php echo $paypal_suffix; ?>paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cpp_header_image" value="<?php echo get_option("9t5_pp_image") ?>" />
				<input type="hidden" name="return" value="<?php the_permalink(); ?>" />
				<input type="hidden" name="cbt" value="<?php echo get_option("9t5_pp_text") ?>" />
				<!-- <input type="hidden" name="rm" value="<?php echo get_option("9t5_pp_text") ?>"/>-->
				<input type="hidden" name="notify_url" value="<?php echo get_template_directory_uri(); ?>/ipn.php"/>
				<!-- Identify your business so that you can collect the payments. -->
				<input type="hidden" name="business" value="<?php echo get_option("9t5_pp_email") ?>"/>
				<input type="hidden" name="custom" value="<?php echo $current_user->ID; ?>"/>
				<!-- Specify a Buy Now button. -->
				<input type="hidden" name="cmd" value="_xclick"/>
				<!-- Specify details about the item that buyers will purchase. -->
				<input type="hidden" name="item_name" value="<?php _e('Job Post Token', '9to5') ?>"/>
				<input type="hidden" name="amount" value="<?php echo get_option("9t5_pp_cost") ?>"/>
				<input type="hidden" name="currency_code" value="<?php echo get_option("9t5_currency") ?>"/>
				<!-- Display the payment button. -->
				<input type="submit" value="<?php _e('Purchase token for', '9to5') ?> <?php echo get_option("9t5_currency") ?> <?php echo get_option("9t5_pp_cost") ?>"/>
				<img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif"/>
			</form>
		</div>
	</div>
<?php
} else {
?>
<form id="post" name="post" action="#" method="post">
<input type="hidden" name="action" value="postajob"/>
<input type="hidden" name="user_id" value="<?php echo $current_user->ID; ?>"/>
<input type="hidden" name="txn_id" value="<?php echo $txn_id; ?>" />

	<div class="form_post">
		<div class="fieldset_set">
			<fieldset class="field_position first">
				<span class="required yes"><?php _e('Required', '9to5') ?></span>
				<label class="label"><?php _e('Position', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap">
						<select name="position_type_value" class="styled">
							<option value="filter_1" selected="selected"><?php echo get_option('filter_1') ?></option>
							<option value="filter_2"><?php echo get_option('filter_2') ?></option>
							<?php if (get_option('filter_3') != '') {
								echo '<option value="filter_3">' . get_option('filter_3') . '</option>'; 
							} ?>
						</select>
					</div>
				</div>
			</fieldset>
			<fieldset class="field_category last">
				<span class="required yes"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="category"><?php _e('Category', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap">
						<select name="category" id="category" class="styled">
						<?php $cats = get_categories("hide_empty=0");
						foreach ($cats as $cat){
							echo '<option nam="post_category[]" value="' . $cat->cat_ID . '">' . htmlspecialchars_decode($cat->cat_name) . '</option>';
						} ?>
						</select>
					</div>
				</div>
			</fieldset>
		</div>

		<div class="fieldset_set">
			<fieldset class="field_title first">
				<span id="labelpost_title" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="title"><?php _e('Job Title', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap"><input type="text" name="post_title" id="title" class="input_full validation"/></div>
				</div>
			</fieldset>
			<fieldset class="field_description last">
				<span id="labelcontent" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="desc">
					<?php _e('Description', '9to5') ?>
					<span class="info"><?php _e('Fill in as much detail as you can about the position, outlining responsibilities, perks and information about your company.', '9to5') ?></span>
				</label>
				<div class="field mod">
					<div class="input_wrap"><textarea class="validation" name="content" rows="" cols=""></textarea></div>
				</div>
			</fieldset>
		</div>

		<div class="fieldset_set">
			<fieldset class="field_company first">
				<span id="labelcompany_name_value" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="company_name_value"><?php _e('Company Name', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap"><input class="validation" id="companyName" type="text" name="company_name_value" value="<?php _e('e.g. Acme Inc.', '9to5') ?>" onfocus="if (this.value == '<?php _e('e.g. Acme Inc.', '9to5') ?>') {this.value = ''; jQuery(this).css('color','#000');}" onblur="if (this.value == '') {this.value = '<?php _e('e.g. Acme Inc.', '9to5') ?>'; jQuery(this).css('color','#CCCCCC');}"/></div>
					<?php if (get_option("9t5_anon_company") == "Yes") { ?>
						<label class="check">
						<span class="input_wrap"><input id="anon" type="checkbox" name="anon" value="anon"/></span>
						<?php _e('Post Anonymously', '9to5') ?>
						</label>
					<?php } ?>
				</div>
			</fieldset>
			<fieldset class="field_location">
				<span id="labellocation_value" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="location_value"><?php _e('Job Location', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap"><input class="validation" type="text" id="companyLocation" name="location_value" value="<?php _e('e.g. San Francisco, USA', '9to5') ?>" onfocus="if (this.value == '<?php _e('e.g. San Francisco, USA', '9to5') ?>') {this.value = ''; jQuery(this).css('color','#000');}" onblur="if (this.value == '') {this.value = '<?php _e('e.g. San Francisco, USA', '9to5') ?>'; jQuery(this).css('color','#CCCCCC');}"/></div>
					<?php if (get_option("9t5_location_anywhere") == "Yes") { ?><label class="check"><span class="input_wrap"><input type="checkbox" name="anywhere" value="anywhere"/></span><?php _e('Anywhere', '9to5') ?> </label><?php } ?>
				</div>
			</fieldset>
			<fieldset class="field_date last">
				<label class="label" for="map">
					<?php _e('Google Map', '9to5') ?>
					<span class="info"><?php _e('Optionally embed a fully functional map. In Google Maps, select Link then Paste HTML to embed in website. Copy and paste the <code>iframe</code> code here.', '9to5') ?></span>
				</label>
				<div class="field mod">
					<div class="input_wrap"><textarea name="map" id="map" rows="" cols=""></textarea></div>
				</div>
			</fieldset>
		</div>

		<div class="fieldset_set">
			<fieldset class="field_skills first">
				<span id="labelskills_value" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="skills">
					<?php _e('Skills', '9to5') ?>
					<span class="info"><?php _e('These core competencies (eg. HTML) or more abstract abilities (eg. Teamwork) will assist applicants in finding your listing.', '9to5') ?> </span>
				</label>

				<div class="field mod">
					<ul class="skills">
						<?php
						$maxskills = get_option("9t5_max_skills") + 1;
						$counter = 1;
						$class = ' class="hidden skills"';

						while ($maxskills > $counter)
						{

							echo '<li><div class="input_wrap"><input id="skill' . $counter . '" type="text" name="skills_value_' . $counter . '" ';
							if ($counter >= 4) {
								echo $class;
							}
							else
							{
								echo ' class="validation skills"';
							}
							echo '/></div></li>';
							$counter++;
						}
						?>
					</ul>
					<?php
					if ($maxskills >= 5) {
						echo '<p><a href="#" id="addskill" name="addskill">' . __('Add another', '9to5') . '</a></p>';
					}
					?>
				</div>
			</fieldset>
			<fieldset class="field_tags last">
				<label class="label" for="title"><?php _e('Tags', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap"><input type="text" name="post_tags" id="tags" class="input_full validation" value="<?php _e('e.g. Graphic, Designer, HTML', '9to5') ?>" onfocus="if (this.value == '<?php _e('e.g. Graphic, Designer, HTML', '9to5') ?>') {this.value = ''; jQuery(this).css('color','#000');}" onblur="if (this.value == '') {this.value = '<?php _e('e.g. Graphic, Designer, HTML', '9to5') ?>'; jQuery(this).css('color','#CCCCCC');}"/></div>
				</div>
			</fieldset>
		</div>

		<?php if (get_option('9t5_job_salary') == 'Yes'): ?>
		<div class="fieldset_set">
			<fieldset class="field_title first">
				<label class="label" for="salary"><?php _e('Salary', '9to5') ?>
					<span class="info"><?php _e('Please, indicate the proposed salary including currency symbol or letters.', '9to5'); ?></span>
				</label>
				<div class="field mod">
					<div class="input_wrap"><input type="text" id="salary" name="salary" class="input_full" value="<?php _e('e.g. $1500 a month or 25 USD per hour', '9to5') ?>" onfocus="if (this.value == '<?php _e('e.g. $1500 a month or 25 USD per hour', '9to5') ?>') {this.value = ''; jQuery(this).css('color','#000');}" onblur="if (this.value == '') {this.value = '<?php _e('e.g. $1500 a month or 25 USD per hour', '9to5') ?>'; jQuery(this).css('color','#CCCCCC');}"/></div>
				</div>
			</fieldset>
			<fieldset class="field_description last">
				<label class="label" for="salary_description">
					<?php _e('Benefits', '9to5') ?>
					<span class="info"><?php _e('Fill in as much detail as you can what can you offer the applicant.', '9to5') ?></span>
				</label>
				<div class="field mod">
					<div class="input_wrap"><textarea class="validation" name="salary_description" rows="" cols=""></textarea></div>
				</div>
			</fieldset>
		</div>
		<?php endif; ?>

		<div class="fieldset_set">
			<fieldset class="field_date first last">
				<span id="labelexpdate" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="expdate"><?php _e('Expiry Date', '9to5') ?></label>
				<div class="field mod">
					<div class="input_wrap"><input class="validation" type="text" name="expdate" id="date" value="<?php echo date('Y/m/d', strtotime("+30 days"));?>" onfocus="if (this.value == 'yyyy/mm/dd') {this.value = '';}" onblur="if (this.value == '') {this.value = 'yyyy/mm/dd';}"/></div>
					<?php if (get_option("9t5_noexpire") == "Yes") { ?>
					<label class="check"><span class="input_wrap"><input id="noexpire" type="checkbox" name="noexpire" value="noexpire"/></span><?php _e('No Expiration Date', '9to5') ?></label>
					<?php } ?>
				</div>
			</fieldset>
		</div>

		<div class="fieldset_set">
			<fieldset class="field_apply first">
				<span id="labelhow_to_apply_value" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="apply">
					<?php _e('How to Apply', '9to5') ?>
					<span class="info"><?php _e('Tell applicants how to apply for the listed position.<br /><br /><strong>Optionally:</strong> Allow applications directly from your job listing. </span>', '9to5') ?>
				</label>
				<div class="field mod">
					<div class="input_wrap"><textarea class="validation" name="how_to_apply_value" cols="" rows=""></textarea></div>
					<div class="clear"></div>
					<label class="check"><span class="input_wrap"><input id="enableApply" type="checkbox" name="enableApply" value="enableApply"/></span><?php _e('Enable on-page application', '9to5') ?></label>
				</div>
			</fieldset>
			<fieldset class="onPageApplication">
				<span id="labelapplication_email" class="required"><?php _e('Required', '9to5') ?></span>
				<label class="label" for="application_email"><?php _e('Email Address', '9to5') ?><span class="info"><?php _e('The address all job applications will be sent to.', '9to5') ?></span></label>
				<div class="field mod" id="email">
					<div class="input_wrap"><input class="validation" type="text" name="application_email" id="application_email" class="" value="<?php  echo  $current_user->user_email;?>"/></div>
				</div>
			</fieldset>
			<fieldset class="last onPageApplication">
				<div id="email_message">
					<span id="labelapplication_email_message" class="required"><?php _e('Required', '9to5') ?></span>
					<label class="label" for="application_email_message"><?php _e('Email Message', '9to5') ?><span class="info"><?php _e('This is the response sent to applicants upon succesful delivery of their application.<br /><br />The email starts with:<br /><strong>Hi [Name],</strong></span>', '9to5') ?></label>
					<div class="field mod">
						<div class="input_wrap"><textarea name="application_email_message" id="application_email_message" class="input_full validation" cols="" rows=""></textarea></div>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<!--/div-->

	<div class="cap">
		<div class="button_set">
			<input id="jobsubmit" name="jobsubmit" type="submit" value="<?php _e('Submit Listing', '9to5') ?>" class="button <?php echo get_option('9t5_color_button'); ?> large"/>
		</div>
	</div>
</form>
</div>

<?php } ?>

<?php 
} else {
?>
<?php
	if (!is_user_logged_in()) {
		global $auth_errors;
?>
<div class="post whiteboard">

	<div class="title <?php echo get_option('9t5_color_tertiary'); ?>">
		<h1><?php the_title() ?></h1>
		<?php the_content(); ?>
	</div>
	<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/scripts/jquery.validate.min.js"></script>
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
	<div class="form_post">
		<div class="message red">
			<div class="push">
				<h3><?php _e('Please login or register first', '9to5') ?></h3>
				<p><?php _e('To post a job you must be logged in or register', '9to5') ?>.</p>
			</div>
		</div>

<?php
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
		<div class="description">
			<form method="post" id="loginForm" class="loginform">
				<div class="form_post">
					<h3><?php _e('Log In', '9to5') ?></h3>
					<div class="fieldset_set">
						<fieldset class="first">
							<span class="required" id="labelusername"><?php _e('Required', '9to5') ?></span>
							<label class="label" for="username"><?php _e('Username', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="username" class="validation" name="username" type="text"/></div>
							</div>
						</fieldset>
						<fieldset class="last">
							<span class="required" id="labelpassword"><?php _e('Required', '9to5') ?></span>
							<label class="label" for="password"><?php _e('Password', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="password" class="validation" name="password" type="password"/></div>
							</div>
						</fieldset>
					</div>
					<div class="button_set mod">
						<input name="action" type="hidden" value="login"/>
						<input id="loginsubmit" name="loginsubmit" type="submit" value="<?php _e('Login', '9to5') ?>"/>
						<p>
							<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e('Lost Password', '9to5') ?>"><?php _e('Lost Password', '9to5') ?></a>
						</p>
					</div>
				</div>
			</form>

			<form method="post" id="registerForm" class="loginform">
				<div class="form_post">
					<h3><?php _e('Register', '9to5') ?></h3>
					<div class="fieldset_set">
						<fieldset class="first">
							<span class="required" id="labelregusername"><?php _e('Required', '9to5') ?></span>
							<label class="label" for="regusername"><?php _e('Username', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="regusername" class="validation" name="regusername" type="text"/></div>
							</div>
						</fieldset>
						<fieldset>
							<span class="required" id="labelregpassword"><?php _e('Required', '9to5') ?></span>
							<label class="label" for="regpassword"><?php _e('Password', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="regpassword" class="validation" name="regpassword" type="password"/></div>
							</div>
						</fieldset>
						<fieldset>
							<label class="label" for="FirstName"><?php _e('First Name', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="FirstName" class="" name="FirstName" type="text"/></div>
							</div>
						</fieldset>
						<fieldset>
							<label class="label" for="LastName"><?php _e('Last Name', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="LastName" class="" name="LastName" type="text"/></div>
							</div>
						</fieldset>
						<fieldset class="last">
							<span class="required" id="labelregemail"><?php _e('Required', '9to5') ?></span>
							<label class="label" for="regemail"><?php _e('Email', '9to5') ?></label>
							<div class="field mod">
								<div class="input_wrap"><input id="regemail" class="validation" name="regemail" type="text"/></div>
							</div>
						</fieldset>
					</div>
					<div class="button_set mod">
						<input name="action" type="hidden" value="registration"/>
						<input id="regsubmit" name="regsubmit" type="submit" value="<?php _e('Register', '9to5') ?>"/>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
	}
} ?>

<?php endwhile; endif; ?>

</div>
</div>

<?php get_sidebar();
get_footer(); ?>