<form action="<?php echo home_url() ?>" method="get" role="search">
    <span>
        <input type="text" name="s" id="search_box" value="<?php _e('Search by keyword', '9to5') ?>" onfocus="if (this.value == '<?php _e('Search by keyword', '9to5') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search by keyword', '9to5') ?>';}"/>
        <button name="submit" type="submit" class="icon icon_search"></button>
    </span>
</form>