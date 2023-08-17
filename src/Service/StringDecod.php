<?php

namespace App\Service;

class StringDecod
{
    public function explodAspasCupomDb(array $code): string
    {
        $str = serialize($code);
        $pieces = explode('"', $str);

        return $pieces[3];
    }

    public function explodBarValue(string $code): string
    {
        $pieces = explode('/', $code);

        return $pieces[0];
    }

    public function explodBarAmount(string $code): string
    {
        $pieces = explode('/', $code);

        return $pieces[1];
    }

    public function explodBarValueInt(string $code): float
    {
        $pieces = explode('/', $code);
        $piecesFloat = floatval($pieces[2]);

        return $piecesFloat;
    }
}