<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use NumPHP\Core\NumArray;
use NumPHP\LinAlg\LinAlg;

/**
 * Transformation
 */
class Transformation
{
    public const SVG_TRANSFORMATION = [
        'translate',
        'scale',
        'matrix',
        'rotate',
        'skewX',
        'skewY',
    ];
    
    /** @var array $transformations */
    protected $transformations;
    
    /**
     *
     *
     * @param  string $svgTransformAttribute
     * @return void
     */
    public function __construct(string $svgTransformAttribute = '')
    {
        preg_match_all('/([a-z]+)\(([0-9.-]+)[,\s]?([0-9.-]+)?[,\s]?([0-9.-]+)?[,\s]?([0-9.-]+)?[,\s]?([0-9.-]+)?[,\s]?([0-9.-]+)?\)/', $svgTransformAttribute, $transformations);
        foreach ($transformations[1] as $k => $transformationName) {
            switch ($transformationName) {
                case 'translate':
                    $matrixT = new NumArray(
                        [
                            [1, 0, (float)$transformations[2][$k]] ?? 0,
                            [0, 1, (float)$transformations[3][$k]] ?? 0,
                            [0, 0, 1],
                        ]
                    );
                    $this->transformations[] = [
                        $transformationName => $matrixT
                    ];
                    break;
                case 'scale':
                    $sx = (float)$transformations[2][$k] ?? 1;
                    $sy = (float)$transformations[3][$k] === 0 ? $sx : (float)$transformations[3][$k];
                    $matrixS = new NumArray(
                        [
                            [$sx, 0, 0],
                            [0, $sy, 0],
                            [0, 0, 1],
                        ]
                    );
                    $this->transformations[] = [
                        $transformationName => $matrixS
                    ];
                    break;
                case 'matrix':
                    $matrix = new NumArray(
                        [
                            [(float)$transformations[2][$k] ?? 1, (float)$transformations[4][$k] ?? 0, (float)$transformations[6][$k] ?? 0],
                            [(float)$transformations[3][$k] ?? 0, (float)$transformations[5][$k] ?? 1, (float)$transformations[7][$k] ?? 0],
                            [0, 0, 1],
                        ]
                    );
                    $this->transformations[] = [
                        $transformationName => $matrix
                    ];
                    break;
                case 'rotate':
                    $a = (float)$transformations[2][$k] ?? 0;
                    $x = (float)$transformations[3][$k] ?? 0;
                    $y = (float)$transformations[4][$k] ?? 0;
                    $a = $a * pi() / 180;
                    $matrixR = new NumArray(
                        [
                            [cos($a), -sin($a), 0],
                            [sin($a), cos($a), 0],
                            [0, 0, 1],
                        ]
                    );
                    $matrixT = new NumArray(
                        [
                            [1, 0, $x],
                            [0, 1, $y],
                            [0, 0, 1],
                        ]
                    );
                    $matrixTminus = new NumArray(
                        [
                            [1, 0, -$x],
                            [0, 1, -$y],
                            [0, 0, 1],
                        ]
                    );
                    $this->transformations[] = [
                        'translation' => $matrixT,
                        'rotation' => $matrixR,
                        'backTranslationBack' => $matrixTminus
                    ];
                    break;
                case 'skewX':
                    $a = (float)$transformations[2][$k] ?? 0;
                    $a = $a * pi() / 180;
                    $matrixT = new NumArray(
                        [
                            [1, tan($a), 0],
                            [0, 1, 0],
                            [0, 0, 1],
                        ]
                    );
                    $this->transformations[] = [
                        'skewX' => $matrixT
                    ];
                    break;
                case 'skewY':
                    $a = (float)$transformations[2][$k] ?? 0;
                    $a = $a * pi() / 180;
                    $matrixT = new NumArray(
                        [
                            [1, 0, 0],
                            [tan($a), 1, 0],
                            [0, 0, 1],
                        ]
                    );
                    $this->transformations[] = [
                        'skewY' => $matrixT
                    ];
                    break;
                default:
                    $this->transformations[] = [
                        'matrix' => new NumArray(
                            [
                                [1, 0, 0],
                                [0, 1, 0],
                                [0, 0, 1],
                            ]
                        )
                    ];
            }
        }
        if(!isset($this->transformations)){
            $this->transformations[] = [
                'matrix' => new NumArray(
                    [
                        [1, 0, 0],
                        [0, 1, 0],
                        [0, 0, 1],
                    ]
                )
            ];
        }
    }
     
    /**
     * getOriginal
     *
     * @param  mixed $transformedPoint
     * @return NumArray
     */
    public function getOriginal(NumArray $transformedPoint): NumArray
    {
        $data = $transformedPoint->getData();
        if (count($data) < 3) {
            $data[] = 1;
            $transformedPoint = new NumArray($data);
        }
        $OriginalPoint = $transformedPoint;
        $transformations = $this->transformations;
        foreach ($transformations as $transform) {
            foreach ($transform as $matrix) {
                $matrix = new NumArray($matrix->getData());
                $matrixTransform = LinAlg::inv($matrix);
                $OriginalPoint = $matrixTransform->dot($OriginalPoint);
            }
        }
        return $OriginalPoint;
    }
    
    /**
     * getTransformed
     *
     * @param  mixed $OriginalPoint
     * @return NumArray
     */
    public function getTransformed(NumArray $OriginalPoint): NumArray
    {
        $data = $OriginalPoint->getData();
        if (count($data) < 3) {
            $data[] = 1;
            $OriginalPoint = new NumArray($data);
        }

        $transformedPoint = $OriginalPoint;
        $transformations = array_reverse($this->transformations);
        foreach ($transformations as $transform) {
            $transform = array_reverse($transform);
            foreach ($transform as $k => $matrix) {
                $matrix = new NumArray($matrix->getData());
                $transformedPoint = $matrix->dot($transformedPoint);
            }
        }
        return $transformedPoint;
    }
}
