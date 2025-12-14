<?php

class EdelMuseumGeneratorAdminPro {

    public function init() {
        add_action('init', array($this, 'register_cpt'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_fields'));

        add_action('wp_ajax_edel_museum_pro_save_layout', array($this, 'ajax_save_layout'));
        add_action('wp_ajax_edel_museum_pro_clear_layout', array($this, 'ajax_clear_layout'));

        add_filter('upload_mimes', array($this, 'allow_glb_uploads'));
        add_filter('wp_check_filetype_and_ext', array($this, 'fix_glb_mime_type_check'), 10, 4);

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // ヘルプメニュー
        add_action('admin_menu', array($this, 'add_help_menu'));

        // ショートコード表示機能
        add_filter('manage_edel_exhibition_posts_columns', array($this, 'add_shortcode_column_head'));
        add_action('manage_edel_exhibition_posts_custom_column', array($this, 'add_shortcode_column_content'), 10, 2);
        add_action('edit_form_after_title', array($this, 'render_shortcode_after_title'));
        add_action('admin_footer', array($this, 'print_admin_scripts'));
    }

    public function add_help_menu() {
        add_submenu_page(
            'edit.php?post_type=edel_exhibition',
            __('Usage Guide', 'edel-museum-generator'),
            __('Usage Guide', 'edel-museum-generator'),
            'edit_posts',
            'edel-museum-help',
            array($this, 'render_help_page')
        );
    }

    public function render_help_page() {
?>
        <div class="wrap">
            <h1><?php _e('Edel Museum Generator Pro - Usage Guide', 'edel-museum-generator'); ?></h1>

            <div style="max-width: 1000px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 20px;">

                <div style="margin-bottom: 30px; padding: 15px; background: #e5f5fa; border-left: 4px solid #2271b1;">
                    <strong><?php _e('For more detailed instructions and tutorials, please visit:', 'edel-museum-generator'); ?></strong><br>
                    <a href="https://edel-hearts.com/edel-museum-generator-pro-usage" target="_blank" style="font-size: 16px; font-weight: bold; text-decoration: none; display: inline-block; margin-top: 5px;">
                        https://edel-hearts.com/edel-museum-generator-pro-usage <span class="dashicons dashicons-external" style="font-size:18px; vertical-align: bottom;"></span>
                    </a>
                </div>

                <h2 style="border-bottom: 2px solid #2271b1; padding-bottom: 10px; margin-bottom: 20px;">
                    <span class="dashicons dashicons-art" style="font-size:24px;width:24px;height:24px;margin-right:5px;"></span>
                    <?php _e('Step 1: Add Artworks', 'edel-museum-generator'); ?>
                </h2>
                <p><?php _e('First, register the artworks (images or 3D models) you want to display in the museum.', 'edel-museum-generator'); ?></p>
                <ol style="margin-left: 20px; line-height: 1.8;">
                    <li><?php _e('Go to <strong>Museum Artworks > Add New Artwork</strong>.', 'edel-museum-generator'); ?></li>
                    <li><?php _e('Enter the <strong>Title</strong> and <strong>Description</strong> (displayed in the popup).', 'edel-museum-generator'); ?></li>
                    <li>
                        <strong><?php _e('For 2D Images (Paintings):', 'edel-museum-generator'); ?></strong><br>
                        <?php _e('Set the <strong>Featured Image</strong> (right sidebar). This will be framed on the wall.', 'edel-museum-generator'); ?>
                    </li>
                    <li>
                        <strong><?php _e('For 3D Models (Sculptures/Objects):', 'edel-museum-generator'); ?></strong><br>
                        <?php _e('Scroll down to "Artwork Options" and upload a <strong>.glb</strong> file.', 'edel-museum-generator'); ?><br>
                        <em><?php _e('*Note: If a GLB file is set, it will be used instead of the Featured Image.', 'edel-museum-generator'); ?></em>
                    </li>
                    <li><strong><?php _e('Link URL (Optional):', 'edel-museum-generator'); ?></strong> <?php _e('Enter a URL if you want to link to an external page.', 'edel-museum-generator'); ?></li>
                </ol>

                <h2 style="border-bottom: 2px solid #2271b1; padding-bottom: 10px; margin-bottom: 20px; margin-top: 40px;">
                    <span class="dashicons dashicons-building" style="font-size:24px;width:24px;height:24px;margin-right:5px;"></span>
                    <?php _e('Step 2: Create Exhibition Room', 'edel-museum-generator'); ?>
                </h2>
                <p><?php _e('Create a room and decide which artworks to place on which walls.', 'edel-museum-generator'); ?></p>
                <ol style="margin-left: 20px; line-height: 1.8;">
                    <li><?php _e('Go to <strong>Exhibition Settings > Add New Exhibition</strong>.', 'edel-museum-generator'); ?></li>
                    <li><strong><?php _e('Textures:', 'edel-museum-generator'); ?></strong> <?php _e('Select images for Floor, Wall, Pillar, and Ceiling.', 'edel-museum-generator'); ?></li>
                    <li><strong><?php _e('Structure:', 'edel-museum-generator'); ?></strong> <?php _e('Choose the number of pillars (0, 1, or 2).', 'edel-museum-generator'); ?></li>
                    <li><strong><?php _e('Placement:', 'edel-museum-generator'); ?></strong>
                        <ul style="list-style:disc; margin-left:20px; color:#555;">
                            <li><strong><?php _e('Walls/Pillars:', 'edel-museum-generator'); ?></strong> <?php _e('Click <strong>"Select"</strong> to choose 2D artworks.', 'edel-museum-generator'); ?></li>
                            <li><strong><?php _e('Free Space:', 'edel-museum-generator'); ?></strong> <?php _e('Click <strong>"Select"</strong> to choose 3D artworks (.glb).', 'edel-museum-generator'); ?></li>
                        </ul>
                    </li>
                    <li><strong><?php _e('Settings:', 'edel-museum-generator'); ?></strong> <?php _e('Adjust Brightness and <strong>Movement Speed</strong> (Default: 20.0).', 'edel-museum-generator'); ?></li>
                </ol>

                <h2 style="border-bottom: 2px solid #2271b1; padding-bottom: 10px; margin-bottom: 20px; margin-top: 40px;">
                    <span class="dashicons dashicons-move" style="font-size:24px;width:24px;height:24px;margin-right:5px;"></span>
                    <?php _e('Step 3: 3D Layout Editor', 'edel-museum-generator'); ?>
                </h2>
                <p><?php _e('Fine-tune the position, size, and angle of your artworks in real-time 3D.', 'edel-museum-generator'); ?></p>
                <div style="background: #f0f0f1; padding: 15px; border-left: 4px solid #2271b1;">
                    <strong><span class="dashicons dashicons-lightbulb"></span> <?php _e('Tip:', 'edel-museum-generator'); ?></strong> <?php _e('You must <strong>Publish/Update</strong> the Exhibition post first to generate the preview.', 'edel-museum-generator'); ?>
                </div>
                <ol style="margin-left: 20px; line-height: 1.8; margin-top: 15px;">
                    <li><?php _e('View the Exhibition post on the front-end (click "View Exhibition").', 'edel-museum-generator'); ?></li>
                    <li><?php _e('Click the <strong>"Switch to Editor"</strong> button at the top right.', 'edel-museum-generator'); ?></li>
                    <li><strong><?php _e('Select an Object:', 'edel-museum-generator'); ?></strong> <?php _e('Click on any artwork in the room.', 'edel-museum-generator'); ?></li>
                    <li><strong><?php _e('Controls:', 'edel-museum-generator'); ?></strong>
                        <ul style="list-style:disc; margin-left:20px; color:#555;">
                            <li><strong><?php _e('Move (T):', 'edel-museum-generator'); ?></strong> <?php _e('Drag arrows to move the object.', 'edel-museum-generator'); ?></li>
                            <li><strong><?php _e('Rotate (R):', 'edel-museum-generator'); ?></strong> <?php _e('Drag the circle or use the slider to rotate.', 'edel-museum-generator'); ?></li>
                            <li><strong><?php _e('Scale:', 'edel-museum-generator'); ?></strong> <?php _e('Use the slider to resize (0.1x to 3.0x).', 'edel-museum-generator'); ?></li>
                        </ul>
                    </li>
                    <li><?php _e('Click <strong>"Save Layout"</strong> to apply changes.', 'edel-museum-generator'); ?></li>
                </ol>

                <h2 style="border-bottom: 2px solid #2271b1; padding-bottom: 10px; margin-bottom: 20px; margin-top: 40px;">
                    <span class="dashicons dashicons-shortcode" style="font-size:24px;width:24px;height:24px;margin-right:5px;"></span>
                    <?php _e('Step 4: Display on Site', 'edel-museum-generator'); ?>
                </h2>
                <p><?php _e('Embed the museum on any page using a shortcode.', 'edel-museum-generator'); ?></p>
                <code style="background: #e5e5e5; padding: 10px; display: block; margin: 10px 0; font-size: 16px;">
                    [edel_museum id="123"]
                </code>
                <p><?php _e('Replace <strong>123</strong> with your Exhibition ID (found in the URL when editing the exhibition).', 'edel-museum-generator'); ?></p>

                <hr style="margin: 40px 0;">
                <p style="text-align: right; color: #888;">
                    Edel Museum Generator Pro v<?php echo EDEL_MUSEUM_GENERATOR_PRO_VERSION; ?>
                </p>
            </div>
        </div>
    <?php
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_media();
    }

    public function add_shortcode_column_head($columns) {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            if ($key === 'date') $new_columns['shortcode'] = __('Shortcode', 'edel-museum-generator');
            $new_columns[$key] = $value;
        }
        return $new_columns;
    }

    public function add_shortcode_column_content($column_name, $post_id) {
        if ($column_name == 'shortcode') {
            $shortcode = '[edel_museum id="' . $post_id . '"]';
            echo '<div style="display:flex; align-items:center; gap:5px;">';
            echo '<input type="text" value="' . esc_attr($shortcode) . '" readonly style="width:160px; background:#f0f0f1; border:1px solid #ccc; font-size:12px; padding:2px 5px;" onclick="this.select();">';
            echo '<button type="button" class="button button-small edel-copy-btn" data-code="' . esc_attr($shortcode) . '"><span class="dashicons dashicons-admin-page" style="line-height:26px; font-size:14px;"></span></button>';
            echo '</div>';
        }
    }

    public function render_shortcode_after_title($post) {
        if ($post->post_type !== 'edel_exhibition') return;
        if ($post->post_status === 'auto-draft') {
            echo '<div style="margin-top:10px; color:#666;">' . __('Save draft to generate shortcode.', 'edel-museum-generator') . '</div>';
            return;
        }
        $shortcode = '[edel_museum id="' . $post->ID . '"]';
    ?>
        <div style="margin-top: 15px; display: flex; align-items: center; gap: 10px; background: #fff; padding: 10px; border: 1px solid #ccd0d4; border-left: 4px solid #2271b1; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
            <strong style="font-size:13px;"><?php _e('Shortcode:', 'edel-museum-generator'); ?></strong>
            <input type="text" id="edel-top-shortcode" value="<?php echo esc_attr($shortcode); ?>" readonly style="background:#f9f9f9; border:1px solid #ddd; width:200px; font-family:monospace;" onclick="this.select();">
            <button type="button" class="button edel-copy-btn" data-code="<?php echo esc_attr($shortcode); ?>">
                <?php _e('Copy to Clipboard', 'edel-museum-generator'); ?>
            </button>
            <span id="edel-copy-msg" style="color:green; display:none; font-weight:bold; font-size:12px;"><?php _e('Copied!', 'edel-museum-generator'); ?></span>
        </div>
        <?php
    }

    public function print_admin_scripts() {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'edel_exhibition') {
        ?>
            <script>
                jQuery(document).ready(function($) {
                    $('.edel-copy-btn').on('click', function(e) {
                        e.preventDefault();
                        var code = $(this).data('code');
                        var $btn = $(this);
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(code).then(function() {
                                showCopied($btn);
                            }, function(err) {
                                alert('Press Ctrl+C to copy');
                            });
                        } else {
                            var $temp = $("<input>");
                            $("body").append($temp);
                            $temp.val(code).select();
                            document.execCommand("copy");
                            $temp.remove();
                            showCopied($btn);
                        }
                    });

                    function showCopied($btn) {
                        if ($btn.next('#edel-copy-msg').length) {
                            $btn.next('#edel-copy-msg').fadeIn().delay(1000).fadeOut();
                        } else {
                            var originalText = $btn.html();
                            $btn.text('<?php echo esc_js(__('Copied!', 'edel-museum-generator')); ?>');
                            setTimeout(function() {
                                $btn.html(originalText);
                            }, 1500);
                        }
                    }
                });
            </script>
        <?php
        }
    }

    public function allow_glb_uploads($mimes) {
        $mimes['glb']  = 'model/gltf-binary';
        $mimes['gltf'] = 'model/gltf+json';
        return $mimes;
    }

    public function fix_glb_mime_type_check($data, $file, $filename, $mimes) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ('glb' === $ext) {
            $data['ext']  = 'glb';
            $data['type'] = 'model/gltf-binary';
        }
        if ('gltf' === $ext) {
            $data['ext']  = 'gltf';
            $data['type'] = 'model/gltf+json';
        }
        return $data;
    }

    public function register_cpt() {
        register_post_type('edel_artwork', array(
            'labels' => array(
                'name' => __('Museum Artworks', 'edel-museum-generator'),
                'singular_name' => __('Artwork', 'edel-museum-generator'),
                'add_new' => __('Add New Artwork', 'edel-museum-generator'),
                'add_new_item' => __('Add New Artwork', 'edel-museum-generator'),
                'edit_item' => __('Edit Artwork', 'edel-museum-generator'),
            ),
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-art',
            'show_in_rest' => true,
        ));

        register_post_type('edel_exhibition', array(
            'labels' => array(
                'name' => __('Exhibition Settings', 'edel-museum-generator'),
                'singular_name' => __('Exhibition', 'edel-museum-generator'),
                'add_new' => __('Add New Exhibition', 'edel-museum-generator'),
                'add_new_item' => __('Add New Exhibition', 'edel-museum-generator'),
                'edit_item' => __('Edit Exhibition', 'edel-museum-generator'),
            ),
            'public' => true,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-building',
            'show_in_rest' => false,
        ));
    }

    public function add_meta_boxes() {
        add_meta_box('edel_art_meta', __('Artwork Options', 'edel-museum-generator'), array($this, 'render_art_meta'), 'edel_artwork', 'normal', 'high');
        add_meta_box('edel_room_meta', __('Room Settings & Artwork Placement', 'edel-museum-generator'), array($this, 'render_room_meta'), 'edel_exhibition', 'normal', 'high');
        add_meta_box('edel_pro_status', 'Edel Museum Pro', array($this, 'render_pro_status'), 'edel_exhibition', 'side', 'high');
    }

    public function render_art_meta($post) {
        $link = get_post_meta($post->ID, '_edel_art_link', true);
        $glb  = get_post_meta($post->ID, '_edel_art_glb', true);
        // ★追加: フレーム設定の取得 (デフォルトは wood)
        $frame = get_post_meta($post->ID, '_edel_art_frame', true) ?: 'wood';

        wp_nonce_field('edel_museum_meta_save', 'edel_museum_meta_nonce');

        // サンプル用URL (省略可能ですが残しておきます)
        $sample_1 = 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/SheenChair/glTF-Binary/SheenChair.glb';
        $sample_2 = 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/AntiqueCamera/glTF-Binary/AntiqueCamera.glb';
        $sample_3 = 'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/Duck/glTF-Binary/Duck.glb';
        ?>
        <p>
            <label for="edel_art_link"><strong><?php _e('Link URL:', 'edel-museum-generator'); ?></strong></label><br>
            <input type="text" id="edel_art_link" name="edel_art_link" value="<?php echo esc_attr($link); ?>" style="width:100%;" placeholder="https://...">
            <span class="description"><?php _e('URL to navigate when artwork is clicked.', 'edel-museum-generator'); ?></span>
        </p>

        <hr>
        <p>
            <label for="edel_art_frame"><strong><?php _e('Frame Style:', 'edel-museum-generator'); ?></strong></label><br>
            <select name="edel_art_frame" id="edel_art_frame" style="width:100%; max-width:300px;">
                <option value="wood" <?php selected($frame, 'wood'); ?>><?php _e('Wood (Standard)', 'edel-museum-generator'); ?></option>
                <option value="black" <?php selected($frame, 'black'); ?>><?php _e('Black (Modern)', 'edel-museum-generator'); ?></option>
                <option value="white" <?php selected($frame, 'white'); ?>><?php _e('White (Minimal)', 'edel-museum-generator'); ?></option>
                <option value="none" <?php selected($frame, 'none'); ?>><?php _e('No Frame (Canvas)', 'edel-museum-generator'); ?></option>
            </select>
            <br>
            <span class="description"><?php _e('Select the frame style for 2D images. (Ignored for 3D models)', 'edel-museum-generator'); ?></span>
        </p>

        <hr>
        <p>
            <label for="edel_art_glb"><strong><?php _e('3D Model URL (.glb):', 'edel-museum-generator'); ?></strong></label><br>
            <input type="text" id="edel_art_glb" name="edel_art_glb" value="<?php echo esc_attr($glb); ?>" style="width:100%;" placeholder="https://.../model.glb">
            <button type="button" class="button" id="edel_upload_glb_btn"><?php _e('Select from Media Library', 'edel-museum-generator'); ?></button>
            <br>
            <span class="description"><?php _e('Upload a .glb file to Media Library.', 'edel-museum-generator'); ?></span>
        </p>

        <script>
            jQuery(document).ready(function($) {
                var frame;
                $('#edel_upload_glb_btn').on('click', function(e) {
                    e.preventDefault();
                    if (frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media({
                        title: '<?php echo esc_js(__('Select 3D Model (.glb)', 'edel-museum-generator')); ?>',
                        button: {
                            text: '<?php echo esc_js(__('Use this model', 'edel-museum-generator')); ?>'
                        },
                        multiple: false,
                        library: {
                            type: 'model/gltf-binary'
                        }
                    });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $('#edel_art_glb').val(attachment.url);
                    });
                    frame.open();
                });
            });
        </script>
    <?php
    }

    public function render_pro_status() {
    ?>
        <div style="text-align: center; color: green; font-weight: bold;">
            <span class="dashicons dashicons-yes-alt" style="font-size: 20px; width: 20px; height: 20px;"></span>
            <?php _e('Pro Version Active', 'edel-museum-generator'); ?>
        </div>
        <p style="font-size: 12px; color: #666; text-align:center;">
            <?php _e('Thank you for your support!', 'edel-museum-generator'); ?>
        </p>
    <?php
    }

    public function render_room_meta($post) {
        $meta = get_post_meta($post->ID, '_edel_exhibition_data', true) ?: array();
        $defaults = array(
            'floor_img' => '',
            'wall_img' => '',
            'pillar_img' => '',
            'ceiling_img' => '',
            'pillars' => '0',
            'room_brightness' => '1.2',
            'spot_brightness' => '1.0',
            'movement_speed' => '20.0',
            'north' => '',
            'south' => '',
            'east' => '',
            'west' => '',
            'p1_north' => '',
            'p1_south' => '',
            'p1_east' => '',
            'p1_west' => '',
            'p2_north' => '',
            'p2_south' => '',
            'p2_east' => '',
            'p2_west' => '',
            'free_objects' => '',
        );
        $meta = array_merge($defaults, $meta);

        wp_nonce_field('edel_museum_meta_save', 'edel_museum_meta_nonce');

        $artworks = get_posts(array('post_type' => 'edel_artwork', 'posts_per_page' => -1, 'post_status' => 'publish'));
    ?>
        <style>
            .edel-meta-table {
                width: 100%;
                border-collapse: collapse;
            }

            .edel-meta-table th {
                text-align: left;
                width: 200px;
                padding: 10px 0;
                border-bottom: 1px solid #eee;
            }

            .edel-meta-table td {
                padding: 10px 0;
                border-bottom: 1px solid #eee;
            }

            .edel-meta-table input[type="text"],
            .edel-meta-table input[type="number"] {
                width: 100%;
            }

            .edel-section-title {
                background: #f0f0f1;
                padding: 10px;
                margin: 20px 0 10px;
                font-weight: bold;
            }

            #edel-art-picker-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                z-index: 9999;
            }

            #edel-picker-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                width: 90%;
                max-width: 800px;
                height: 80%;
                border-radius: 5px;
                display: flex;
                flexDirection: column;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            }

            #edel-picker-header {
                padding: 15px;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: #f9f9f9;
            }

            #edel-picker-body {
                padding: 15px;
                overflow-y: auto;
                flex: 1;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-content: flex-start;
            }

            .edel-art-item {
                width: 120px;
                border: 2px solid #ddd;
                padding: 5px;
                border-radius: 4px;
                cursor: pointer;
                text-align: center;
                transition: 0.2s;
                background: #fff;
                position: relative;
            }

            .edel-art-item:hover {
                border-color: #2271b1;
            }

            .edel-art-item.selected {
                border-color: #2271b1;
                background: #e5f5fa;
            }

            .edel-art-item.disabled {
                opacity: 0.6;
                pointer-events: none;
                background: #f0f0f1;
                border-color: #ccc;
            }

            .edel-art-item.disabled::after {
                content: 'Placed';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 0, 0, 0.7);
                color: #fff;
                padding: 2px 6px;
                font-size: 10px;
                border-radius: 3px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .edel-art-item.hidden {
                display: none;
            }

            .edel-art-thumb {
                width: 100%;
                height: 80px;
                object-fit: cover;
                background: #eee;
            }

            .edel-art-title {
                font-size: 11px;
                margin-top: 5px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .edel-open-picker,
            .edel-upload-texture {
                margin-left: 5px !important;
            }
        </style>

        <div class="edel-section-title"><?php _e('Lighting & Movement', 'edel-museum-generator'); ?></div>
        <table class="edel-meta-table">
            <tr>
                <th><?php _e('Room Brightness', 'edel-museum-generator'); ?></th>
                <td><input type="number" name="edel_room[room_brightness]" value="<?php echo esc_attr($meta['room_brightness']); ?>" step="0.1" min="0" max="2.5"></td>
            </tr>
            <tr>
                <th><?php _e('Spotlight Brightness', 'edel-museum-generator'); ?></th>
                <td><input type="number" name="edel_room[spot_brightness]" value="<?php echo esc_attr($meta['spot_brightness']); ?>" step="0.1" min="0" max="2.5"></td>
            </tr>
            <tr>
                <th><?php _e('Movement Speed', 'edel-museum-generator'); ?></th>
                <td>
                    <input type="number" name="edel_room[movement_speed]" value="<?php echo esc_attr($meta['movement_speed']); ?>" step="1.0" min="1.0" max="50.0">
                    <p class="description" style="font-size:11px;">Default: 20.0 (Range: 1.0 - 50.0)</p>
                </td>
            </tr>
        </table>

        <div class="edel-section-title"><?php _e('Textures (Image URL)', 'edel-museum-generator'); ?></div>
        <table class="edel-meta-table">
            <?php
            $textures = array(
                'floor_img' => __('Floor', 'edel-museum-generator'),
                'wall_img' => __('Wall', 'edel-museum-generator'),
                'pillar_img' => __('Pillar', 'edel-museum-generator'),
                'ceiling_img' => __('Ceiling', 'edel-museum-generator')
            );
            foreach ($textures as $key => $label):
            ?>
                <tr>
                    <th><?php echo esc_html($label); ?></th>
                    <td>
                        <div style="display:flex;">
                            <input type="text" id="edel_room_<?php echo $key; ?>" name="edel_room[<?php echo $key; ?>]" value="<?php echo esc_attr($meta[$key]); ?>">
                            <button type="button" class="button edel-upload-texture" data-target="edel_room_<?php echo $key; ?>"><?php _e('Select Image', 'edel-museum-generator'); ?></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="edel-section-title"><?php _e('Structure', 'edel-museum-generator'); ?></div>
        <table class="edel-meta-table">
            <tr>
                <th><?php _e('Number of Pillars (0-2)', 'edel-museum-generator'); ?></th>
                <td><input type="number" name="edel_room[pillars]" value="<?php echo esc_attr($meta['pillars']); ?>" min="0" max="2"></td>
            </tr>
        </table>

        <div class="edel-section-title"><?php _e('Free Placement (3D Models / Objects)', 'edel-museum-generator'); ?></div>
        <table class="edel-meta-table">
            <tr>
                <th><?php _e('Free Space', 'edel-museum-generator'); ?></th>
                <td>
                    <div style="display:flex;">
                        <input type="text" id="edel_room_free" class="edel-placement-input" name="edel_room[free_objects]" value="<?php echo esc_attr($meta['free_objects']); ?>">
                        <button type="button" class="button edel-open-picker" data-target="edel_room_free"><?php _e('Select', 'edel-museum-generator'); ?></button>
                    </div>
                    <p class="description"><?php _e('Enter IDs separated by commas. (Only artworks with .glb models can be selected)', 'edel-museum-generator'); ?></p>
                </td>
            </tr>
        </table>

        <div class="edel-section-title"><?php _e('Wall Placement (Images)', 'edel-museum-generator'); ?></div>
        <table class="edel-meta-table">
            <?php
            $walls = array('north' => __('North Wall', 'edel-museum-generator'), 'south' => __('South Wall', 'edel-museum-generator'), 'east' => __('East Wall', 'edel-museum-generator'), 'west' => __('West Wall', 'edel-museum-generator'));
            foreach ($walls as $key => $label):
            ?>
                <tr>
                    <th><?php echo esc_html($label); ?></th>
                    <td>
                        <div style="display:flex;">
                            <input type="text" id="edel_room_<?php echo $key; ?>" class="edel-placement-input" name="edel_room[<?php echo $key; ?>]" value="<?php echo esc_attr($meta[$key]); ?>">
                            <button type="button" class="button edel-open-picker" data-target="edel_room_<?php echo $key; ?>"><?php _e('Select', 'edel-museum-generator'); ?></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="edel-section-title"><?php _e('Pillar Placement', 'edel-museum-generator'); ?></div>
        <table class="edel-meta-table">
            <?php
            $pillar_keys = array(
                'p1_north' => __('Pillar 1 North', 'edel-museum-generator'),
                'p1_south' => __('Pillar 1 South', 'edel-museum-generator'),
                'p1_east' => __('Pillar 1 East', 'edel-museum-generator'),
                'p1_west' => __('Pillar 1 West', 'edel-museum-generator'),
                'p2_north' => __('Pillar 2 North', 'edel-museum-generator'),
                'p2_south' => __('Pillar 2 South', 'edel-museum-generator'),
                'p2_east' => __('Pillar 2 East', 'edel-museum-generator'),
                'p2_west' => __('Pillar 2 West', 'edel-museum-generator')
            );
            $i = 0;
            foreach ($pillar_keys as $key => $label):
                if ($i === 4) echo '<tr><td colspan="2" style="background:#fafafa;"></td></tr>';
            ?>
                <tr>
                    <th><?php echo esc_html($label); ?></th>
                    <td>
                        <div style="display:flex;">
                            <input type="text" id="edel_room_<?php echo $key; ?>" class="edel-placement-input" name="edel_room[<?php echo $key; ?>]" value="<?php echo esc_attr($meta[$key]); ?>">
                            <button type="button" class="button edel-open-picker" data-target="edel_room_<?php echo $key; ?>"><?php _e('Select', 'edel-museum-generator'); ?></button>
                        </div>
                    </td>
                </tr>
            <?php $i++;
            endforeach; ?>
        </table>

        <div id="edel-art-picker-modal">
            <div id="edel-picker-content">
                <div id="edel-picker-header">
                    <h3 id="edel-picker-title"><?php _e('Select Artworks', 'edel-museum-generator'); ?></h3>
                    <button type="button" id="edel-picker-close" class="button"><?php _e('Close', 'edel-museum-generator'); ?></button>
                </div>
                <div id="edel-picker-body">
                    <?php if ($artworks): foreach ($artworks as $art):
                            $img_url = get_the_post_thumbnail_url($art->ID, 'thumbnail');
                            $glb_url = get_post_meta($art->ID, '_edel_art_glb', true);
                            $has_img = $img_url ? '1' : '0';
                            $has_glb = !empty($glb_url) ? '1' : '0';
                            $label_glb = !empty($glb_url) ? ' <span style="color:green;font-weight:bold;">(3D)</span>' : '';
                    ?>
                            <div class="edel-art-item"
                                data-id="<?php echo $art->ID; ?>"
                                data-has-img="<?php echo $has_img; ?>"
                                data-has-glb="<?php echo $has_glb; ?>">
                                <?php if ($img_url): ?><img src="<?php echo $img_url; ?>" class="edel-art-thumb"><?php else: ?><div class="edel-art-thumb" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:10px;">No Image</div><?php endif; ?>
                                <div class="edel-art-title"><?php echo esc_html($art->post_title); ?><?php echo $label_glb; ?></div>
                                <div style="font-size:10px;color:#888;">ID: <?php echo $art->ID; ?></div>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p style="padding:20px;"><?php _e('No artworks found.', 'edel-museum-generator'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // --- Texture Uploader ---
                var textureFrame;
                $('.edel-upload-texture').on('click', function(e) {
                    e.preventDefault();
                    var targetId = $(this).data('target');

                    if (textureFrame) {
                        textureFrame.targetId = targetId;
                        textureFrame.open();
                        return;
                    }

                    textureFrame = wp.media({
                        title: '<?php echo esc_js(__('Select Texture Image', 'edel-museum-generator')); ?>',
                        button: {
                            text: '<?php echo esc_js(__('Use this image', 'edel-museum-generator')); ?>'
                        },
                        multiple: false,
                        library: {
                            type: 'image'
                        }
                    });

                    textureFrame.targetId = targetId;

                    textureFrame.on('select', function() {
                        var attachment = textureFrame.state().get('selection').first().toJSON();
                        $('#' + textureFrame.targetId).val(attachment.url);
                    });

                    textureFrame.open();
                });

                // --- Art Picker Logic ---
                var targetInputId = null;
                var $modal = $('#edel-art-picker-modal');
                var $title = $('#edel-picker-title');

                $('.edel-open-picker').on('click', function() {
                    targetInputId = $(this).data('target');
                    var $targetInput = $('#' + targetInputId);
                    var val = $targetInput.val();

                    var currentIds = val ? val.split(',').map(function(s) {
                        return s.trim();
                    }) : [];

                    var usedIds = [];
                    $('.edel-placement-input').each(function() {
                        if ($(this).attr('id') !== targetInputId) {
                            var v = $(this).val();
                            if (v) {
                                var parts = v.split(',');
                                parts.forEach(function(s) {
                                    if (s.trim()) usedIds.push(s.trim());
                                });
                            }
                        }
                    });

                    var isFreeSpace = (targetInputId === 'edel_room_free');
                    if (isFreeSpace) {
                        $title.text('<?php echo esc_js(__('Select 3D Objects (Free Space)', 'edel-museum-generator')); ?>');
                    } else {
                        $title.text('<?php echo esc_js(__('Select 2D Artworks (Wall)', 'edel-museum-generator')); ?>');
                    }

                    $('.edel-art-item').each(function() {
                        var $item = $(this);
                        var id = String($item.data('id'));
                        var hasImg = $item.data('has-img') == 1;
                        var hasGlb = $item.data('has-glb') == 1;

                        $item.removeClass('selected disabled hidden');

                        if (isFreeSpace) {
                            if (!hasGlb) {
                                $item.addClass('hidden');
                                return;
                            }
                        } else {
                            if (!hasImg) {
                                $item.addClass('hidden');
                                return;
                            }
                        }

                        if (currentIds.indexOf(id) !== -1) {
                            $item.addClass('selected');
                        } else if (usedIds.indexOf(id) !== -1) {
                            $item.addClass('disabled');
                        }
                    });

                    $modal.show();
                });

                $('#edel-picker-close').on('click', function() {
                    $modal.hide();
                });
                $modal.on('click', function(e) {
                    if (e.target === this) $modal.hide();
                });

                $('.edel-art-item').on('click', function() {
                    if ($(this).hasClass('disabled') || $(this).hasClass('hidden')) return;
                    $(this).toggleClass('selected');
                    var ids = [];
                    $('.edel-art-item.selected:not(.hidden)').each(function() {
                        ids.push($(this).data('id'));
                    });
                    $('#' + targetInputId).val(ids.join(', '));
                });
            });
        </script>
