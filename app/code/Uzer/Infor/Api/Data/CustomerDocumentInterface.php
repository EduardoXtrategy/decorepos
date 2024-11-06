<?php

namespace Uzer\Infor\Api\Data;

interface CustomerDocumentInterface
{
    /**
     * String constants for property names
     */
    public const NAME = "Name";
    public const DOCUMENT_TYPE = "DocumentType";
    public const ENTITY_TYPE = "EntityType";
    public const FILENAME = "filename";
    public const BASE64 = "base64";
    public const ACL = "acl";
    public const ENTITY_NAME = "entityName";

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
     * Getter for DocumentType.
     *
     * @return string|null
     */
    public function getDocumentType(): ?string;

    /**
     * Setter for DocumentType.
     *
     * @param string|null $documentType
     *
     * @return void
     */
    public function setDocumentType(?string $documentType): void;

    /**
     * Getter for EntityType.
     *
     * @return string|null
     */
    public function getEntityType(): ?string;

    /**
     * Setter for EntityType.
     *
     * @param string|null $entityType
     *
     * @return void
     */
    public function setEntityType(?string $entityType): void;

    /**
     * Getter for Filename.
     *
     * @return string|null
     */
    public function getFilename(): ?string;

    /**
     * Setter for Filename.
     *
     * @param string|null $filename
     *
     * @return void
     */
    public function setFilename(?string $filename): void;

    /**
     * Getter for Base64.
     *
     * @return string|null
     */
    public function getBase64(): ?string;

    /**
     * Setter for Base64.
     *
     * @param string|null $base64
     *
     * @return void
     */
    public function setBase64(?string $base64): void;

    /**
     * Getter for ACL.
     *
     * @return string|null
     */
    public function getAcl(): ?string;

    /**
     * Setter for ACL.
     *
     * @param string|null $acl
     *
     * @return void
     */
    public function setAcl(?string $acl): void;

    /**
     * Getter for EntityName.
     *
     * @return string|null
     */
    public function getEntityName(): ?string;

    /**
     * Setter for EntityName.
     *
     * @param string|null $entityName
     *
     * @return void
     */
    public function setEntityName(?string $entityName): void;


    /**
     * Generate array for request
     *
     * @return array
     */
    public function compile(): array;

}
