<?php

namespace Uzer\Catalogs\Api\Data;

interface CatalogInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const IMAGE = "image";
    const NAME = "name";
    const LINK = "link";
    const ACTIVE = "active";
    const DESCRIPTION = "description";
    const IMAGE_NAME = 'image_name';
    const STORE_ID = 'store_id';

    /**
     * Getter for Image.
     *
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * Setter for Image.
     *
     * @param string|null $image
     *
     * @return void
     */
    public function setImage(?string $image): void;

    /**
     * Getter for Image Name.
     *
     * @return string|null
     */
    public function getImageName(): ?string;

    /**
     * Setter for Image.
     *
     * @param string|null $imageName
     *
     * @return void
     */
    public function setImageName(?string $imageName): void;

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * Getter for Link.
     *
     * @return string|null
     */
    public function getLink(): ?string;

    /**
     * Setter for Link.
     *
     * @param string|null $link
     *
     * @return void
     */
    public function setLink(?string $link): void;

    /**
     * Getter for Active.
     *
     * @return bool|null
     */
    public function getActive(): ?bool;

    /**
     * Setter for Active.
     *
     * @param bool|null $active
     *
     * @return void
     */
    public function setActive(?bool $active): void;

    /**
     * Getter for Description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Setter for Description.
     *
     * @param string|null $description
     *
     * @return void
     */
    public function setDescription(?string $description): void;


}
