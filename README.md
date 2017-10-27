Aquaduck
========

This package is heavily inspired by zendframework/zend-stratigility [zendframework/zend-stratigility](https://github.com/zendframework/zend-stratigility).

Installation and Requirements
-----------------------------

Install this library using composer:

```console
$ composer require webpt/aquaduck
```

Usage
-----

Creating a processing pipeline is trivially easy:

```php
use Webpt\Aquaduck\Aquaduck;

require __DIR__ . '/../vendor/autoload.php';

$pipeline = new Aquaduck();

$pipeline->bind(function($value, $next) {
    $next($value + 5);
});

echo $pipeline(5); //10
```

Middleware
----------

What is middleware?

Middleware is code that exists which can take the incoming value (or object), perform actions based on it, and either return or pass delegation on to the next middleware in the queue.

Within Aquaduck, middleware can be:

- Any PHP callable that accepts, minimally, a single argument, and, optionally, a callable (for invoking the next middleware in the queue, if any).
- An object implementing `Webpt\Aquaduck\Middleware\MiddlewareInterface`. `Webpt\Aquaduck\Aquaduck` implements this interface.

Error Handlers
--------------

To handle errors, you can write middleware that accepts **exactly** 3 arguments:

```php
function ($error, $subject, $next) { }
```

Alternately, you can implement `Webpt\Aquaduck\ErrorHandler\ErrorHandlerInterface`.

When using `Aquaduck`, as the queue is executed, if `$next()` is called with an argument, or if an exception is
thrown, middleware will iterate through the queue until the first such error handler is found. That error handler can
either complete the request, or itself call `$next()`. **Error handlers that call `$next()` SHOULD call it with the
error it received itself, or with another error.**

Error handlers are usually attached at the end of middleware, to prevent attempts at executing non-error-handling
middleware, and to ensure they can intercept errors from any other handlers.

Creating Middleware
-------------------

To create middleware, write a callable capable of receiving minimally a single argument, and optionally
a callback to call the next in the chain. If your middleware accepts a second argument, `$next`, if it is
unable to complete the request, or allows further processing, it can call it to return handling to the parent
middleware.

Middleware written in this way can be any of the following:

- Closures (as shown above)
- Functions
- Static class methods
- PHP array callbacks (e.g., `[ $class, 'run' ]`, where `$class` is a class instance)
- Invokable PHP objects (i.e., instances of classes implementing `__invoke()`)
- Objects implementing `Aquaduck\Middleware\MiddlewareInterface` (including `Webpt\Aquaduck\Aquaduck`)


Executing and composing middleware
----------------------------------

The easiest way to execute middleware is to write closures and attach them to a `Webpt\Aquaduck\Aquaduck` instance. You
can nest `Aquaduck` instances to create groups of related middleware.

```php
$pipe = new Aquaduck();       // Middleware collection
$pipe->bind(/* ... */);       // repeat as necessary

$superPipe = new Aquaduck();  // New Middleware collection
$superPipe->bind($pipe);      // Middleware attached as a group
```

API
---

The following make up the primary API of Aquaduck.

### Middleware

`Webpt\Aquaduck\Aquaduck` is the primary application interface, and has been discussed previously. Its API is:

```php
class Aquaduck implements MiddlewareInterface
{
    public function bind($middleware, $priority = 1);
    public function __invoke(
        $subject,
        $out = null
    );
}
```

`bind()` takes up to two arguments. If only one argument is provided, `$middleware` will be assigned that value, and
`$priority` will be assigned to the value `1`.

Middleware is executed in the order in which `$priority` is determined.

`__invoke()` is itself middleware. If `$out` is not provided, an instance of
`Webpt\Aquaduck\FinalHandler` will be created, and used in the event that the pipe
stack is exhausted. The callable should use the same signature as `Next()`:

```php
function (
    $subject,
    $err = null
) {
}
```

Internally, `Aquaduck` creates an instance of `Webpt\Aquaduck\Next`, feeding it its queue, executes it, and returns a response.

### Next

`Webpt\Aquaduck\Next` is primarily an implementation detail of middleware, and exists to allow delegating to middleware registered later in the stack.

```php
class Next
{
    public function __invoke(
        $subject,
        $err = null
    );
}
```

As examples:

#### Providing an altered subject:

```php
function ($subject, $next) use ($helperClass)
{
    $subject = $helperClass->help($subject);
    return $next($subject);
}
```

#### Raising an error condition

To raise an error condition, pass a non-null value as the second argument to `$next()`:

```php
function ($subject, $next)
{
    try {
        // try some operation...
    } catch (Exception $e) {
        return $next($subject, $e); // Next registered error middleware will be invoked
    }
}
```

### FinalHandler

`Webpt\Aquaduck\FinalHandler` is a default implementation of middleware to execute when the stack exhausts itself. It
expects two arguments when invoked: a subject, and an error condition (or `null` for no error).
