<?php

declare(strict_types=1);

namespace ASK\Svg;

use ASK\Svg\Exceptions\TransformException;
use NumPHP\Core\NumArray;
use NumPHP\LinAlg\LinAlg;

use function PHPSTORM_META\type;

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
        $this->transformations = $this->getMatrixFromStrin($svgTransformAttribute);
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
        if (is_array($this->transformations)) {
            $transformations = array_reverse($this->transformations);
        } else {
            return $transformedPoint;
        }
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
        $ret = 'transform="' . trim(implode(' ', $this->toStringEach()));

        return $ret;
    }

    public function toStringEach()
    {
        $ret = [];
        foreach ($this->transformations as $transformation) {
            $type = array_key_first($transformation);
            switch ($type) {
                case 'translate':
                    if (count($transformation) > 1) {
                        $rotate = 'rotate(';
                        $dataT = $transformation['translate']->getData();
                        $dataR = $transformation['rotation']->getData();
                        $deg = (acos($dataR[0][0]) > 0 && asin($dataR[1][0]) > 0) ? (acos($dataR[0][0]) * 180 / pi()) : 360 - (acos($dataR[0][0]) * 180 / pi());
                        $rotate .= $deg . (((int)$dataT[0][2] !== 0 && (int)$dataT[1][2] !== 0) ? ', ' . $dataT[0][2] . ', ' . $dataT[1][2] : '') . ')';
                        $ret[] = $rotate;
                        break;
                    } else {
                        $data = $transformation['translate']->getData();
                        $translate = 'translate(' . $data[0][2] . ', ' . $data[0][2] . ')';
                        $ret[] = $translate;
                    }
                    break;
                case 'scale':
                    $data = $transformation['scale']->getData();
                    $scale = 'scale(' . $data[0][0] . ($data[1][1] === 0 ? '' : ', ' . $data[1][1]) . ')';
                    $ret[] = $scale;
                    break;
                case 'matrix':
                    $data = $transformation['matrix']->getData();
                    $matrix = 'matrix(' . $data[0][0] . ', ' . $data[0][1] . ', ' . $data[1][0] . ', ' . $data[1][1] . ', ' . $data[0][2] . ', ' . $data[1][2] . ')';
                    $ret[] = $matrix;
                    break;
                case 'skewX':
                    $data = $transformation['skewX']->getData();
                    $skewX = 'skewX(' . atan($data[0][2]) . ')';
                    $ret[] = $skewX;
                    break;
                case 'skewY':
                    $data = $transformation['skewY']->getData();
                    $skewY = 'skewY(' . atan($data[0][1]) . ')';
                    $ret[] = $skewY;
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

    public function addTransformation($transformation, $position = -1)
    {
        if (is_string($transformation)) {
            $transformation = $this->getMatrixFromStrin($transformation);
        }
        if (empty($this->transformations)) {
            $this->transformations = $transformation;
            return $this;
        }
        if ($position === -1) {
            $this->transformations[] = $transformation;
            return $this;
        }
        if ($position < 0) {
            $position =  count($this->transformations) + $position + 1;
            if ($position < 0) {
                throw TransformException($position);
            }
        }

        $pushed = 0;
        foreach ($this->transformations as $key => $value) {
            if ($key === $position) {
                $this->transformations[$key] = $transformation[0];
                $this->transformations[$key + 1] = $value;
                $pushed = 1;
            } else {
                $this->transformations[$key + $pushed] = $value;
            }
        }
    }

    /**
     * Get the value of transformations
     */
    public function getTransformations()
    {
        return $this->transformations;
    }

    public function getMatrixFromStrin(string $transformationString)
    {
        $returnTransformations = [];
        preg_match_all('/([a-z]+)\(([0-9.-]+)(?:[,\s]+([0-9.-]+))?(?:[,\s]+([0-9.-]+))?(?:[,\s]+([0-9.-]+))?(?:[,\s]+([0-9.-]+))?(?:[,\s]+([0-9.-]+))?\)/', $transformationString, $transformations);
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
                    $returnTransformations[] = [
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
                    $returnTransformations[] = [
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
                    $returnTransformations[] = [
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
                    $returnTransformations[] = [
                        'translate' => $matrixT,
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
                    $returnTransformations[] = [
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
                    $returnTransformations[] = [
                        'skewY' => $matrixT
                    ];
                    break;
                default:
                    $returnTransformations[] = [
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

        if (!isset($returnTransformations)) {

            $returnTransformations[] = [
                'matrix' => new NumArray(
                    [
                        [1, 0, 0],
                        [0, 1, 0],
                        [0, 0, 1],
                    ]
                )
            ];
        }
        return $returnTransformations;
    }
}
