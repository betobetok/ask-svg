<?php

declare(strict_types=1);

namespace ASK\Svg;

use NumPHP\Core\NumArray;
use NumPHP\LinAlg\LinAlg;

/**
 * a Transformation Objet that represent the transformation matrix of a svg transformation
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
                    $sy = (float)$transformations[3][$k] === 0.0 ? $sx : (float)$transformations[3][$k];
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
                    break;
            }
        }

        if (!isset($this->transformations)) {

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
     * get the original point (x, y) from a transformed point (x', y') through the transformations in the svg object
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
     * get the transformed point (x', y') from an original point (x, y) through the transformations in the svg object
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

    public function __toString()
    {
        $ret = 'transform="';
        foreach ($this->transformations as $transformation) {
            $type = array_key_first($transformation);

            switch ($type) {
                case 'translate':
                    if (count($transformation) > 1) {
                        $ret .= ' rotate(';
                        $dataT = $transformation['translation']->getData();
                        $dataR = $transformation['rotation']->getData();
                        $ret .= acos($dataR[0][0]) . (($dataT[0][2] !== 0 && $dataT[1][2] !== 0) ? ', ' . $dataT[0][2] . ', ' . $dataT[1][2] : '') . ')';
                        break;
                    } else {
                        $data = $transformation['translate']->getData();
                        $ret .= ' translate(' . $data[0][2] . ', ' . $data[0][2] . ')';
                    }
                    break;
                case 'scale':
                    $data = $transformation['scale']->getData();
                    $ret .= ' scale(' . $data[0][0] . ($data[1][1] === 0 ? '' : ', ' . $data[1][1]) . ')';
                    break;
                case 'matrix':
                    $data = $transformation['matrix']->getData();
                    $ret .= ' matrix(' . $data[0][0] . ', ' . $data[0][1] . ', ' . $data[1][0] . ', ' . $data[1][1] . ', ' . $data[0][2] . ', ' . $data[1][2] . ')';
                    break;
                case 'skewX':
                    $data = $transformation['skewX']->getData();
                    $ret .= ' skewX(' . atan($data[0][2]) . ')';
                    break;
                case 'skewY':
                    $data = $transformation['skewY']->getData();
                    $ret .= ' skewY(' . atan($data[0][1]) . ')';
                    break;
                default:
                    $ret = '';
            }
        }
        return $ret;
    }

    public function getTransformMatrix($toString = false)
    {
        $return = new NumArray([
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 1],
        ]);
        foreach ($this->transformations as $transformgroup) {
            foreach ($transformgroup as $transform); {
                $return->dot($transform);
            }
        }
        if ($toString) {
            $m = $return->getData();
            return 'transform="matrix(' . $m[0][0] . ',' . $m[0][1] . ',' . $m[1][0] . ',' . $m[1][1] . ',' . $m[0][2] . ',' . $m[1][2] . ')"';
        } else {
            return $return;
        }
    }
}
