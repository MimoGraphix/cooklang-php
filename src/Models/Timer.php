<?php

namespace MimoGraphix\CookLang\Models;

use JsonSerializable;
use MimoGraphix\CookLang\Helpers\Inflect;

class Timer implements JsonSerializable
{
    protected ?string $name;
    protected Quantity $quantity;

    /**
     * @param string|null $text
     * @param string $time
     * @param string $unit
     */
    public function __construct(?string $name, Quantity $quantity)
    {
        $this->name = Inflect::singularize($name);
        $this->quantity = $quantity;
    }

    public static function parse(string $textPart)
    {
        preg_match('/~([\w\s]+){([\w ,\/]*)%?([\w]*)}|~{([\w ,\/]*)%?([\w]*)}/i', $textPart, $output_array);

        if (count($output_array) == 6) {
            return new self("", new Quantity($output_array[4], $output_array[5]));
        }
        return new self($output_array[1], new Quantity($output_array[2], $output_array[3]));
    }


    public function jsonSerialize(): mixed
    {
        return [
            "type" => 'timer',
            "name" => $this->name,
            ...$this->quantity->jsonSerialize()
        ];
    }
}