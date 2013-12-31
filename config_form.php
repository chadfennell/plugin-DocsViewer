<h3>Admin Interface</h3>
<label for="docsviewer_embed_admin">Embed viewer in admin item show pages?</label>
<p><?php echo __v()->formCheckbox('docsviewer_embed_admin',
                                  true,
                                  array('checked' => (boolean) get_option('docsviewer_embed_admin'))); ?></p>
<label for="docsviewer_width_admin">Viewer width, in pixels:</label>
<p><?php echo __v()->formText('docsviewer_width_admin',
                              get_option('docsviewer_width_admin'),
                              array('size' => 5));?></p>
<label for="docsviewer_height_admin">Viewer height, in pixels:</label>
<p><?php echo __v()->formText('docsviewer_height_admin',
                              get_option('docsviewer_height_admin'),
                              array('size' => 5));?></p>
<h3>Public Theme</h3>
<label for="docsviewer_embed_public">Embed viewer in public item show pages?</label>
<p><?php echo __v()->formCheckbox('docsviewer_embed_public',
                                  true,
                                  array('checked' => (boolean) get_option('docsviewer_embed_public'))); ?></p>
<label for="docsviewer_width_public">Viewer width, in pixels:</label>
<p><?php echo __v()->formText('docsviewer_width_public',
                              get_option('docsviewer_width_public'),
                              array('size' => 5));?></p>
<label for="docsviewer_height_public">Viewer height, in pixels:</label>
<p><?php echo __v()->formText('docsviewer_height_public',
                              get_option('docsviewer_height_public'),
                              array('size' => 5));?></p>

<h3>File Formats to Display via the Docs Viewer</h3>
<?php  $savedTypes = array_keys(unserialize(get_option('docsviewer_supported_files')));?>
  <?php foreach(DocsViewerPlugin::$supportedFileTypes as $fileTypes => $fileTypeName): ?>
  <div title="<?php print $fileTypeName; ?>">
      <label style="width: 220px;" for="docsviewer_supported_files[<?php print $FileTypes; ?>]"><?php printf('%s (%s)', $fileTypeName, str_replace('|', ', ', $fileTypes)); ?></label>
      <input type="checkbox" name="docsviewer_supported_files[<?php print $fileTypes; ?>]" <?php print (in_array($fileTypes, $savedTypes)) ? 'checked="true"' : null ?> />
  </div>
<?php endforeach; ?>

<p>By using this service you acknowledge that you have read and agreed to the <a href="http://docs.google.com/viewer/TOS?hl=en">Google Docs Viewer Terms of Service</a>.</p>

