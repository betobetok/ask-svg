<?php

declare(strict_types=1);

namespace Tests\BladeUiTests;

use ASK\Svg\Components\Svg;
use Illuminate\Support\Facades\Blade;
use InvalidArgumentException;

class ComponentsTest extends TestCase
{
    /** @test */
    public function components_are_registered_with_their_subdirectories()
    {
        $this->prepareSets()->registerComponents();

        $compiled = Blade::getClassComponentAliases();

        $expected = [
            'icon-camera' => Svg::class,
            'icon-foo-camera' => Svg::class,
            'icon-solid.camera' => Svg::class,
            'icon-zondicon-flag' => Svg::class,
            'zondicon-flag' => Svg::class,
        ];

        foreach ($expected as $alias => $component) {
            $this->assertArrayHasKey($alias, $compiled);
            $this->assertSame(Svg::class, $component);
        }
    }

    /** @test */
    public function components_are_not_registered_when_disabled()
    {
        $this->prepareSets(['components' => ['disabled' => true]])->registerComponents();

        $compiled = Blade::getClassComponentAliases();

        $this->assertNotContains(Svg::class, $compiled);
    }

    /** @test */
    public function it_can_render_an_icon()
    {
        $this->prepareSets();

        $view = $this->blade('<x-icon-camera/>');

        $expected = <<<'HTML'
            <svg id="svg6" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path2" d="M 3 9 a 2 2 0 0 1 2 -2 h 0.93 a 2 2 0 0 0 1.664 -0.89 l 0.812 -1.22 A 2 2 0 0 1 10.07 4 h 3.86 a 2 2 0 0 1 1.664 0.89 l 0.812 1.22 A 2 2 0 0 0 18.07 7 H 19 a 2 2 0 0 1 2 2 v 9 a 2 2 0 0 1 -2 2 H 5 a 2 2 0 0 1 -2 -2 V 9 z " /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path4" d="M 15 13 a 3 3 0 1 1 -6 0, 3 3 0 0 1 6 0 z " /></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_with_default_classes()
    {
        $this->prepareSets([], ['default' => ['class' => 'w-6 h-6']]);

        $view = $this->blade('<x-icon-camera/>');

        $expected =  <<<'HTML'
            class="w-6 h-6"
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_with_default_classes_and_set_classes()
    {
        $this->prepareSets(['class' => 'text-blue-500'], ['default' =>  ['class' => 'w-6 h-6']]);

        $view = $this->blade('<x-icon-camera class="icon icon-lg" data-foo/>');

        $expected = <<<'HTML'
            <svg id="svg6" dataFoo="true" class="text-blue-500 w-6 h-6 icon icon-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path2" d="M 3 9 a 2 2 0 0 1 2 -2 h 0.93 a 2 2 0 0 0 1.664 -0.89 l 0.812 -1.22 A 2 2 0 0 1 10.07 4 h 3.86 a 2 2 0 0 1 1.664 0.89 l 0.812 1.22 A 2 2 0 0 0 18.07 7 H 19 a 2 2 0 0 1 2 2 v 9 a 2 2 0 0 1 -2 2 H 5 a 2 2 0 0 1 -2 -2 V 9 z " /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path4" d="M 15 13 a 3 3 0 1 1 -6 0, 3 3 0 0 1 6 0 z " /></svg>
            HTML;
        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_with_default_attributes()
    {
        $this->prepareSets(['attributes' => ['width' => 50, 'height' => 50]]);

        $view = $this->blade('<x-icon-camera/>');

        $expected = <<<'HTML'
            <svg id="svg6" width="50" height="50" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" >
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_with_default_attributes_and_set_attributes()
    {
        $this->prepareSets(['attributes' => ['width' => 50]], ['default' => ['attributes' => ['height' => 50]]]);

        $view = $this->blade('<x-icon-camera/>');

        $expected = <<<'HTML'
            <svg id="svg6" width="50" height="50" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" >
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_does_not_duplicate_attributes()
    {
        $this->prepareSets(['attributes' => ['height' => 50]], ['default' => ['attributes' => ['height' => 50]]]);

        $view = $this->blade('<x-icon-camera/>');

        $expected = <<<'HTML'
            <svg id="svg6" height="50" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" >
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_from_a_subdirectory()
    {
        $this->prepareSets();

        $view = $this->blade('<x-icon-solid.camera/>');

        $expected = <<<'HTML'
            <svg id="solid.camera" viewBox="0 0 20 20" fill="currentColor" ><path fill-rule="evenodd" clip-rule="evenodd" d="M 4 5 a 2 2 0 0 0 -2 2 v 8 a 2 2 0 0 0 2 2 h 12 a 2 2 0 0 0 2 -2 V 7 a 2 2 0 0 0 -2 -2 h -1.586 a 1 1 0 0 1 -0.707 -0.293 l -1.121 -1.121 A 2 2 0 0 0 11.172 3 H 8.828 a 2 2 0 0 0 -1.414 0.586 L 6.293 4.707 A 1 1 0 0 1 5.586 5 H 4 z m 6 9 a 3 3 0 1 0 0 -6, 3 3 0 0 0 0 6 z " /></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_from_a_specific_set()
    {
        $this->prepareSets();

        $view = $this->blade('<x-zondicon-flag/>');

        $expected = <<<'HTML'
            <svg id="flag" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" ><path d="M 7.667 12 H 2 v 8 H 0 V 0 h 12 l 0.333 2 H 20 l -3 6, 3 6 H 8 l -0.333 -2 z " /></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_attributes()
    {
        $this->prepareSets();

        $view = $this->blade('<x-icon-camera class="icon icon-lg" data-foo/>');

        $expected = <<<'HTML'
            <svg id="svg6" dataFoo="true" class="icon icon-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" >
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_with_the_icon_component()
    {
        $this->prepareSets();

        $view = $this->blade('<x-icon name="camera" class="icon icon-lg" data-foo/>');

        $expected = <<<'HTML'
            <svg id="svg6" dataFoo="true" class="icon icon-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path2" d="M 3 9 a 2 2 0 0 1 2 -2 h 0.93 a 2 2 0 0 0 1.664 -0.89 l 0.812 -1.22 A 2 2 0 0 1 10.07 4 h 3.86 a 2 2 0 0 1 1.664 0.89 l 0.812 1.22 A 2 2 0 0 0 18.07 7 H 19 a 2 2 0 0 1 2 2 v 9 a 2 2 0 0 1 -2 2 H 5 a 2 2 0 0 1 -2 -2 V 9 z " /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path4" d="M 15 13 a 3 3 0 1 1 -6 0, 3 3 0 0 1 6 0 z " /></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_from_a_specific_set_with_the_icon_component()
    {
        $this->prepareSets();

        $view = $this->blade('<x-icon name="zondicon-flag"/>');

        $expected = <<<'HTML'
            <svg id="flag" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" ><path d="M 7.667 12 H 2 v 8 H 0 V 0 h 12 l 0.333 2 H 20 l -3 6, 3 6 H 8 l -0.333 -2 z " /></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_can_render_an_icon_when_multiple_paths_are_defined()
    {
        $factory = $this->prepareSets();

        $factory->add('mixed', [
            'paths' => [
                __DIR__ . '/resources/svg/',
                __DIR__ . '/resources/zondicons/',
            ],
            'prefix' => 'mixed',
        ]);

        $view = $this->blade('<x-mixed-camera/>');

        $expected = <<<'HTML'
            <svg id="svg6" fill="none" viewBox="0 0 24 24" stroke="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" ><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path2" d="M 3 9 a 2 2 0 0 1 2 -2 h 0.93 a 2 2 0 0 0 1.664 -0.89 l 0.812 -1.22 A 2 2 0 0 1 10.07 4 h 3.86 a 2 2 0 0 1 1.664 0.89 l 0.812 1.22 A 2 2 0 0 0 18.07 7 H 19 a 2 2 0 0 1 2 2 v 9 a 2 2 0 0 1 -2 2 H 5 a 2 2 0 0 1 -2 -2 V 9 z " /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" id="path4" d="M 15 13 a 3 3 0 1 1 -6 0, 3 3 0 0 1 6 0 z " /></svg>
            HTML;

        $view->assertSee($expected, false);

        $view = $this->blade('<x-mixed-flag/>');

        $expected = <<<'HTML'
            <svg id="flag" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" ><path d="M 7.667 12 H 2 v 8 H 0 V 0 h 12 l 0.333 2 H 20 l -3 6, 3 6 H 8 l -0.333 -2 z " /></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function it_files_without_an_svg_extension_are_not_registered()
    {
        $this->prepareSets();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to locate a class or view for component [icon-invalid-extension].');

        $this->blade('<x-icon-invalid-extension/>');
    }
}
