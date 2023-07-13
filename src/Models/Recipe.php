<?php

namespace MimoGraphix\CookLang\Models;

class Recipe implements \JsonSerializable
{
    /**
     * @var <string,string>[]
     */
    protected array $metadata = [];

    /**
     * @var Step[]
     */
    protected array $steps = [];

    /**
     * @return Ingredience[]
     */
    public function getAllIngrediences(): array
    {
        $ingrediences = [];

        foreach ($this->steps as $step) {
            $ingrediences = [...$ingrediences, ...$step->getIngrediences()];
        }

        return $ingrediences;
    }

    /**
     * @return Cookware[]
     */
    public function getAllCookware(): array
    {
        $cookware = [];

        foreach ($this->steps as $step) {
            foreach ($step->getCookware() as $_cookware) {
                if(!isset($cookware[$_cookware->getName()])){
                    $cookware[$_cookware->getName()] = $_cookware;
                    continue;
                }
                $cookware[$_cookware->getName()] = $_cookware->merge($cookware[$_cookware->getName()]);
            }
        }

        return $cookware;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function getMetadata(): array
    {
        return $this->steps;
    }

    public function addMetadata(string $key, string $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }

    public function addStep(Step $step): self
    {
        $this->steps[] = $step;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "metadata" => $this->metadata,
            "steps" => $this->steps,
        ];
    }
}