<?php

namespace MimoGraphix\CookLang\Models;

use JsonSerializable;
use MimoGraphix\CookLang\Helpers\Inflect;

class Cookware implements JsonSerializable
{
    protected string $name;

    protected Quantity $quantity; // TODO

    /**
     * @param string $name
     * @param Quantity $quantity
     */
    public function __construct(string $name, Quantity $quantity)
    {
        $this->name = Inflect::singularize($name);
        $this->quantity = $quantity;
    }

    public static function parse(string $textPart)
    {
        preg_match('/#([\w\s-]+){([\w ,\/]*)}|#([\w]+)/i', $textPart, $output_array);

        return new self((!empty($output_array[1]) ? $output_array[1] : $output_array[3]), new Quantity($output_array[2] ?? ""));
    }

    public function jsonSerialize(): mixed
    {
        return [
            "type" => "cookware",
            "name" => $this->name,
            ...$this->quantity->jsonSerialize(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Quantity
     */
    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function merge(self $item)
    {
        $this->quantity->merge($item->quantity);
        return $this;
    }
}