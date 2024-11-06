<?php

declare(strict_types=1);

namespace Amasty\SeoToolkitLite\Api\Data;

interface RedirectInterface
{
    const TABLE_NAME = 'amasty_seotoolkit_redirect';
    const STORE_TABLE_NAME = 'amasty_seotoolkit_redirect_store';
    const REDIRECT_ID = 'redirect_id';
    const STATUS = 'status';
    const REQUEST_PATH = 'request_path';
    const TARGET_PATH = 'target_path';
    const REDIRECT_TYPE = 'redirect_type';
    const UNDEFINED_PAGE_ONLY = 'undefined_page_only';
    const PRIORITY = 'priority';
    const DESCRIPTION = 'description';
    const STORE_ID = 'store_id';

    /**
     * @return int
     */
    public function getRedirectId();

    /**
     * @param int $redirectId
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setRedirectId($redirectId);

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @param int $status
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setStatus($status);

    /**
     * @return string|null
     */
    public function getRequestPath();

    /**
     * @param string|null $requestPath
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setRequestPath($requestPath);

    /**
     * @return string|null
     */
    public function getTargetPath();

    /**
     * @param string|null $targetPath
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setTargetPath($targetPath);

    /**
     * @return int
     */
    public function getRedirectType();

    /**
     * @param int $redirectType
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setRedirectType($redirectType);

    /**
     * @return int
     */
    public function getUndefinedPageOnly();

    /**
     * @param int $undefinedPageOnly
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setUndefinedPageOnly($undefinedPageOnly);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setPriority($priority);

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @param string|null $description
     *
     * @return \Amasty\SeoToolkitLite\Api\Data\RedirectInterface
     */
    public function setDescription($description);
}
