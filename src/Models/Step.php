<?php

namespace MimoGraphix\CookLang\Models;

use JsonSerializable;

class Step implements JsonSerializable
{
    protected array $textParts = [];

    /**
     * @return Ingredience[]
     */
    public function getIngrediences()
    {
        return array_filter($this->textParts, function ($value) {
            return $value instanceof Ingredience;
        });
    }

    /**
     * @return Cookware[]
     */
    public function getCookware()
    {
        return array_filter($this->textParts, function ($value) {
            return $value instanceof Cookware;
        });
    }

    public function getText()
    {
        return implode("", array_map(function ($part) {
            return $part;
        }, $this->textParts));
    }

    public function jsonSerialize(): mixed
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return [
                    "type" => "text",
                    "value" => $value,
                ];
            }

            return $value->jsonSerialize();
        }, $this->textParts);
    }

    public function addTextPart(mixed $textPart)
    {
        if (is_string($textPart)) {
            $isComment = substr($textPart, 0, 2);
            $isCommentEnd = substr($textPart, -2, 2);
            if ($isComment === "--" || ($isComment === "[-" && $isCommentEnd === "-]")) {
                $this->textParts[] = Comment::parse($textPart);
                return $this;
            }
        }

        $this->textParts[] = $textPart;
        return $this;
    }
}