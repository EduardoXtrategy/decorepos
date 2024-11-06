<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\LocalizedException;
use Uzer\Infor\Api\Data\CustomerDocumentInterface;
use Uzer\Infor\Api\Data\CustomerDocumentInterfaceFactory;

class BuilderDocument
{

    protected CustomerDocumentInterfaceFactory $customerDocumentInterfaceFactory;

    public function __construct(
        CustomerDocumentInterfaceFactory $customerDocumentInterfaceFactory
    )
    {
        $this->customerDocumentInterfaceFactory = $customerDocumentInterfaceFactory;
    }

    /**
     * @param Customer $customer
     * @param string $path
     * @param string $type
     * @param string $fileName
     * @return array
     * @throws LocalizedException
     */
    public function build(
        Customer $customer,
        string   $path,
        string   $type,
        string   $fileName
    ): array
    {

        /** @var CustomerDocumentInterface $document */
        $document = $this->customerDocumentInterfaceFactory->create();
        $document->setName($customer->getName());
        $document->setDocumentType($type);
        $document->setEntityType('DW_Customer');
        $document->setFilename($fileName);
        $document->setBase64(base64_encode(file_get_contents($path)));
        $document->setAcl('Public');
        $document->setEntityName('DW_Customer');
        return $document->compile();
    }

}
