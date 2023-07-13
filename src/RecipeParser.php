<?php

namespace MimoGraphix\CookLang;

use MimoGraphix\CookLang\Models\Cookware;
use MimoGraphix\CookLang\Models\Ingredience;
use MimoGraphix\CookLang\Models\Recipe;
use MimoGraphix\CookLang\Models\Step;
use MimoGraphix\CookLang\Models\Timer;

class RecipeParser
{
    public function parseFromString($string)
    {
        $steps = explode("\n\n", $string);

        $recipe = new Recipe();
        foreach ($steps as $key => $step) {
            if ($key === 0) {
                preg_match_all('/>>\s+([a-z ]+):\s+(.*)/', $step, $medatada);
                if (count($medatada[0]) > 0) {
                    foreach ($medatada[0] as $meta_key => $_tmp) {
                        $recipe->addMetadata($medatada[1][$meta_key], $medatada[2][$meta_key]);
                    }
                    continue;
                }
            }
            $recipe->addStep($this->parseStep($step));
        }

        return $recipe;
    }

    public function parseFromFile($file_path)
    {
        if (!file_exists($file_path)) {
            throw new \Exception('File[' . $file_path . '] not found!');
        }

        return $this->parseFromString(file_get_contents($file_path));
    }

    protected function parseStep(string $stepText)
    {
        $sentenceRegex = "\w\s,\.-\/%\(\)";

        $regex = "("
            . "@[\w\s-]+{[" . $sentenceRegex . "]*}"
            . "|@[\w\s-]+"
            . "|#[\w\s-]+{[" . $sentenceRegex . "]*}"
            . "|#[\w\s-]+"
            . "|~[\w\s-]*{[" . $sentenceRegex . "]*}"
            . "|\[-\s*[" . $sentenceRegex . "]+\s*-\]"
            . "|--\s+.*$"
            . "|[^@#~]+)";

        preg_match_all('/' . $regex . '/i', $stepText, $split);

        $step = new Step();
        foreach ($split[0] as $textPart){
            switch ($textPart[0]){
                case '@': // ingredience
                    $ingredience = Ingredience::parse($textPart);
                    $step->addTextPart($ingredience);
                    break;
                case '#': // cookware
                    $cookware = Cookware::parse($textPart);
                    $step->addTextPart($cookware);
                    break;
                case '~': // timer
                    $timer = Timer::parse($textPart);
                    $step->addTextPart($timer);
                    break;
                default:
                    $step->addTextPart($textPart);
            }
        }

        return $step;
    }
}