<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Out of stock Notifications GraphQL for Magento 2 (System)
 */

namespace Amasty\OsnGraphql\Model\Resolver;

use Amasty\OsnGraphql\Model\Di\Wrapper;
use Amasty\Xnotif\Model\Messages\ResultStatus;
use Amasty\Xnotif\Model\Product\GdprProcessor;
use Amasty\Xnotif\Model\Product\PriceSubscribe as SubscribeToPriceNotification;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class PriceSubscribe implements ResolverInterface
{
    /**
     * @var SubscribeToPriceNotification
     */
    private $priceSubscribe;

    /**
     * @var GdprProcessor
     */
    private $gdprProcessor;

    /**
     * @var \Magento\Framework\GraphQl\Query\Uid
     */
    private $uidEncoder;

    public function __construct(
        SubscribeToPriceNotification $priceSubscribe,
        GdprProcessor $gdprProcessor,
        Wrapper $uidEncoder
    ) {
        $this->priceSubscribe = $priceSubscribe;
        $this->gdprProcessor = $gdprProcessor;
        $this->uidEncoder = $uidEncoder;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        try {
            $email = $args['input']['email'] ?? null;
            $data['gdrp'] = $args['input']['gdpr_agreement'] ?? false;
            $this->gdprProcessor->validateGDRP($data);
            $status = $this->priceSubscribe->execute($this->getProductId($args['input']), $email);
            $message = ResultStatus::MESSAGES[$status];
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getRawMessage(), $e->getParameters()));
        }

        return ['response_message' => __($message)];
    }

    private function getProductId(array $input): ?int
    {
        $uid = $input['product_uid'] ?? null;
        $id = $uid ? (int) $this->uidEncoder->decode($uid) : ($input['product_id'] ?? null);
        if (!$id) {
            throw new GraphQlInputException(__('Please specify product_id or product_uid'));
        }

        return $id;
    }
}
