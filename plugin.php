<?php
add_plugin_hook('install', 'DocsViewerPlugin::install');
add_plugin_hook('uninstall', 'DocsViewerPlugin::uninstall');
add_plugin_hook('config_form', 'DocsViewerPlugin::configForm');
add_plugin_hook('config', 'DocsViewerPlugin::config');
add_plugin_hook('admin_append_to_items_show_primary', 'DocsViewerPlugin::append');
add_plugin_hook('public_append_to_items_show', 'DocsViewerPlugin::append');

class DocsViewerPlugin
{
    const API_URL = 'http://docs.google.com/viewer';
    const DEFAULT_VIEWER_EMBED = 1;
    const DEFAULT_VIEWER_WIDTH = 500;
    const DEFAULT_VIEWER_HEIGHT = 600;

    // http://docs.google.com/support/bin/answer.py?hl=en&answer=1189935
    public static $supportedFileTypes = array(
        'ai' => 'Adobe Illustrator',
        'psd' => 'Adobe Photoshop',
        'dxf' => 'Autodesk AutoCad',
        'doc|docx' => 'Microsoft Word',
        'xls|xlsx' => 'Microsoft Excel',
        'ppt|pptx' => 'Microsoft PowerPoint',
        'pdf' => 'Portable Document Format File',
        'eps|ps' => 'PostScript',
        'pps' => 'PowerPoint Slide Show',
        'svg' => 'Scalable Vector Graphics',
        'tif|tiff' => 'Tagged Image File Format',
        'ttf' => 'TrueType',
        'xps' => 'XML Paper Specification'
    );

    public static function install()
    {
        set_option('docsviewer_embed_admin', DocsViewerPlugin::DEFAULT_VIEWER_EMBED);
        set_option('docsviewer_width_admin', DocsViewerPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('docsviewer_height_admin', DocsViewerPlugin::DEFAULT_VIEWER_HEIGHT);
        set_option('docsviewer_embed_public', DocsViewerPlugin::DEFAULT_VIEWER_EMBED);
        set_option('docsviewer_width_public', DocsViewerPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('docsviewer_height_public', DocsViewerPlugin::DEFAULT_VIEWER_HEIGHT);
    }

    public static function uninstall()
    {
        delete_option('docsviewer_width');
        delete_option('docsviewer_height');
    }

    public static function configForm()
    {
        include 'config_form.php';
    }

    public static function config($post)
    {
        if (!is_numeric($post['docsviewer_width_admin']) ||
            !is_numeric($post['docsviewer_height_admin']) ||
            !is_numeric($post['docsviewer_width_public']) ||
            !is_numeric($post['docsviewer_height_public'])) {
            throw new Exception('The width and height must be numeric.');
        }

        // Only accept whitelisted file types
        foreach (array_keys($post['docsviewer_supported_files']) as $type) {
            if (!in_array($type, array_keys(self::$supportedFileTypes))) {
                throw new Exception('Invalid File Format Submitted.');
            }
            else {
                // Prevent form from injecting arbitrary values
                $post['docsviewer_supported_files'] = array_fill_keys(array_keys($post['docsviewer_supported_files']), 'on');
            }
        }

        set_option('docsviewer_embed_admin', (int) (boolean) $post['docsviewer_embed_admin']);
        set_option('docsviewer_width_admin', $post['docsviewer_width_admin']);
        set_option('docsviewer_height_admin', $post['docsviewer_height_admin']);
        set_option('docsviewer_embed_public', (int) (boolean) $post['docsviewer_embed_public']);
        set_option('docsviewer_width_public', $post['docsviewer_width_public']);
        set_option('docsviewer_height_public', $post['docsviewer_height_public']);
        set_option('docsviewer_supported_files', serialize($post['docsviewer_supported_files']));
    }


    public static function append()
    {
        // Embed viewer only if configured to do so.
        if ((is_admin_theme() && !get_option('docsviewer_embed_admin')) ||
            (!is_admin_theme() && !get_option('docsviewer_embed_public'))) {
            return;
        }
        $docsViewer = new DocsViewerPlugin;
        $docsViewer->embed();
    }

    public function embed()
    {
        foreach (__v()->item->Files as $file) {
            $extension = pathinfo($file->archive_filename, PATHINFO_EXTENSION);
            if (!in_array($extension, self::getSupportedExtensions())) {
                continue;
            }
?>
<div>
    <h2>File: <?php echo $file->original_filename; ?></h2>
    <iframe src="<?php echo $this->_getUrl($file); ?>"
            width="<?php echo is_admin_theme() ? get_option('docsviewer_width_admin') : get_option('docsviewer_width_public'); ?>"
            height="<?php echo is_admin_theme() ? get_option('docsviewer_height_admin') : get_option('docsviewer_height_public'); ?>"
            style="border: none;"></iframe>
</div>
<?php
        }
    }

    private function getSupportedExtensions() {
        $docsviewer_supported_files = array_keys(unserialize(get_option('docsviewer_supported_files')));
        foreach ($docsviewer_supported_files as $supported) {
            $exts = (implode('|', $all_supported)) ? implode('|', $all_supported) : array($supported);
            foreach ($exts as $ext) {
                $valid_extensions[] = $ext;
            }
        }

        return $valid_extensions;
    }

    private function _getUrl(File $file)
    {
        require_once 'Zend/Uri.php';
        $uri = Zend_Uri::factory(self::API_URL);
        $uri->setQuery(array('url'      => WEB_FILES . '/' . $file->archive_filename,
                             'embedded' => 'true'));
        return $uri->getUri();
    }
}