<?php
    }

    public function save_meta_fields($post_id) {
        if (!isset($_POST['edel_museum_meta_nonce']) || !wp_verify_nonce($_POST['edel_museum_meta_nonce'], 'edel_museum_meta_save')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        if (isset($_POST['edel_art_link'])) {
            update_post_meta($post_id, '_edel_art_link', sanitize_text_field($_POST['edel_art_link']));
        }
        if (isset($_POST['edel_art_frame'])) {
            update_post_meta($post_id, '_edel_art_frame', sanitize_text_field($_POST['edel_art_frame']));
        }
        if (isset($_POST['edel_art_glb'])) {
            update_post_meta($post_id, '_edel_art_glb', sanitize_text_field($_POST['edel_art_glb']));
        }

        if (isset($_POST['edel_room'])) {
            $clean_data = array();
            foreach ($_POST['edel_room'] as $k => $v) {
                $clean_data[$k] = sanitize_text_field($v);
            }
            update_post_meta($post_id, '_edel_exhibition_data', $clean_data);

            $old_json = get_post_meta($post_id, '_edel_museum_layout', true);
            $old_json = is_string($old_json) ? wp_unslash($old_json) : $old_json;
            $old_layout = $old_json ? json_decode($old_json, true) : null;
            $new_layout = $this->generate_layout_data($post_id, $clean_data);

            if ($old_layout && isset($old_layout['artworks']) && isset($new_layout['artworks'])) {
                $old_map = array();
                foreach ($old_layout['artworks'] as $art) {
                    $key = $art['id'] . '_' . $art['wall'];
                    $old_map[$key] = $art;
                }
                foreach ($new_layout['artworks'] as &$new_art) {
                    $key = $new_art['id'] . '_' . $new_art['wall'];
                    if (isset($old_map[$key])) {
                        $old_art = $old_map[$key];
                        $new_art['x'] = $old_art['x'];
                        $new_art['y'] = $old_art['y'];
                        $new_art['z'] = $old_art['z'];
                        if (isset($old_art['scale'])) $new_art['scale'] = $old_art['scale'];
                        if (isset($old_art['rotationY'])) $new_art['rotationY'] = $old_art['rotationY'];
                    }
                }
            }
            $json = wp_json_encode($new_layout, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            update_post_meta($post_id, '_edel_museum_layout', wp_slash($json));
        }
    }

    private function generate_layout_data($post_id, $meta) {
        $room_w = 16;
        $room_h = 4;
        $room_d = 16;
        $num_pillars = intval($meta['pillars']);
        $pillars_data = array();
        $pillar_size = 2;
        if ($num_pillars === 1) {
            $pillars_data[] = array('id' => 'p1', 'x' => 0, 'z' => 0, 'w' => $pillar_size, 'd' => $pillar_size);
        } elseif ($num_pillars === 2) {
            $pillars_data[] = array('id' => 'p1', 'x' => -3, 'z' => 0, 'w' => $pillar_size, 'd' => $pillar_size);
            $pillars_data[] = array('id' => 'p2', 'x' => 3, 'z' => 0, 'w' => $pillar_size, 'd' => $pillar_size);
        }
        $layout = array(
            'room' => array(
                'width' => $room_w,
                'height' => $room_h,
                'depth' => $room_d,
                'floor_image'   => $meta['floor_img'],
                'wall_image'    => $meta['wall_img'],
                'pillar_image'  => $meta['pillar_img'],
                'ceiling_image' => $meta['ceiling_img'],
                'room_brightness' => isset($meta['room_brightness']) ? $meta['room_brightness'] : '1.2',
                'spot_brightness' => isset($meta['spot_brightness']) ? $meta['spot_brightness'] : '1.0',
                'movement_speed'  => isset($meta['movement_speed']) ? $meta['movement_speed'] : '20.0',
            ),
            'pillars' => $pillars_data,
            'artworks' => array(),
        );
        $walls_map = array(
            'north' => $meta['north'],
            'south' => $meta['south'],
            'east'  => $meta['east'],
            'west'  => $meta['west'],
            'p1_north' => $meta['p1_north'],
            'p1_south' => $meta['p1_south'],
            'p1_east'  => $meta['p1_east'],
            'p1_west'  => $meta['p1_west'],
            'p2_north' => $meta['p2_north'],
            'p2_south' => $meta['p2_south'],
            'p2_east'  => $meta['p2_east'],
            'p2_west'  => $meta['p2_west'],
            'free'     => isset($meta['free_objects']) ? $meta['free_objects'] : '',
        );
        foreach ($walls_map as $wall_key => $ids_str) {
            if (empty($ids_str)) continue;
            $ids = array_filter(array_map('trim', explode(',', $ids_str)));
            if (empty($ids)) continue;

            $is_free = ($wall_key === 'free');
            $is_pillar = (strpos($wall_key, 'p1_') === 0 || strpos($wall_key, 'p2_') === 0);
            $target_pillar = null;
            if ($is_pillar) {
                $pid = substr($wall_key, 0, 2);
                foreach ($pillars_data as $p) {
                    if ($p['id'] === $pid) {
                        $target_pillar = $p;
                        break;
                    }
                }
                if (!$target_pillar) continue;
            }
            if ($is_pillar) {
                $dir = substr($wall_key, 3);
                $wall_w = ($dir === 'north' || $dir === 'south') ? $target_pillar['w'] : $target_pillar['d'];
            } else {
                $wall_w = ($wall_key === 'north' || $wall_key === 'south') ? $room_w : $room_d;
            }
            $margin = 0.5;
            $effective_width = $wall_w - ($margin * 2);
            $count = count($ids);
            $spacing = 2.0;
            if ($count * $spacing > $effective_width) $spacing = $effective_width / $count;
            $total_span = ($count - 1) * $spacing;
            $start_pos = - ($total_span / 2);
            foreach ($ids as $i => $art_id) {
                $art_post = get_post($art_id);
                if (!$art_post || $art_post->post_type !== 'edel_artwork') continue;
                $img_url = get_the_post_thumbnail_url($art_id, 'large');
                $glb_url = get_post_meta($art_id, '_edel_art_glb', true);
                if (!$img_url && !$glb_url) continue;

                $offset = $start_pos + ($i * $spacing);
                $px = 0;
                $pz = 0;
                $p_offset = 0.05;
                if ($is_free) {
                    $px = 0;
                    $pz = 5;
                } elseif (!$is_pillar) {
                    if ($wall_key === 'north') {
                        $px = $offset;
                        $pz = - ($room_d / 2) + $p_offset;
                    } elseif ($wall_key === 'south') {
                        $px = $offset;
                        $pz = ($room_d / 2) - $p_offset;
                    } elseif ($wall_key === 'east') {
                        $pz = $offset;
                        $px = ($room_w / 2) - $p_offset;
                    } elseif ($wall_key === 'west') {
                        $pz = $offset;
                        $px = - ($room_w / 2) + $p_offset;
                    }
                } else {
                    $cx = $target_pillar['x'];
                    $cz = $target_pillar['z'];
                    $hw = $target_pillar['w'] / 2;
                    $hd = $target_pillar['d'] / 2;
                    $dir = substr($wall_key, 3);
                    if ($dir === 'north') {
                        $px = $cx + $offset;
                        $pz = $cz - $hd - $p_offset;
                    } elseif ($dir === 'south') {
                        $px = $cx + $offset;
                        $pz = $cz + $hd + $p_offset;
                    } elseif ($dir === 'east') {
                        $pz = $cz + $offset;
                        $px = $cx + $hw + $p_offset;
                    } elseif ($dir === 'west') {
                        $pz = $cz + $offset;
                        $px = $cx - $hw - $p_offset;
                    }
                }
                $layout['artworks'][] = array(
                    'id'    => $art_id,
                    'image' => $img_url,
                    'glb'   => $glb_url,
                    'title' => $art_post->post_title,
                    'desc'  => wp_strip_all_tags($art_post->post_content),
                    'link'  => get_post_meta($art_id, '_edel_art_link', true),
                    'wall'  => $wall_key,
                    'x'     => $px,
                    'y'     => $is_free ? 0 : 1.5,
                    'z'     => $pz,
                    'scale' => array('x' => 1, 'y' => 1, 'z' => 1),
                );
            }
        }
        return $layout;
    }

    public function ajax_save_layout() {
        if (!current_user_can('edit_posts')) wp_send_json_error(array('message' => __('Permission denied', 'edel-museum-generator')));
        check_ajax_referer(EDEL_MUSEUM_GENERATOR_PRO_SLUG, '_nonce');

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $layout  = isset($_POST['layout'])  ? wp_unslash($_POST['layout']) : '';

        if (!$post_id || !$layout) wp_send_json_error(array('message' => __('Missing data', 'edel-museum-generator')));

        update_post_meta($post_id, '_edel_museum_layout', wp_slash($layout));
        wp_send_json_success(array('message' => __('Saved successfully!', 'edel-museum-generator')));
    }

    public function ajax_clear_layout() {
        if (!current_user_can('edit_posts')) wp_send_json_error(array('message' => __('Permission denied', 'edel-museum-generator')));
        check_ajax_referer(EDEL_MUSEUM_GENERATOR_PRO_SLUG, '_nonce');

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id) wp_send_json_error(array('message' => __('Missing data', 'edel-museum-generator')));

        delete_post_meta($post_id, '_edel_museum_layout');
        wp_send_json_success(array('message' => __('Reset to default layout.', 'edel-museum-generator')));
    }
}
