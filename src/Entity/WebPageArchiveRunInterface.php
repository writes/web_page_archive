<?php

namespace Drupal\web_page_archive\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Web page archive run entities.
 *
 * @ingroup web_page_archive
 */
interface WebPageArchiveRunInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Web page archive run name.
   *
   * @return string
   *   Name of the Web page archive run.
   */
  public function getName();

  /**
   * Sets the Web page archive run name.
   *
   * @param string $name
   *   The Web page archive run name.
   *
   * @return \Drupal\web_page_archive\Entity\WebPageArchiveRunInterface
   *   The called Web page archive run entity.
   */
  public function setName($name);

  /**
   * Gets the Web page archive run creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Web page archive run.
   */
  public function getCreatedTime();

  /**
   * Sets the Web page archive run creation timestamp.
   *
   * @param int $timestamp
   *   The Web page archive run creation timestamp.
   *
   * @return \Drupal\web_page_archive\Entity\WebPageArchiveRunInterface
   *   The called Web page archive run entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Web page archive run published status indicator.
   *
   * Unpublished Web page archive run are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Web page archive run is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Web page archive run.
   *
   * @param bool $published
   *   TRUE to set this Web page archive run to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\web_page_archive\Entity\WebPageArchiveRunInterface
   *   The called Web page archive run entity.
   */
  public function setPublished($published);

  /**
   * Gets the Web page archive run revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Web page archive run revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\web_page_archive\Entity\WebPageArchiveRunInterface
   *   The called Web page archive run entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Web page archive run revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Web page archive run revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\web_page_archive\Entity\WebPageArchiveRunInterface
   *   The called Web page archive run entity.
   */
  public function setRevisionUserId($uid);

}
