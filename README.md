# Colorium Runtime Resolver

- Read docblock annotations
- Resolve any callable into a formalized `Resource` instance
- Inject custom data/factory into callable parameters

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

$resource = Resolver::of('Some::thing');
if(!$resource){
    // not a valid callable
}

$resource->isStaticMethod(); // false
$resource->isClassMethod(); // true
$resource->isClosure(); // false

$foo = $resource->annotation('foo'); // 'bar'
$annotations = $resource->annotations(); // ['foo' => 'bar']

echo $resource->call('You'); // Hello You !
echo $resource('You'); // Hello You !
```


## Injectors

Todo