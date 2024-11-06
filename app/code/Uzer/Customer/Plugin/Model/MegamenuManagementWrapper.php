<?php
namespace Uzer\Customer\Plugin\Model;

use Magedelight\Megamenu\Model\MegamenuManagement;
use Magedelight\Megamenu\Model\MenuItems;
use Magedelight\Megamenu\Model\ResourceModel\MenuItems\Collection;
use Magento\Customer\Model\Session;
use Uzer\Core\Logger\Logger;

class MegamenuManagementWrapper
{

    protected Logger $logger;

    protected Session $session;

    /**
     *
     * @param Logger $logger
     * @param Session $session
     */
    public function __construct(Logger $logger, Session $session)
    {
        $this->logger = $logger;
        $this->session = $session;
    }

    /**
     *
     * @param MegamenuManagement $megamenuManagement
     * @param Collection $result
     * @return Collection
     */
    public function afterLoadMenuItems(MegamenuManagement $megamenuManagement, Collection $result): Collection
    {
        if (! $this->session->isLoggedIn()) {
            /*
             * $result->addFieldToFilter('item_id', array(
             * 'nin' => [
             * 481,
             * 490
             * ]
             * ));
             */
//            $result->addFieldToFilter('item_name', array(
//                'nlike' => '%custom%'
//            ));
        }
        return $result;
    }
}
