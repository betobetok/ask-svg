<?php

namespace Tests\AskSvgTests;

use ASK\Svg\BladeIconsServiceProvider;
use ASK\Svg\Factory;
use ASK\Svg\Generation\IconGenerator;
use ASK\Svg\IconsManifest;
use ASK\Svg\Svg;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use NumPHP\Core\NumArray;

class SvgTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }


    /** @test */
    public function i_can_use_a_json_file_to_overrreid_the_default_configuration()
    {
        $config = include __DIR__ . '/../../config/blade-icons.php';

        $sets = $config['sets'];

        $this->assertArrayNotHasKey('test', $sets);

        $newSets = app(Factory::class)->all();
        $this->assertArrayHasKey('default', $newSets);
        $this->assertArrayHasKey('test', $newSets);
        foreach ($newSets as $set) {
            $this->assertArrayHasKey('paths', $set);
            $this->assertArrayHasKey('prefix', $set);
        }
    }

    /** @test */
    public function i_can_get_a_Svg_Object_from_a_svg_file()
    {
        $svg = svg('camera');
        $this->assertTrue($svg instanceof Svg);
    }

    /** @test */
    public function the_Svg_Object_from_a_svg_file_had_all_elements()
    {
        $svg = svg('test-test');
        $countElements = $this->countContentRecursive($svg->getElements());
        $this->assertEquals(23, $countElements);
    }

    /** @test */
    public function the_elements_of_the_Svg_Object_are_in_the_same_order_as_in_the_file()
    {
        $svg = svg('test-test');
        $toCompare = [
            "Element_0" => [
                "Element_1" => [
                    "Element_2" => "stop",
                    "Element_3" => "stop",
                ],
                "Element_4" => [
                    "Element_5" => "feFlood",
                    "Element_6" => "feColorMatrix",
                    "Element_7" => "feOffset",
                    "Element_8" => "feGaussianBlur",
                    "Element_9" => "feComposite",
                    "Element_10" => "feColorMatrix",
                    "Element_11" => "feBlend",
                    "Element_12" => "feBlend",
                ]
            ],
            "Element_13" => "ellipse",
            "Element_14" => "text",
            "Element_15" => "rect",
            "Element_16" => [
                "Element_17" => "rect",
                "Element_18" => "circle",
                "Element_19" => "polyline",
                "Element_20" => "line",
                "Element_21" => [
                    "Element_22" => "polygon"
                ]
            ]
        ];

        $elements = $this->getContentElementsRecursive($svg->getElements());
        $this->assertEquals($toCompare, $elements);
    }

    /** @test */
    public function the_Svg_Object_from_a_svg_file_had_all_the_svg_attributes()
    {
        $svg = svg('test-testAttributes');
        $attributes = $svg->attributes();
        $toCompare =  [
            "id" => "svg6",
            "fill" => "none",
            "viewBox" => "0 0 24 24",
            "stroke" => "currentColor",
            "version" => "1.1",
            "xmlns" => "http://www.w3.org/2000/svg",
            "xmlns:svg" => "http://www.w3.org/2000/svg",
        ];
        $this->assertEquals($toCompare, $attributes);

        $polylineAtt = $svg->g[0]->polyline[0]->attributes();
        $toCompare =  [
            "id" => "Element_19",
            "stroke" => "red",
            "stroke-width" => "4",
            "fill" => "none",
            "style" => "opacity:1",
            'points' => [
                0 => new NumArray(['x' => '50', 'y' => '150']),
                1 => new NumArray(['x' => '50', 'y' => '200']),
                2 => new NumArray(['x' => '200', 'y' => '200']),
                3 => new NumArray(['x' => '200', 'y' => '100']),
            ]
        ];

        $this->assertEquals($toCompare, $polylineAtt);

        $rectAtt = $svg->g[0]->rect[0]->attributes();
        $toCompare =  [
            "id" => "Element_17",
            "fill" => "lime",
            "stroke-width" => "4",
            "stroke" => "pink",
            "x" => "25",
            "y" => "25",
            "width" => "200",
            "height" => "200",
            "rx" => "0",
            "ry" => "0"
        ];

        $this->assertEquals($toCompare, $rectAtt);
    }

    private function countContentRecursive(array $elements): int
    {
        $count = count($elements);
        foreach ($elements as $element) {
            if (!empty($element->getElements())) {
                $count += $this->countContentRecursive($element->getElements());
            }
        }
        return $count;
    }

    private function getContentElementsRecursive(array $elements): array
    {
        $elementsRet = [];
        foreach ($elements as $element) {

            if (!empty($element->getElements())) {
                $elementsRet[$element->id()] = $this->getContentElementsRecursive($element->getElements());
            } else {
                $elementsRet[$element->id()] = $element->name();
            }
        }
        return $elementsRet;
    }
}
