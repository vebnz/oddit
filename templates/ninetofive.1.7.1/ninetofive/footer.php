</div>
</div>
<div class="footer_push"></div>
</div>
<div class="footer">
 <div class="inner">
  <?php wp_nav_menu(array('container_class' => 'links', 'theme_location' => 'footer')); ?>
  <div class="copyright">
   <p><?php echo stripslashes(get_option("9t5_copy_notice")) ?></p>
  </div>
  <div class="seo">
   <p><?php echo get_option("9t5_seo"); ?></p>
  </div>
 </div>
</div>
<?php echo stripslashes(get_option("9t5_analytics")) ?>
<?php wp_footer(); ?>
<!-- Nine to Five v1.6.8 -->
</body>
</html>