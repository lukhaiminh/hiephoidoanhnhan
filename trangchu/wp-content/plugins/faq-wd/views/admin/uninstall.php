<h2>Uninstall FAQ WD</h2>
<p>Deactivating FAQ WD plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.</p>
<div class="uninstall_faq_wd">
    <p><strong>WARNING:</strong></p>
    <p>Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.</p>
    <p><strong>The following WordPress Options/Tables will be DELETED:</strong></p>
</div>
<div class="uninstall_faq">
    <form id="uninstall_form" method="post" action="<?php echo admin_url('admin.php?page=uninstall_faq_wd'); ?>">
        <input type="hidden" name="uninstall_faq_wd" value="yes" />
        <input type="submit" class="uninstall_button" value="UNINSTALL FAQ WD">
        <?php wp_nonce_field('delete_faq_wd', 'delete_faq_wd_fild'); ?>
    </form>    
</div><br/>