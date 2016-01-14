# Callable resolver and annotation parser

- Read docblock annotations
- Resolve any callable into a formalized `Invokable` instance

Exemple :

```php
/**
 * @top notch
 */
class Some
{

    /**
     * @var int
     */
    protected $prop;

    /**
     * @foo bar
     * @param string $name
     */
    public function thing($name)
    {
        return 'Hello ' . $name . ' !';
    }
}

/**
 * @hell o
 */
 function hello()
 {
    return 'Hello !';
 }
```


## Annotation

Read any meta tag existing in docblock.

```php
use Colorium\Runtime\Annotation;

$annotations = Annotation::ofClass('Some'); // ['top' => 'notch']
$top = Annotation::ofClass('Some', 'top'); // 'notch'

$annotations = Annotation::ofProperty('Some', 'prop'); // ['var' => 'int']
$var = Annotation::ofProperty('Some', 'prop', 'var'); // 'int'

$annotations = Annotation::ofMethod('Some', 'thing'); // ['foo' => 'bar', 'param' => 'string $name']
$foo = Annotation::ofMethod('Some', 'thing', 'foo'); // 'bar'

$annotations = Annotation::ofFunction('hello'); // ['hell' => 'o']
$hell = Annotation::ofFunction('hello', 'hell'); // 'o'
```

The value is automaticaly and recursively casted as `string`, `int`, `float`, `bool`, `array` or `object`.

```php
/**
 * @key -> null (but exists as annotation)
 * @key true -> bool
 * @key false -> bool
 * @key 10 -> int
 * @key 10.1 -> float
 * @key [10, 10] -> array (inner values are casted as well)
 * @key {foo: bar} -> stdClass with property foo set as 'bar'
 * @key othervalue -> string
 */
```


## Resolver

Translate any valid callable into a resource instance. Callable can be :
- an user closure: `function(){}`
- an existing function name: `'function_name_that_exists'`
- a class-method couple name: `'Classname::method'`
- a class-method couple array name: `['Classname', 'method']`
- a class-method couple array instance: `[$instance, 'method']`
- an invokable class name: `'Classname'` (same as `Classname::__invoke`)
- an invokable class instance: `$instance` (same as `[$instance, '__invoke']`)

```php
use Colorium\Runtime\Resolver;

$invokable = Resolver::of('Some::thing');
if(!$invokable){
    // not a valid callable
}

$invokable->isStaticMethod(); // false
$invokable->isClassMethod(); // true
$invokable->isClosure(); // false

$foo = $invokable->annotation('foo'); // 'bar'
$annotations = $invokable->annotations(); // ['foo' => 'bar']

echo $invokable->call('You'); // Hello You !
echo $invokable('You'); // Hello You !
```

## Install

`composer require colorium/runtime`