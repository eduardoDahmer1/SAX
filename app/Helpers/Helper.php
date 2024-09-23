<?php

namespace App\Helpers;

use App\Models\Language;
use App\Models\AdminLanguage;
use App\Models\Product;
use Image;
use File;

class Helper
{
    public static function dir_is_empty($dirname)
    {
        if (!is_dir($dirname)) {
            return false;
        }
        foreach (scandir($dirname) as $file) {
            if (!in_array($file, array('.','..'))) {
                return false;
            }
        }
        return true;
    }

    public static function generateProductThumbnailsFtp($ftp_folder, $ref_code_int)
    {

    }

    /**
     * Remove accents and other characters from a string.
     * Replace special chars with nothing and spaces with underscore.
     * @param string $str
     * @return string
     */
    public static function strNormalize(string $string): string
    {
        $string = mb_ereg_replace("[áàâãä]", "a", $string);
        $string = mb_ereg_replace("[ÁÀÂÃÄ]", "A", $string);
        $string = mb_ereg_replace("[éèê]", "e", $string);
        $string = mb_ereg_replace("[ÉÈÊ]", "E", $string);
        $string = mb_ereg_replace("[íì]", "i", $string);
        $string = mb_ereg_replace("[ÍÌ]", "I", $string);
        $string = mb_ereg_replace("[óòôõö]", "o", $string);
        $string = mb_ereg_replace("[ÓÒÔÕÖ]", "O", $string);
        $string = mb_ereg_replace("[úùü]", "u", $string);
        $string = mb_ereg_replace("[ÚÙÜ]", "U", $string);
        $string = mb_ereg_replace("ç", "c", $string);
        $string = mb_ereg_replace("Ç", "C", $string);
        $string = mb_ereg_replace("[\]\[><}{)(:;,!?*%~^`&#@]", "", $string);
        $string = mb_ereg_replace(" ", "_", $string);
        return $string;
    }

    /**
     * Output variations of a locale string to use in setlocale() native method.
     * The setlocale() is server dependent. The locales must be installed in the
     * server and each server can have different string formats. So with this
     * helper you can just pass the locale (Eg. pt-br) and every possible
     * string combination will be send to the setlocale() method.
     *
     * @param string $locale
     * @return array
     */
    public static function strLocaleVariations(string $locale): array
    {
        $prefix = substr($locale, 0, 2);
        $suffix = strstr($locale, '-');
        $utf8 = ".utf8";

        $variations = [
            "{$locale}",
            "{$locale}".$utf8,
            "{$prefix}".strtoupper($suffix),
            "{$prefix}".strtoupper($suffix).$utf8,
            "{$prefix}".strtoupper($suffix).strtoupper($utf8),
            str_replace('-', '_', "{$locale}"),
            str_replace('-', '_', "{$locale}".$utf8),
            str_replace('-', '_', "{$prefix}".strtoupper($suffix)),
            str_replace('-', '_', "{$prefix}".strtoupper($suffix).$utf8),
            str_replace('-', '_', "{$prefix}".strtoupper($suffix).strtoupper($utf8))
        ];

        return $variations;
    }

    /**
     * Convert comma or dot numeric string into float.
     *
     * @param string $num
     * @return float
     */
    public static function toFloat($num)
    {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }
        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
                preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
        );
    }
}
