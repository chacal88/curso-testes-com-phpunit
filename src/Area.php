<?php

declare(strict_types=1);

namespace Chacal;


/**
 * Created by PhpStorm.
 * User: chacal
 * Date: 23/05/17
 * Time: 16:47
 */
class Area
{
    public function getArea(float $valor1, float $valor2): float
    {
        return $valor1 * $valor2;
    }

}