<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('getAvatar')) {
    function getAvatar($user_id)
    {
        $user = \App\User::where('id', $user_id)->first();
        if ($user->avatar == null) {
            return asset('img/avatar-default.png');
        }
        return \Storage::url($user->avatar);
    }
}

if (!function_exists('getDob')) {
    function getDob($user_id)
    {
        $user = \App\User::where('id', $user_id)->first();
        return ($user->dob) ? $user->dob->format('Y-m-d') : null;
    }
}

if (!function_exists('brl')) {
    function brl($value, $flag = true)
    {
        $valor = number_format($value, 2, ',', '.');
        if ($flag) {
            return "R$ " . $valor;
        }
        return $valor;
    }
}

if (!function_exists('brlToNumeric')) {
    function brlToNumeric($value)
    {
        return str_replace([' ', 'R$', '.', ','], ['', '', '', '.'], $value);
    }
}

if (!function_exists('menuPath')) {
    function menuPath($path = [], $type = "active menu-open")
    {
        $routeNames = (array)$path;
        foreach ($routeNames as $routeName) {
            if (Route::is($routeName)) {
                return ' ' . $type;
            }
        }
        return '';
    }
}

if (!function_exists('numberIntegerToRoman')) {
    function numberIntegerToRoman($num, $debug = false)
    {
        $n = intval($num);
        $nRoman = '';

        $default = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        );

        foreach ($default as $roman => $number) {
            $matches = intval($n / $number);
            $nRoman .= str_repeat($roman, $matches);
            $n = $n % $number;
        }
        return $nRoman;
    }
}

if (!function_exists('menuPath')) {
    function menuPath($path = [], $type = "active menu-open")
    {
        $routeNames = (array)$path;
        foreach ($routeNames as $routeName) {
            if (Route::is($routeName) || Route::currentRouteName() == $routeName) {
                return ' ' . $type;
            }
        }
        return '';
    }
}

if (!function_exists('maskPhone')) {
    function maskPhone($phone)
    {
        $number = preg_replace('/\D/', '', $phone);
        $length = strlen($number);
        if ($length >= 10) {
            $block = ($length == 11) ? 5 : 4;
            $ddd = substr($number, 0, 2);
            $firstBlock = substr($number, 2, $block);
            $secondBlock = substr($number, -4);
            $phone = "({$ddd}) {$firstBlock}-{$secondBlock}";
        }
        return $phone;
    }
}

if (!function_exists('maskDocument')) {
    function maskDocument($str)
    {
        $str = (string)preg_replace('/\D/', '', $str);
        $maskared = "";
        $k = 0;
        // if cpf have 10 digitis
        if (strlen($str) <= 10) {
            $str = str_pad($str, 11, 0, STR_PAD_RIGHT);
        }

        $mask = (strlen($str) > 11) ? '##.###.###/####-##' : '###.###.###-##';
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] === '#') {
                if (isset($str[$k]))
                    $maskared .= $str[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}

if (!function_exists('loadSectors')) {
    function loadSectors()
    {
        $data = file_get_contents('setores.json');
        $data = json_decode($data, true);
        return $data;
    }
}
