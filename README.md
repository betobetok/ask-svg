# ASK - PHP Svg Manipulation 

<a href="https://github.com/">
    <img src="https://github.com/" alt="Tests">
</a>
<a href="https://github.styleci.io/repos/">
    <img src="https://github.styleci.io/repos/" alt="Code Style">
</a>
<a href="https://packagist.org/packages/">
    <img src="https://img.shields.io/packagist/" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/">
    <img src="https://img.shields.io/packagist/" alt="Total Downloads">
</a>

A package to convert an svg graphic file into a Php object. based on [Blade UIkit](https://github.com/blade-ui-kit/blade-icons)

by using the `svg()` function we can retrieve an svg file using the file name and convert it into an object of the Svg class and manipulate it as such.

```php
$mySvg = svg('my_svg');
dd($mySvg);
```
svg file [my_svg.svg](resources/svg/my_svg.svg)

```
ASK\Svg\Svg ({)#427 ▼
  #name: "svg"
  #elements: array:1 [▼
    0 => ASK\Svg\Configurators\G ({)#301 ▼
      #name: "g"
      #elements: array:37 [▶]
      #context: ASK\Svg\Svg ({)#427(})
      #transforms: ASK\Svg\Transformation ({)#424 ▼
        #transformations: array:1 [▼
          0 => array:1 [▼
            "matrix" => NumPHP\Core\NumArray ({)#423 ▶(})
          ]
        ]
      (})
      #isTransformable: true
      -attributes: array:1 [▶]
      +"g": array:37 [▶]
    (})
  ]
  #context: null
  #isTransformable: false
  -attributes: array:6 [▼
    "id" => "my_svg"
    "width" => "473"
    "height" => "477"
    "viewBox" => "0 0 473 477"
    "fill" => "none"
    "xmlns" => "http://www.w3.org/2000/svg"
  ]
  +style: ASK\Svg\Configurators\Style ({)#426 ▼
    #name: "style"
    #context: ASK\Svg\Svg ({)#427(})
    #isTransformable: false
    -attributes: []
    -classes: []
    -rules: []
  (})
  +"g": array:1 [▼
    0 => ASK\Svg\Configurators\G ({)#301 ▶(})
  ]
(})
```

---

<h2 style = "color:#88ff88" id="Svg">The Svg class </h2>

<pre> Full name: \ASK\Svg\Svg</pre>

the Svg document



      
Class ***Svg*** implements: ```\ASK\Svg\Conteiner```

### Svg Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```public```|*style*|`Configurators\Style`||
</details>

---

### Svg Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Svg :: constructor </h2>






```php
Svg (string fileName, string contents, array attributes = []): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *fileName* | `string` |  |
| *contents* | `string` |  |
| *attributes* | `array` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: getStylefromContent </h2>

get the Style element from the string content




```php
public getStylefromContent(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: removeStylefromContent </h2>

remove the string Style from the string content




```php
public removeStylefromContent(): self
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: style </h2>

get the Style element




```php
public style(): Configurators\Style
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: setStyle </h2>

set the Style element




```php
public setStyle(Configurators\Style style): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *style* | `Configurators\Style` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: replaceClasses </h2>

replace the Classes names in the string content




```php
public replaceClasses(Configurators\Style style, string content): string
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *style* | `Configurators\Style` |  |
| *content* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: mergeSvgs </h2>

merge one or more Svgs in this svg




```php
public mergeSvgs(Svg[] param): Svg
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *param* | `Svg[]` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: toHtml </h2>

implements of Htmlable, toHtml return a string form of the svg in HTML format




```php
public toHtml(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: getAllSvgElements </h2>

get all the Svg elements in this svg,
this array conteins all the elements in order




```php
public getAllSvgElements(mixed svg): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *svg* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: cleanContent </h2>

cleanContent remove all string contents in the complet object




```php
public cleanContent(): self
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: getContent </h2>

Get the value of content
(Conteiner implement) //TODO implementation of container




```php
public getContent(): mixed
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: setContent </h2>

Set the value of content
(Conteiner implement) //TODO implementation of container




```php
public setContent(mixed content): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *content* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: removeSvgAttribute </h2>

removes those attributes that belong exclusively to the svg element




```php
public removeSvgAttribute(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> Svg :: getOnlySvgAttribute </h2>

get those attributes that belong exclusively to the svg element




```php
public getOnlySvgAttribute(): array
```




**returns** 



---

</details>


------
---

<h2 style = "color:#88ff88" id="SvgElement">The SvgElement class </h2>

<pre> Full name: \ASK\Svg\SvgElement</pre>

# An element belonging to a svg structure

This object represents all the elements within an svg document

from the svg parent element to the internal elements or
figures such as \&lt;path\&gt;, \&lt;circle\&gt; or \&lt;g\&gt;, passing through
configuration elements such as \&lt;style\&gt;, \&lt;defs\&gt; among others

all elements are accessible in order through the attribute $elements,
as well as through the array in the property with the name of the element

for example:
- $svg-&gt;g returns all &lt;g&gt; elements
- $svg-&gt;elements[0] return the first element in the &lt;svg&gt;&lt;/svg&gt;
- $svg-&gt;g[0]-&gt;elements[0] return the first element in the &lt;g&gt;&lt;/g&gt;

      
Class ***SvgElement*** implements: ```\Illuminate\Contracts\Support\Htmlable```

### SvgElement Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*name*|`string`||
|```protected```|*elements*|`SvgElement[]`||
|```protected```|*contents*|`string`||
|```protected```|*context*|`SvgElement`||
|```protected```|*transforms*|`Transformation`||
|```protected```|*isTransformable*|`bool`||
</details>

---

### SvgElement Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> SvgElement :: constructor </h2>






```php
SvgElement (string name, string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *name* | `string` |  |
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: makeTransformable </h2>

Make this element Transformable




```php
public makeTransformable(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: makeUntransformable </h2>

make this element Untransformable




```php
public makeUntransformable(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: name </h2>

get or set the name of the element




```php
public name(string arg = &#039;&#039;): string|self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *arg* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: id </h2>

get or set the id attribute of the element in the attributes property




```php
public id(string arg = &#039;&#039;): string|self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *arg* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: transform </h2>

set or get the Transformation Object for the element




```php
public transform(mixed arg = null): Transformation|string
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *arg* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: contents </h2>

get the string contents of the elements
- this method is similar at toHtml
  but get just the string of the content without the tag string




```php
public contents(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: removeContents </h2>

remove the string Contents property from the object




```php
public removeContents(): self
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: setContents </h2>

set the string Contents property in the object




```php
public setContents(string contents): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: getContext </h2>

get the property Context that is the parent SVG element of this element




```php
public getContext(): SvgElement|null
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: getAllElements </h2>

get all Elements from the string content
this method make all found elements as SvgElement Objects and
put in a property with the element name




```php
public getAllElements(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: setElement </h2>

set a neu Element in this object




```php
public setElement(string name, mixed element): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *name* | `string` |  |
| *element* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: mergeAttributes </h2>

merge the attributes conteined in a string tag with the array attributes




```php
public mergeAttributes(string tag, array attributes): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *tag* | `string` |  |
| *attributes* | `array` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: removeComents </h2>

remove coments from the string content




```php
public removeComents(): self
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: toHtml </h2>

implements of Htmlable, toHtml return a string form of the svg element in HTML format




```php
public toHtml(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: removeId </h2>

remove the property Id




```php
public removeId(): self
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: toArray </h2>

toArray




```php
public toArray(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: serialize </h2>






```php
public serialize(mixed data): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *data* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: unserialize </h2>






```php
public unserialize(mixed data): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *data* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: findFirstGroupElement </h2>

find the first group element with the name $element and
return the new corresponding SvgElement instance
or null if not found




```php
public findFirstGroupElement(string element): SvgElement|null
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *element* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: findFirstElement </h2>

return the first Element in the elements array
or null if not found




```php
public findFirstElement(string elementName): SvgElement|null
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *elementName* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: findFirstNonGroupElement </h2>

find the first non-group element with the name $element and
return the new corresponding SvgElement instance
or null if not found




```php
public findFirstNonGroupElement(string element): SvgElement|null
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *element* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: getElementAttributes </h2>

get the attributes of a element froma string tag
(<tag attribute="value"></tag>)




```php
public getElementAttributes(string tag): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *tag* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: getTransformations </h2>

get the Transformations of the element and make the corresponding Transformation Object




```php
public getTransformations(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: getElementById </h2>

find an element by its id and return it
or null if not found




```php
public getElementById(string id): SvgElement|null
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *id* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: getStartPointById </h2>

get the start position point of an Shape element by its Id
and return it or null if not found




```php
public getStartPointById(string id): \NumPHP\Core\NumArray|null
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *id* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> SvgElement :: hasContext </h2>

return true if the element has content or false otherwise




```php
public hasContext(): void
```




**returns** 



---

</details>


---

---

<h2 style = "color:#8888AA" id="Shape">The abstract class Shape</h2>

<pre> Full name: \ASK\Svg\Shapes\Shape</pre>

An element that make a Shape in a svg document




### Shape Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*startPosition*|`\NumPHP\Core\NumArray`||
</details>

---

### Shape Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Shape :: constructor </h2>






```php
Shape (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Shape :: getStartPosition </h2>

get the Start position o the element




```php
public getStartPosition(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> Shape :: renderAttributes </h2>

renderAttributes return a string with attributes in a HTML format
(overloaded Method from RenderAttributes)




```php
protected renderAttributes(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Shape :: toHtml </h2>

(overloaded Method from SvgElement)




```php
public toHtml(): string
```




**returns** 



---

</details>

---
<h2 style = "color:#8888ff" id="Circle">Class Circle</h2>

<pre> Full name: \ASK\Svg\Shapes\Circle</pre>

A Circle element in a svg document



      
Class ***Circle*** inherits from class: ```Shape```


---

### Circle Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*cx*|`float`||
|```protected```|*cy*|`float`||
|```protected```|*r*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Circle :: constructor </h2>






```php
Circle (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Circle :: center </h2>

get the center of the circle




```php
public center(): \NumPHP\Core\NumArray
```




**returns** 



---


<h2 style = "color:#aa88cc"> Circle :: diameter </h2>

get the Diameter of the circle




```php
public diameter(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> Circle :: radio </h2>

get the Radio of the circle




```php
public radio(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> Circle :: area </h2>

get the Area of the circle




```php
public area(): float
```




**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Ellipse">Class Ellipse</h2>

<pre> Full name: \ASK\Svg\Shapes\Ellipse</pre>

A Ellipse element in a svg document



      
Class ***Ellipse*** inherits from class: ```Shape```


---

### Ellipse Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*cx*|`float`||
|```protected```|*cy*|`float`||
|```protected```|*rx*|`float`||
|```protected```|*ry*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Ellipse :: constructor </h2>






```php
Ellipse (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Ellipse :: center </h2>

get the Center of the ellipse




```php
public center(): \NumPHP\Core\NumArray
```




**returns** 



---


<h2 style = "color:#aa88cc"> Ellipse :: radioX </h2>

get the x Radio of the ellipse




```php
public radioX(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> Ellipse :: radioY </h2>

get the y Radio of the ellipse




```php
public radioY(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> Ellipse :: area </h2>

get the Area of the ellipse




```php
public area(): float
```




**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Line">Class Line</h2>

<pre> Full name: \ASK\Svg\Shapes\Line</pre>

A Line element in a svg document



      
Class ***Line*** inherits from class: ```Shape```


---

### Line Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*x1*|`float`||
|```protected```|*y1*|`float`||
|```protected```|*x2*|`float`||
|```protected```|*y2*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Line :: constructor </h2>






```php
Line (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Line :: long </h2>

get teh Logitud of the line




```php
public long(): float
```




**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Path">Class Path</h2>

<pre> Full name: \ASK\Svg\Shapes\Path</pre>

A Path element in a svg document



      
Class ***Path*** inherits from class: ```Shape```


---

### Path Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```private```|*dString*|`string`||
|```protected```|*d*|`array`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Path :: constructor </h2>






```php
Path (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Path :: toHtml </h2>

(overloaded Method from SvgElement)




```php
public toHtml(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Path :: content </h2>

content get the content string of the svg elemnt to print in a HTML document




```php
public content(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Path :: getExistingComands </h2>

getExistingComands get the existing comands in a d attribute from a string




```php
public getExistingComands(string d): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *d* | `string` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Path :: renderAttributes </h2>

renderAttributes return a string with attributes in a HTML format
(overloaded Method from RenderAttributes)




```php
protected renderAttributes(): string
```




**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Polygon">Class Polygon</h2>

<pre> Full name: \ASK\Svg\Shapes\Polygon</pre>

A Polygon element in a svg document



      
Class ***Polygon*** inherits from class: ```Shape```


---

### Polygon Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*points*|`array`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Polygon :: constructor </h2>






```php
Polygon (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Polygon :: toHtml </h2>

(overloaded Method from SvgElement)




```php
public toHtml(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Polygon :: getPoints </h2>

get the array Points from a string




```php
public getPoints(string points): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *points* | `string` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Polyline">Class Polyline</h2>

<pre> Full name: \ASK\Svg\Shapes\Polyline</pre>

A Polyline element in a svg document



      
Class ***Polyline*** inherits from class: ```Shape```


---

### Polyline Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*points*|`array`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Polyline :: constructor </h2>






```php
Polyline (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Polyline :: toHtml </h2>

(overloaded Method from SvgElement)




```php
public toHtml(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Polyline :: getPoints </h2>

get the array Points from a string




```php
public getPoints(string points): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *points* | `string` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Rect">Class Rect</h2>

<pre> Full name: \ASK\Svg\Shapes\Rect</pre>

A Rect element in a svg document



      
Class ***Rect*** inherits from class: ```Shape```


---

### Rect Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*x*|`float`||
|```protected```|*y*|`float`||
|```protected```|*width*|`float`||
|```protected```|*height*|`float`||
|```protected```|*rx*|`float`||
|```protected```|*ry*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Rect :: constructor </h2>






```php
Rect (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Rect :: center </h2>

get the Center of the rectangle




```php
public center(): \NumPHP\Core\NumArray
```




**returns** 



---


<h2 style = "color:#aa88cc"> Rect :: area </h2>

get the Area of the rectangle




```php
public area(): float
```




**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Text">Class Text</h2>

<pre> Full name: \ASK\Svg\Shapes\Text</pre>

a Text element in a svg document



      
Class ***Text*** inherits from class: ```Shape```


---

### Text Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Text :: constructor </h2>






```php
Text (string contents, array attributes = [], SvgElement context = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *contents* | `string` |  |
| *attributes* | `array` |  |
| *context* | `SvgElement` |  |



**returns** 



---

</details>


------

<h2 style = "color:#8888AA" id="Command">The abstract class Command</h2>

<pre> Full name: \ASK\Svg\DCommands\Command</pre>

A command in a *d* attribute of a svg path

There are five line commands for &lt;path&gt; nodes.
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
as well as a reference to the previous command.

      
Class ***Command*** implements: ```\Illuminate\Contracts\Support\Htmlable```

### Command Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*nextPoint*|`int`||
|```protected```|*count*|`mixed`||
|```protected```|*type*|`string`||
|```protected```|*coordinates*|`array`||
|```protected```|*prev*|`DCommands\Command`||
|```protected```|*position*|`int`||
|```protected```|*endPointCoordinates*|`array`||
</details>

---

### Command Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Command :: constructor </h2>






```php
Command (string type, array parameters = [], ?DCommands\Command prev = null): mixed
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *type* | `string` |  |
| *parameters* | `array` |  |
| *prev* | `?DCommands\Command` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Command :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Command :: getComand </h2>

getComand

return the name of the command. Uppercase if it's absolute lowercase if relative


```php
public getComand(): string
```




**returns** 



---


<h2 style = "color:#aa88cc"> Command :: setEndPoint </h2>

setEndPoint set the values of the coordinates of the end point in the command list, both Absolute and Relative




```php
public setEndPoint(array relativePoint, array absolutePoint): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *relativePoint* | `array` |  |
| *absolutePoint* | `array` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Command :: toHtml </h2>






```php
public toHtml(): mixed
```




**returns** 



---


<h2 style = "color:#aa88cc"> Command :: getEndPoint </h2>

getEndPoint returns an array with the x and y value of the end point. If the parameter "absolute"
is put to true the Absolute value of the end point is returned, relative is returned otherwise




```php
public getEndPoint(bool absolute = true): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *absolute* | `bool` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Command :: resetNext </h2>

resetNext




```php
public resetNext(): void
```




**returns** 



---


<h2 style = "color:#aa88cc"> Command :: getLastMComand </h2>

getLastMComand returns the last M command in the "d" attribute




```php
public getLastMComand(): DCommands\Command|null
```




**returns** 



---


<h2 style = "color:#aa88cc"> Command :: getPoint </h2>

getPoint returns the array with the x and y parameters of the n point,
if the parameter "absolute" is set to true, the Absolute values are returned,
relative are retuned otherwise




```php
public getPoint(int n = null, bool absolute = false): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *n* | `int` |  |
| *absolute* | `bool` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Command :: getDinstance </h2>

getDinstance get the distance between tow points,
if the second parameter is not gived, returns the Absolut distans of the point




```php
public getDinstance(array fromPoint, array toPoint = []): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *fromPoint* | `array` |  |
| *toPoint* | `array` |  |



**returns** 



---

</details>

---
<h2 style = "color:#8888ff" id="A">Class A</h2>

<pre> Full name: \ASK\Svg\DCommands\A</pre>

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

      
Class ***A*** inherits from class: ```Command```


---

### A Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> A :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> A :: getCenter </h2>

getCenter get the centero of the n arc




```php
public getCenter(int n = null): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *n* | `int` | the arc number of which we want the center |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="C">Class C</h2>

<pre> Full name: \ASK\Svg\DCommands\C</pre>

A comand "c" in a d attribute of a svg path

The cubic curve, C, is the slightly more complex curve.
Cubic Béziers take in two control points for each point.
Therefore, to create a cubic Bézier, three sets of coordinates need to be specified.

C x1 y1, x2 y2, x y
c dx1 dy1, dx2 dy2, dx dy

      
Class ***C*** inherits from class: ```Command```


---

### C Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> C :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="H">Class H</h2>

<pre> Full name: \ASK\Svg\DCommands\H</pre>

A comand "h" in a d attribute of a svg path

A command draws a horizontal line, this command only take
one parameter since they only move in one direction.

H x
h dx

      
Class ***H*** inherits from class: ```Command```


---

### H Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*x*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> H :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> H :: getX </h2>

getX




```php
public getX(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> H :: setX </h2>

setX Set the value of x




```php
public setX(float x): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *x* | `float` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="L">Class L</h2>

<pre> Full name: \ASK\Svg\DCommands\L</pre>

A comand "h" in a d attribute of a svg path

L x y
l dx dy

      
Class ***L*** inherits from class: ```Command```


---

### L Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> L :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="M">Class M</h2>

<pre> Full name: \ASK\Svg\DCommands\M</pre>

A comand "m" in a d attribute of a svg path

M x y
m dx dy

      
Class ***M*** inherits from class: ```Command```


---

### M Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```private```|*x*|`float`||
|```private```|*y*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> M :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> M :: getMDinstance </h2>

getMDinstance get the distance between last m point and the point of parameter




```php
public getMDinstance(array toPoint = []): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *toPoint* | `array` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> M :: getX </h2>

Get the value of x




```php
public getX(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> M :: setX </h2>

Set the value of x




```php
public setX(float x): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *x* | `float` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> M :: getY </h2>

Get the value of y




```php
public getY(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> M :: setY </h2>

Set the value of y




```php
public setY(float y): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *y* | `float` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Q">Class Q</h2>

<pre> Full name: \ASK\Svg\DCommands\Q</pre>

A comand "q" in a d attribute of a svg path

Q x1 y1, x y
q dx1 dy1, dx dy

      
Class ***Q*** inherits from class: ```Command```


---

### Q Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Q :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="S">Class S</h2>

<pre> Full name: \ASK\Svg\DCommands\S</pre>

A comand "s" in a d attribute of a svg path

S x2 y2, x y
s dx2 dy2, dx dy

      
Class ***S*** inherits from class: ```Command```


---

### S Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> S :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="T">Class T</h2>

<pre> Full name: \ASK\Svg\DCommands\T</pre>

A comand "t" in a d attribute of a svg path

T x y
t dx dy

      
Class ***T*** inherits from class: ```Command```


---

### T Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> T :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="V">Class V</h2>

<pre> Full name: \ASK\Svg\DCommands\V</pre>

A comand "v" in a d attribute of a svg path

V y
v dy

      
Class ***V*** inherits from class: ```Command```


---

### V Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
|```protected```|*y*|`float`||
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> V :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> V :: getY </h2>

getY




```php
public getY(): float
```




**returns** 



---


<h2 style = "color:#aa88cc"> V :: setY </h2>

setY




```php
public setY(float y): self
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *y* | `float` |  |



**returns** 



---

</details>


------
<h2 style = "color:#8888ff" id="Z">Class Z</h2>

<pre> Full name: \ASK\Svg\DCommands\Z</pre>

A comand "z" in a d attribute of a svg path



      
Class ***Z*** inherits from class: ```Command```


---

### Z Properties

<details><summary>Class Properties</summary>

| visibility |    Property    |    Type    |    Description    |
|:---|---:|:---|:---|
</details>

---

###  Methods

<details><summary>Class methods</summary>

<h2 style = "color:#aa88cc"> Z :: initialization </h2>

initialization is a configuration method for the specific type of command




```php
public initialization(mixed parameters): void
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *parameters* | `mixed` |  |



**returns** 



---


<h2 style = "color:#aa88cc"> Z :: getPoint </h2>

getPoint returns the array with the x and y parameters of the n point,
if the parameter "absolute" is set to true, the Absolute values are returned,
relative are retuned otherwise




```php
public getPoint(mixed n = null, mixed absolute = true): array
```



| Parameter | Type | Description |
|-----------|------|-------------|
| *n* | `mixed` |  |
| *absolute* | `mixed` |  |



**returns** 



---

</details>


---
## alphabetical listing 

* [Svg - the Svg document](#Svg).
* [SvgElement - # An element belonging to a svg structure](#SvgElement).
* [Shape - An element that make a Shape in a svg document](#Shape).
	- [Circle - A Circle element in a svg document](#Circle).
	- [Ellipse - A Ellipse element in a svg document](#Ellipse).
	- [Line - A Line element in a svg document](#Line).
	- [Path - A Path element in a svg document](#Path).
	- [Polygon - A Polygon element in a svg document](#Polygon).
	- [Polyline - A Polyline element in a svg document](#Polyline).
	- [Rect - A Rect element in a svg document](#Rect).
	- [Text - a Text element in a svg document](#Text).
* [Command - A command in a *d* attribute of a svg path](#Command).
	- [A - A comand "a" in a d attribute of a svg path](#A).
	- [C - A comand "c" in a d attribute of a svg path](#C).
	- [H - A comand "h" in a d attribute of a svg path](#H).
	- [L - A comand "h" in a d attribute of a svg path](#L).
	- [M - A comand "m" in a d attribute of a svg path](#M).
	- [Q - A comand "q" in a d attribute of a svg path](#Q).
	- [S - A comand "s" in a d attribute of a svg path](#S).
	- [T - A comand "t" in a d attribute of a svg path](#T).
	- [V - A comand "v" in a d attribute of a svg path](#V).
	- [Z - A comand "z" in a d attribute of a svg path](#Z).

---
> This file is public domain. Use it for any purpose, including commercial
> applications. Attribution would be nice, but is not required. There is
> no warranty of any kind, including its correctness, usefulness, or safety.

> **Author: Alberto Solorzano Kraemer ( alberto.kraemer@gmail.com, @betobetok )**
---
> This document was automatically generated from source code comments 
> on 2022-03-25 using [phpDocumentor](http://www.phpdoc.org/) 
> and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
