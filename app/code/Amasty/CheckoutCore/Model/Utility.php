<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package One Step Checkout Core for Magento 2
 */

namespace Amasty\CheckoutCore\Model;

class Utility
{
    /**
     * The method inserts a new array before some key
     *
     * @param array $origin
     * @param string $wantedKey
     * @param array $insert
     *
     * @return array
     */
    public function arrayInsertBeforeKey(
        $origin = [],
        $wantedKey = '',
        $insert = []
    ) {
        $availableKeys = array_keys($origin);
        $position = array_search($wantedKey, $availableKeys);
        if ($position === false) {
            $position = count($origin);
        }

        $derivative = array_merge(
            array_slice($origin, 0, $position),
            $insert,
            array_slice($origin, $position)
        );

        return $derivative;
    }
}
