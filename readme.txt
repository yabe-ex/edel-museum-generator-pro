=== Edel Museum Generator Pro ===
Contributors: yabea
Tags: 3d, gallery, museum, virtual tour, three.js, portfolio, woocommerce, frame, caption
Requires at least: 5.8
Tested up to: 6.9
Stable tag: 1.4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Premium version.
Unlock Link integration, 3D Model (GLB) support, Artwork Frames, and Title Labels.

== Description ==

**Edel Museum Generator Pro** allows you to easily create professional-grade 3D virtual museums and galleries on your WordPress site.
This premium version extends the capabilities of the standard generator with powerful features for sales, immersive experiences, and detailed customization.

### ✨ Pro Features

* **Everything in Lite:** Includes all features from the standard version.
* **Link Integration:** Add external links to your artworks. Perfect for linking to WooCommerce product pages, portfolios, or affiliate sites.
* **3D Model Support (GLB/GLTF):** Go beyond 2D images. Place 3D sculptures, furniture, or artifacts in the free space of your museum.
* **Artwork Frames:** Choose from **Wood**, **Black**, **White**, or **No Frame** for your 2D paintings to match your exhibition style.
* **Title Labels:** Automatically displays a stylish white plate with the artwork title below each 2D piece. You can toggle this ON/OFF and adjust font size.
* **Advanced Structure:** Add pillars (columns) to your room layout to create more complex gallery spaces.
* **Immersive 3D Walkthrough:** Smooth FPS-style navigation with keyboard/mouse or touch controls.
* **Visual Editor:** Real-time 3D editor in the backend to move, rotate, and scale your artworks.
* **Full I18n Support:** Ready for translation into any language.

== Installation ==

**Important:** Please deactivate the Lite version (Edel Museum Generator) before activating the Pro version.

1.  Upload the `edel-museum-generator-pro` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Go to **Museum Artworks** to add your art pieces. You can set the **Frame Style** here.
4.  Go to **Exhibition Settings** to configure your room. You can toggle **Show Title Labels** and adjust **Label Font Size** here.
5.  Copy the shortcode (e.g., `[edel_museum id="123"]`) and paste it into any post or page.

== Screenshots ==

1.  **Pro Gallery View:** Displaying paintings with frames and title labels.
2.  **Visual Editor:** Fine-tuning position and rotation of 3D models.
3.  **Artwork Settings:** Adding links, uploading GLB files, and selecting frame styles.

== Frequently Asked Questions ==

= Can I hide the title labels? =
Yes, in the **Exhibition Settings**, uncheck "Show Title Labels" to hide them for that room.

= Can I change the frame of my paintings? =
Yes, in the **Artwork Options** meta box (on the artwork edit screen), you can select from "Wood", "Black", "White", or "No Frame".

= How do I adjust the title label size? =
In the **Exhibition Settings**, under the "Lighting & Movement" section, you can find the "Label Font Size" setting.

= Can I use 3D models? =
Yes, the Pro version supports `.glb` and `.gltf` file formats. You can place them anywhere in the "Free Space" area.

= How do the links work? =
In the artwork settings, you can enter a "Link URL".
When a visitor clicks on the artwork in the virtual museum, a "View Details" button appears in the popup, directing them to your specified URL.

== Changelog ==

= 1.4.0 =
* Feature: Added Title Labels below 2D artworks.
* Feature: Added "Show Title Labels" toggle in Exhibition Settings.
* Feature: Added "Label Font Size" setting.
* Update: Tested up to WordPress 6.9.

= 1.3.0 =
* Feature: Added Artwork Frame selection (Wood, Black, White, None).

= 1.2.0 =
* Update: Full internationalization (i18n) support.
* Feature: Added "Copy Shortcode" button to the admin list and edit screen.
* Improvement: Enhanced Help UI and Lighting controls in Viewer mode.
* Improvement: Optimized asset loading with progress bar.
* Fix: Solved security nonce issues for smoother Ajax operations.

= 1.0.0 =
* Initial release.