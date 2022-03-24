# ASK - PHP Svg Manipulation 

> This file is public domain. Use it for any purpose, including commercial
> applications. Attribution would be nice, but is not required. There is
> no warranty of any kind, including its correctness, usefulness, or safety.

> **Author: Alberto Solorzano Kraemer ( alberto.kraemer@gmail.com )**

////////////////////////////////////////////////////////////////////
//                                                                //
//                    ////    //////   //   //                    //
//                   // //   //        //  //                     //
//                  //  //   //        // //                      //
//                 ///////    //////   ////                       //
//                //    //        //   // //                      //
//               //     //   //////    //  //                     //
//                            __!__                               //
//                      ^----o-(_)-o----^                         //
////////////////////////////////////////////////////////////////////

## Command 
A command in a *d* attribute of a svg path

There are five line commands for <path> nodes.
* M - *Move*
* L - *Line*
* H - *Horizontal*
* V - *Vertical*
* Z - *Close*

und five arc  commands.
* C - *Cubic Curve*
* Q - *Quadratic Curve*
* S - *Short Cubic Curve*
* T - *Together Multiple Quadratic Curve*
* A - *Arc*

Each command contains a $coordinates array with all the parameters of each point,
as well as a reference to the previous command. This class implements: \Illuminate\Contracts\Support\Htmlable


* ## A 
A comand "a" in a d attribute of a svg path

Arcs are sections of circles or ellipses.
For a given x-radius and y-radius, there are two ellipses that can
connect any two points (last end point and (x, y)).
Along either of those circles, there are two possible paths that can
be taken to connect the points (large way or short way) so in any situation,
there are four possible arcs available.

Because of that, arcs require seven parameters:
A rx ry x-axis-rotation large-arc-flag sweep-flag x y
a rx ry x-axis-rotation large-arc-flag sweep-flag dx dy

A command hat in aditional to the other commands a getCenter Methode

* ## C 
A comand "c" in a d attribute of a svg path

The cubic curve, C, is the slightly more complex curve.
Cubic Béziers take in two control points for each point.
Therefore, to create a cubic Bézier, three sets of coordinates need to be specified.

C x1 y1, x2 y2, x y
c dx1 dy1, dx2 dy2, dx dy

* ## H 
A comand "h" in a d attribute of a svg path

A command draws a horizontal line, this command only take
one parameter since they only move in one direction.

H x
h dx

* ## L 
A comand "h" in a d attribute of a svg path

L x y
l dx dy

* ## M 
A comand "m" in a d attribute of a svg path

M x y
m dx dy

* ## Q 
A comand "q" in a d attribute of a svg path

Q x1 y1, x y
q dx1 dy1, dx dy

* ## S 
A comand "s" in a d attribute of a svg path

S x2 y2, x y
s dx2 dy2, dx dy

* ## T 
A comand "t" in a d attribute of a svg path

T x y
t dx dy

* ## V 
A comand "v" in a d attribute of a svg path

V y
v dy

* ## Z 
A comand "z" in a d attribute of a svg path



* ## CacheCommand 




* ## ClearCommand 




## Shape 
An element that make a Shape in a svg document



* ## Circle 
A Circle element in a svg document



* ## Ellipse 
A Ellipse element in a svg document



* ## Line 
A Line element in a svg document



* ## Path 
A Path element in a svg document



* ## Polygon 
A Polygon element in a svg document



* ## Polyline 
A Polyline element in a svg document



* ## Rect 
A Rect element in a svg document



* ## Text 
a Text element in a svg document




## Classes

* A - A comand "a" in a d attribute of a svg path
	* BladeIconsServiceProvider - 
	* C - A comand "c" in a d attribute of a svg path
	* CacheCommand - 
	* Circle - A Circle element in a svg document
	* ClearCommand - 
	* ClipPhat - ClipPhat in a svg document
	* ComandException - ComandException
	* Configurator - Configurator is an element within the document that is used to set or modify the behavior of the svg
	* Defs - The Definitions element in a svg document
	* Ellipse - A Ellipse element in a svg document
	* Factory - 
	* FeEfect - a Filter efect used in a definitions element to define a filter
	* Filter - A Filter element to be used in a definitions element in a svg document
	* Font - A Font element into a svg document
	* G - A group element "g" in a svg document
	* H - A comand "h" in a d attribute of a svg path
	* Icon - Icon
	* IconGenerator - 
	* IconsManifest - 
	* L - A comand "h" in a d attribute of a svg path
	* Line - A Line element in a svg document
	* LinearGradient - A LinearGradient element in a svg document
	* M - A comand "m" in a d attribute of a svg path
	* Mask - A Mask element in a svg document
	* Path - A Path element in a svg document
	* Pattern - A Pattern element in a svg document
	* Polygon - A Polygon element in a svg document
	* Polyline - A Polyline element in a svg document
	* Q - A comand "q" in a d attribute of a svg path
	* RadialGradient - A RadialGradient element in a svg document
	* Rect - A Rect element in a svg document
	* S - A comand "s" in a d attribute of a svg path
	* Style - The Style element in a svg document
	* Svg - Svg
	* Svg - the Svg document
	* SvgElement - # An element belonging to a svg structure
	* T - A comand "t" in a d attribute of a svg path
	* Text - a Text element in a svg document
	* Transformation - a Transformation Objet that represent the transformation matrix of a svg transformation
	* V - A comand "v" in a d attribute of a svg path
	* Z - A comand "z" in a d attribute of a svg path
	
--------
> This document was automatically generated from source code comments 
> on 2022-03-23 using [phpDocumentor](http://www.phpdoc.org/) 
> and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
