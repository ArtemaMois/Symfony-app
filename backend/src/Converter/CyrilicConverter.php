<?php

namespace App\Converter;

class CyrilicConverter
{

    public static function replaceCyrilic(string $text)
    {
        $symbols = array_merge(self::getLowerSymbols(), self::getUpperSymbols());
        return strtr($text, $symbols);
    }

    private static function getLowerSymbols()
    {
        return [
            'ґ' => 'g',
            'ё' => 'e',
            'є' => 'e',
            'ї' => 'i',
            'і' => 'i',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'i',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ы' => 'y',
            'э' => 'e',
            'ю' => 'u',
            'я' => 'ya',
            'é' => 'e',
            '&' => 'and',
            'ь' => '',
            'ъ' => '',
        ];
    }

    private static function getUpperSymbols()
    {
        return [
            'ґ' => 'G',
            'ё' => 'E',
            'є' => 'E',
            'ї' => 'I',
            'і' => 'I',
            'Ф' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'I',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Ч' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ы' => 'Y',
            'Э' => 'E',
            'Ю' => 'U',
            'Я' => 'Ya',
            'é' => 'E',
            '&' => 'and',
            'Ь' => '',
            'Ъ' => '',
        ];
    }
}
