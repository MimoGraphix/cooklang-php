<?php

namespace MimoGraphix\CookLang\Models;

class Comment implements \JsonSerializable
{
    protected string $text;

    protected bool $block = false;

    public static function parse(string $textPart)
    {
        $comment = new self();

        $isComment = substr($textPart, 0, 2);
        if ($isComment === "---") {
            $comment->block = false;
            $comment->text = trim(substr($textPart, 2));
        } else {
            $comment->block = true;
            $comment->text = trim(substr($textPart, 2, -2));
        }

        return $comment;
    }

    public function __toString(): string
    {
        if ($this->block) {
            return "[- " . $this->text . " -]";
        }

        return "-- " . $this->text;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "type" => 'comment',
            "text" => $this->text,
            "block" => $this->block,
        ];
    }
}