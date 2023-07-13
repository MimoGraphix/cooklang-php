<?php

use MimoGraphix\CookLang\RecipeParser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @dataProvider cook_files
     */
    public function test_parse_demo($file, $count_ingrediences, $count_cookware, $count_steps)
    {
        $parser = new RecipeParser();
        $recipe = $parser->parseFromFile($file);

        $this->assertNotNull($recipe);

        $this->assertJson(json_encode($recipe));

        $this->assertCount($count_ingrediences, $recipe->getAllIngrediences());
        $this->assertCount($count_cookware, $recipe->getAllCookware());
        $this->assertCount($count_steps, $recipe->getSteps());
    }

    public function cook_files()
    {
        return [
            ['tests/data/recipe_demo.cook', 5, 1, 2],
            ['tests/data/recipe_mash.cook', 7, 5, 1],
            ['tests/data/kabob.cook', 11, 2, 18],
        ];
    }
}