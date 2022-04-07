<?php

namespace Tests\AskSvgTests;


use ASK\Svg\Factory;
use ASK\Svg\Shapes\Circle;
use ASK\Svg\Shapes\Ellipse;
use ASK\Svg\Shapes\Line;
use ASK\Svg\Shapes\Path;
use ASK\Svg\Shapes\Polygon;
use ASK\Svg\Shapes\Polyline;
use ASK\Svg\Shapes\Rect;
use ASK\Svg\Svg;
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
            'points' => '50,150 50,200 200,200 200,100'
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

    /** @test */
    public function i_can_creat_a_new_svg_object_and_add_a_new_element()
    {
        $svg = new Svg('ask-svg');
        $this->assertTrue($svg instanceof Svg);
        $expected = <<<'HTML'
            <svg id="ask-svg" ></svg>
            HTML;
        $this->assertEquals($expected, $svg->toHtml());

        $svg->addAnElement(new Path(['d' => 'M0 0 c 2 3 4 1 2 3 z']));
        $expected = <<<'HTML'
            <svg id="ask-svg" ><path d="M 0 0 c 2 3 4 1 2 3 z " /></svg>
            HTML;

        $this->assertEquals($expected, $svg->toHtml());
    }

    /** @test */
    public function i_can_add_attributes_to_the_svg_element_and_their_elements()
    {
        $svg = new Svg('ask-svg');
        $svg->setAttribute('style', 'stroke:5px'); // add attribute calling the explicite methode for that
        $svg->viewBox('0 0 1 1'); // add attribute calling a methode whit the name of attribute
        $expected = <<<'HTML'
            <svg id="ask-svg" style="stroke:5px" viewBox="0 0 1 1" ></svg>
            HTML;
        $this->assertEquals($expected, $svg->toHtml());

        /** add attribute in the constructor a methode */
        $svg->addAnElement(new Path([
            'd'     => 'M0 0 c 2-3 4 1 2 3 z',
            'style' => 'stroke:2',
            'class' => 'one'
        ]));
        $expected = <<<'HTML'
            <svg id="ask-svg" style="stroke:5px" viewBox="0 0 1 1" ><path style="stroke:2" class="one" d="M 0 0 c 2 -3 4 1 2 3 z " /></svg>
            HTML;
        $this->assertEquals($expected, $svg->toHtml());

        $svg->path[0]->fill('transparent');
        $expected = <<<'HTML'
            <svg id="ask-svg" style="stroke:5px" viewBox="0 0 1 1" ><path style="stroke:2" class="one" fill="transparent" d="M 0 0 c 2 -3 4 1 2 3 z " /></svg>
            HTML;
        $this->assertEquals($expected, $svg->toHtml());
    }

    /** @test */
    public function i_can_create_basic_shapes_in_a_svg()
    {
        $svg = new Svg('svg-with-elements');
        //Add a Path
        $svg->addAnElement(new Path([
            'd'         => 'M 10 10 C 20 20, 40 20, 50 10',
            'stroke'    => 'black',
            'fill'      => 'transparent'
        ]));

        //Add a Path
        $svg->addAnElement(new Circle([
            'r' => '10',
            'cx' => '25',
            'cy' => '50'
        ]));

        //Add a Rect
        $svg->addAnElement(new Rect([
            'x'             => '50',
            'y'             => '50',
            'width'         => '30',
            'height'        => '30',
            'stroke'        => 'black',
            'fill'          => 'transparent',
            'stroke-width'  => '5'
        ]));

        //Add a Line
        $svg->addAnElement(new Line([
            'x1'            => '50',
            'x2'            => '50',
            'y1'            => '75',
            'y2'            => '75',
            'stroke'        => 'green',
            'fill'          => 'transparent',
            'stroke-width'  => '5'
        ]));

        //Add a Polyline
        $svg->addAnElement(new Polyline([
            'points'        => '60 110 65 120 70 115 75 130 80 125 85 140 90 135 95 150 100 145',
            'stroke'        => 'green',
            'fill'          => 'transparent',
            'stroke-width'  => '5'
        ]));

        //Add a Polygon
        $svg->addAnElement(new Polygon([
            'points'        => '50 160 55 180 70 180 60 190 65 205 50 195 35 205 40 190 30 180 45 180',
            'stroke'        => 'blue',
            'fill'          => 'transparent',
            'stroke-width'  => '5'
        ]));

        //Add a Ellipse
        $svg->addAnElement(new Ellipse([
            'rx'            => '10',
            'ry'            => '30',
            'cx'            => '25',
            'cy'            => '50',
            'stroke'        => 'red',
            'fill'          => 'blue',
            'stroke-width'  => '2'
        ]));
        file_put_contents(__DIR__ . '/laravel/resources/test/test3.svg', $svg->toHtml());
        $expected = <<<'HTML'
            <svg id="svg-with-elements" ><path stroke="black" fill="transparent" d="M 10 10 C 20 20 40 20 50 10 " /><circle r="10" cx="25" cy="50"/><rect x="50" y="50" width="30" height="30" stroke="black" fill="transparent" stroke-width="5"/><line x1="50" x2="50" y1="75" y2="75" stroke="green" fill="transparent" stroke-width="5"/><polyline stroke="green" fill="transparent" stroke-width="5" points="60,110 65,120 70,115 75,130 80,125 85,140 90,135 95,150 100,145" /><polygon stroke="blue" fill="transparent" stroke-width="5" points="50,160 55,180 70,180 60,190 65,205 50,195 35,205 40,190 30,180 45,180" /><ellipse rx="10" ry="30" cx="25" cy="50" stroke="red" fill="blue" stroke-width="2"/></svg>
            HTML;
        $this->assertEquals($expected, $svg->toHtml());
    }
}
