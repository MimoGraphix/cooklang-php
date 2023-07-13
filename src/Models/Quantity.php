<?php

namespace MimoGraphix\CookLang\Models;

use JsonSerializable;

class Quantity implements JsonSerializable
{
    protected string $quantity;

    protected ?string $units;

    /**
     * @param string $quantity
     * @param string $units
     */
    public function __construct(string $quantity, ?string $units = null)
    {
        $this->quantity = $quantity;
        $this->units = $units;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "quantity" => $this->quantity,
            "units" => $this->units,
        ];
    }

    public function merge(Quantity $quantity)
    {
        if(is_float($this->quantity) && is_float($quantity->quantity)){
            $this->quantity += $quantity->quantity;
        }else{
            $this->quantity .= $quantity->quantity;
        }
        return $this;
    }
}