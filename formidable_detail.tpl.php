<div class="wrap">
  
<div class="frmicon icon32"><br></div>
<h2><?php echo $form_name; ?></h2>

<table class="widefat fixed" cellspacing="0">
  <thead>
    <tr>
      <th class="manage-column"><?php _e('ID'); ?></th>
      <th class="manage-column"><?php _e('Name'); ?></th>
      <th class="manage-column"><?php _e('Submitted'); ?></th>
    </tr>
  </thead>
  <tbody>
    <td><?php echo $entry_id; ?></td>
    <td><?php echo $name; ?></td>
    <td><?php echo $created_at; ?></td>
  </tbody>
</table>

<h3><?php _e('Form Values'); ?></h3>

<?php foreach($metas as $key=>$value) : ?>
  <?php if(is_numeric($key)) { continue; } ?>
  <strong><?php _e($key); ?></strong>
  <div><?php echo $value; ?></div><br />
<?php endforeach; ?>
  
<?php do_action('formidable_results_render_entry', $entry_id); ?>

<?php
  $url = "?page=formidable-results";
  if(isset($_REQUEST['orderby'])) {
    $url .= "&orderby={$_REQUEST['orderby']}";
  }
  if(isset($_REQUEST['order'])) {
    $url .= "&order={$_REQUEST['order']}";
  }
?>
<p><a class="button-secondary cancel" href="<?php echo $url; ?>">Done</a></p>

</div>