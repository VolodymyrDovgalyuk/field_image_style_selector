<?php

namespace Drupal\field_image_style_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for the 'image_style_selector*' widgets.
 */
abstract class ImageStyleSelectorWidgetBase extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * List of available image styles.
   *
   * @var array $imageStyles
   */
  protected $imageStyles = [];

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $imageStyleStorage;

  /**
   * ImageStyleSelectorWidgetBase constructor.
   *
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param array $settings
   * @param array $third_party_settings
   * @param \Drupal\Core\Entity\EntityTypeManager $image_style_repository
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityTypeManager $image_style_repository) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->imageStyleStorage = $image_style_repository->getStorage('image_style');

    $fieldSettings = $field_definition->getSettings();

    // Get all image styles.
    $imageStyles = $this->imageStyleStorage->loadMultiple();

    // Reduce options by enabled image styles.
    foreach (array_keys($imageStyles) as $imageStyle) {
      if (isset($fieldSettings['image_styles'][$imageStyle]['enable']) && $fieldSettings['image_styles'][$imageStyle]['enable']) {
        continue;
      }
      unset($imageStyles[$imageStyle]);
    }

    // Show all image styles in widget when no image styles are enabled.
    if (!count($imageStyles)) {
      $imageStyles = $this->imageStyleStorage->loadMultiple();
    }

    $this->imageStyles = $imageStyles;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $plugin_definition, $configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('entity_type.manager'));
  }
}
