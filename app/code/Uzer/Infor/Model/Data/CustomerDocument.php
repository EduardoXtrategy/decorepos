<?php

namespace Uzer\Infor\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Infor\Api\Data\CustomerDocumentInterface;

class CustomerDocument extends DataObject implements CustomerDocumentInterface
{
    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * Getter for DocumentType.
     *
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->getData(self::DOCUMENT_TYPE);
    }

    /**
     * Setter for DocumentType.
     *
     * @param string|null $documentType
     *
     * @return void
     */
    public function setDocumentType(?string $documentType): void
    {
        $this->setData(self::DOCUMENT_TYPE, $documentType);
    }

    /**
     * Getter for EntityType.
     *
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * Setter for EntityType.
     *
     * @param string|null $entityType
     *
     * @return void
     */
    public function setEntityType(?string $entityType): void
    {
        $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * Getter for Filename.
     *
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->getData(self::FILENAME);
    }

    /**
     * Setter for Filename.
     *
     * @param string|null $filename
     *
     * @return void
     */
    public function setFilename(?string $filename): void
    {
        $this->setData(self::FILENAME, $filename);
    }

    /**
     * Getter for Base64.
     *
     * @return string|null
     * @return void
     */
    public function getBase64(): ?string
    {
        return $this->getData(self::BASE64);
    }


    /**
     * Setter for Base64.
     *
     * @param string|null $base64
     *
     * @return void
     */
    public function setBase64(?string $base64): void
    {
        $this->setData(self::BASE64, $base64);
    }

    /**
     * Getter for ACL.
     *
     * @return string|null
     */
    public function getAcl(): ?string
    {
        return $this->getData(self::ACL);
    }

    /**
     * Setter for ACL.
     *
     * @param string|null $acl
     *
     * @return void
     */
    public function setAcl(?string $acl): void
    {
        $this->setData(self::ACL, $acl);
    }

    /**
     * Getter for EntityName.
     *
     * @return string|null
     */
    public function getEntityName(): ?string
    {
        return $this->getData(self::ENTITY_NAME);
    }

    /**
     * Setter for EntityName.
     *
     * @param string|null $entityName
     *
     * @return void
     */
    public function setEntityName(?string $entityName): void
    {
        $this->setData(self::ENTITY_NAME, $entityName);
    }


    /**
     * Generate array for request
     *
     * @return array
     */
    public function compile(): array
    {
        return [
            "item" => [
                "attrs" => [
                    [
                        "name" => "Name",
                        "value" => $this->getName()
                    ],
                    [
                        "name" => "DocumentType",
                        "value" => $this->getDocumentType()
                    ],
                    [
                        "name" => "EntityType",
                        "value" => $this->getEntityType()
                    ],
                ],
                "resrs" => [
                    [
                        "filename" => $this->getFilename(),
                        "base64" => $this->getBase64()
                    ]
                ],
                "acl" => ["name" => $this->getAcl()],
                "entityName" => $this->getEntityName()
            ]
        ];
    }
}
