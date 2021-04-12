<?php

namespace Drupal\field_image_style_selector\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\image\Entity\ImageStyle;

/**
 * Plugin implementation of the 'list_string' field type.
 *
 * @FieldType(
 *   id = "image_style_selector",
 *   label = @Translation("Image Style Selector"),
 *   description = @Translation("This field stores image style."),
 *   default_formatter = "image_style_selector",
 *   default_widget = "image_style_selector_select",
 * )
 */
class ImageStyleSelectorItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'image_styles' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Image style'))
      ->addConstraint('Length', ['max' => 255])
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type'   => 'varchar',
          'length' => 255,
        ],
      ],
      'indexes' => [
        'value' => ['value'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    $settings = $this->getSettings();

    // Get all image styles.
    $imageStyles = ImageStyle::loadMultiple();

    foreach ($imageStyles as $imageStyleId => $imageStyle) {
      if (!isset($element['image_styles'])) {
        $element['image_styles'] = [
          '#type'       => 'fieldset',
          '#tree'       => TRUE,
          '#title'      => t('Available image styles'),
          '#attributes' => ['class' => ['image-style-selector-image-styles']],
        ];
      }

      $element['image_styles'][$imageStyleId]['enable'] = [
        '#type'          => 'checkbox',
        '#title'         => $imageStyle->label() . ' (' . $imageStyleId . ')',
        '#default_value' => isset($settings['image_styles'][$imageStyleId]) && $settings['image_styles'][$imageStyleId]['enable'] ?: FALSE,
      ];

      $element['image_styles'][$imageStyleId]['prefix']['#markup'] = '<div class="settings">';
      $element['image_styles'][$imageStyleId]['suffix']['#markup'] = '</div>';
    }
    return $element;
  }
}
