define([
    'jquery',
    'mage/utils/wrapper',
    'Amasty_GdprFrontendUi/js/model/cookie'
], function ($, wrapper, cookies) {
    'use strict';

    return function (idsStorage) {
        idsStorage.initLocalStorage = wrapper.wrapSuper(idsStorage.initLocalStorage, function () {
            var isCookieAllowed = true,
                cookieSetItem = window.cookieStorage.setItem,
                emptyFunction = function () {};

            isCookieAllowed = cookies.isCookieAllowed(this.namespace);

            if (isCookieAllowed || !window.isGdprCookieEnabled) {
                return this._super();
            }

            window.cookieStorage.setItem = emptyFunction;

            this._super();

            window.cookieStorage.setItem = cookieSetItem;

            return this;
        });

        return idsStorage;
    };
});
