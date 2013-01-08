<script type="text/javascript">
jQuery(document).ready(function () {
    var docviewer = jQuery('#docsviewer');
    
    // Set the default docviewer.
    docviewer.append(
    '<h2>Viewing: ' + <?php echo js_escape($docs[0]->original_filename); ?> + '</h2>' 
  + '<iframe src="' + <?php echo js_escape(DocsViewerPlugin::API_URL . '?' . http_build_query(array('url' => $docs[0]->getWebPath('original'), 'embedded' => 'true'))); ?> 
  + '" width="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_width_admin')) : js_escape(get_option('docsviewer_width_public')); ?> 
  + '" height="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_height_admin')) : js_escape(get_option('docsviewer_height_public')); ?> 
  + '" style="border: none;"></iframe>');
    
    // Handle the document click event.
    jQuery('.docsviewer_docs').click(function(event) {
        event.preventDefault();
        
        // Reset the docviewer.
        docviewer.empty();
        docviewer.append(
        '<h2>Viewing: ' + jQuery(this).text() + '</h2>' 
      + '<iframe src="' + this.href 
      + '" width="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_width_admin')) : js_escape(get_option('docsviewer_width_public')); ?> 
      + '" height="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_height_admin')) : js_escape(get_option('docsviewer_height_public')); ?> 
      + '" style="border: none;"></iframe>');
    });
});
</script>
<div>
    <h2>Document Viewer</h2>
    <?php if (1 < count($docs)): ?>
    <p>Click below to view a document.</p>
    <ul>
        <?php foreach($docs as $doc): ?>
        <li><a href="<?php echo html_escape(DocsViewerPlugin::API_URL . '?' . http_build_query(array('url' => $docs[0]->getWebPath('original'), 'embedded' => 'true'))); ?>" class="docsviewer_docs"><?php echo html_escape($doc->original_filename); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div id="docsviewer"></div>
</div>
