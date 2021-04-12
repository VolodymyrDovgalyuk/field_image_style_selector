<?php

namespace Drupal\field_image_style_selector\Plugin\Field\FieldFormatter;

use Drupal\text\Plugin\Field\FieldFormatter\TextDefaultFormatter;

/**
 * Plugin extends the 'text_default' formatter.
 *
 * @FieldFormatter(
 *   id = "image_style_selector",
 *   label = @Translation("Selected image style name as text"),
 *   field_types = {
 *     "image_style_selector"
 *   }
 * )
 */
class ImageStyleSelectorFormatter extends TextDefaultFormatter {}
