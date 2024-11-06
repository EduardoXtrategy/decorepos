<?php

declare(strict_types=1);

namespace Uzer\PriceDecimal\Model;

use Magento\Framework\CurrencyInterface;
use Magento\Framework\Currency as MagentoCurrency;
use Uzer\PriceDecimal\Model\ConfigInterface;
use Zend_Currency_Exception;
use Zend_Locale;
use Zend_Locale_Data;
use Zend_Locale_Format;

/** @method getPricePrecision */
class Currency extends MagentoCurrency implements CurrencyInterface
{

    use PricePrecisionConfigTrait;

    /**
     * @var \Uzer\PriceDecimal\Model\ConfigInterface
     */
    public $moduleConfig;

    /**
     * Currency constructor.
     *
     * @param \Magento\Framework\App\CacheInterface $appCache
     * @param \Uzer\PriceDecimal\Model\ConfigInterface $moduleConfig
     * @param null $options
     * @param null $locale
     */
    public function __construct(
        \Magento\Framework\App\CacheInterface $appCache,
        ConfigInterface                       $moduleConfig,
                                              $options = null,
                                              $locale = null
    )
    {
        $this->moduleConfig = $moduleConfig;
        parent::__construct($appCache, $options, $locale);
    }

    /**
     * @throws \Zend_Currency_Exception
     * @throws \Zend_Locale_Exception
     */
    public function toCurrencyFormatted($value = null, array $options = array()): string
    {
        if ($value === null) {
            if (is_array($options) && isset($options['value'])) {
                $value = $options['value'];
            } else {
                $value = $this->_options['value'];
            }
        }

        if (is_array($value)) {
            $options += $value;
            if (isset($options['value'])) {
                $value = $options['value'];
            }
        }

        // Validate the passed number
        if (!(isset($value)) or (is_numeric($value) === false)) {
            #require_once 'Zend/Currency/Exception.php';
            throw new Zend_Currency_Exception("Value '$value' has to be numeric");
        }

        if (isset($options['currency'])) {
            if (!isset($options['locale'])) {
                $options['locale'] = $this->_options['locale'];
            }

            $options['currency'] = self::getShortName($options['currency'], $options['locale']);
            $options['name'] = self::getName($options['currency'], $options['locale']);
            $options['symbol'] = self::getSymbol($options['currency'], $options['locale']);
        }

        $options = $this->_checkOptions($options) + $this->_options;
        // Format the number
        $format = $options['format'];
        $locale = $options['locale'];
        if (empty($format)) {
            $format = Zend_Locale_Data::getContent($locale, 'currencynumber');
        } else if (Zend_Locale::isLocale($format, true, false)) {
            $locale = $format;
            $format = Zend_Locale_Data::getContent($format, 'currencynumber');
        }
        $original = $value;
        $value = Zend_Locale_Format::toNumber($value, array('locale' => $locale,
            'number_format' => $format,
            'precision' => $options['precision']));

        if ($options['position'] !== self::STANDARD) {
            $value = str_replace('¤', '', $value);
            $space = '';
            if (iconv_strpos($value, ' ') !== false) {
                $value = str_replace(' ', '', $value);
                $space = ' ';
            }

            if ($options['position'] == self::LEFT) {
                $value = '¤' . $space . $value;
            } else {
                $value = $value . $space . '¤';
            }
        }

        // Localize the number digits
        if (empty($options['script']) === false) {
            $value = Zend_Locale_Format::convertNumerals($value, 'Latn', $options['script']);
        }

        // Get the sign to be placed next to the number
        if (is_numeric($options['display']) === false) {
            $sign = $options['display'];
        } else {
            switch ($options['display']) {
                case self::USE_SYMBOL:
                    $sign = $this->_extractPattern($options['symbol'], $original);
                    break;

                case self::USE_SHORTNAME:
                    $sign = $options['currency'];
                    break;

                case self::USE_NAME:
                    $sign = $options['name'];
                    break;

                default:
                    $sign = '';
                    $value = str_replace(' ', '', $value);
                    break;
            }
        }
        $value = str_replace('¤', $sign, $value);
        return $value;
    }

    private function _extractPattern($pattern, $value)
    {
        if (strpos($pattern, '|') === false) {
            return $pattern;
        }

        $patterns = explode('|', $pattern);
        $token = $pattern;
        $value = trim(str_replace('¤', '', $value));
        krsort($patterns);
        foreach ($patterns as $content) {
            if (strpos($content, '<') !== false) {
                $check = iconv_substr($content, 0, iconv_strpos($content, '<'));
                $token = iconv_substr($content, iconv_strpos($content, '<') + 1);
                if ($check < $value) {
                    return $token;
                }
            } else {
                $check = iconv_substr($content, 0, iconv_strpos($content, '≤'));
                $token = iconv_substr($content, iconv_strpos($content, '≤') + 1);
                if ($check <= $value) {
                    return $token;
                }
            }

        }

        return $token;
    }
}
