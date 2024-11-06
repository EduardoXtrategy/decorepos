<?php

namespace Uzer\Jobs\Api\Data;

use Magento\Framework\Model\AbstractModel;

interface UzerJobInterface
{
    const NAME = "name";
    const LOCATION = "location";
    const DESCRIPTION = "description";
    const STATUS = "status";
    const STORE_ID = 'store_id';

    /**
     * Retrieve entity id
     *
     * @return mixed
     */
    public function getEntityId();

    /**
     * Set entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

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
     * Getter for Location.
     *
     * @return string|null
     */
    public function getLocation(): ?string;

    /**
     * Setter for Location.
     *
     * @param string|null $location
     *
     * @return void
     */
    public function setLocation(?string $location): void;

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

    /**
     * Getter for Status.
     *
     * @return bool|null
     */
    public function getStatus(): ?bool;

    /**
     * Setter for Status.
     *
     * @param bool|null $status
     *
     * @return void
     */
    public function setStatus(?bool $status): void;
}
