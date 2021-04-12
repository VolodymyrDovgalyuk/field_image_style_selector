<?php

namespace Drupal\field_image_style_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldWidget(
 *  id = "image_style_selector_select",
 *  label = @Translation("Select list"),
 *  field_types = {"image_style_selector"}
 * )
 */
class ImageStyleSelectorSelect extends ImageStyleSelectorWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Pull together info we need to build the element.
    $value = isset($items[$delta]->value) ? $items[$delta]->value : NULL;

    $options = [];
    $imageStyles = $this->imageStyles;
    foreach ($imageStyles as $imageStyleId => $imageStyle) {
      $options[$imageStyleId] = $imageStyle->label();
    }

    // Build the element render array.
    $element['value'] = $element + [
        '#type'          => 'select',
        '#options'       => $options,
        '#default_value' => $value,
        '#empty_option'  => $this->t('Select image style'),
      ];
    return $element;
  }
}
