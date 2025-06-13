<?php

namespace Kompo\Core;

use Illuminate\Support\Arr;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionClass;

class DependencyResolver
{
    /**
     * Calls one of the Komponent's method with Dependency injection.
     *
     * @param Komponent $komponent
     * @param string   $method
     * @param array    $specialParams
     *
     * @return mixed
     */
    public static function callKomponentMethod($komponent, $method, $specialParams = [], $force = false)
    {
        if (!$force) {
            AuthorizationGuard::selfMethodGate($komponent, $method);
        }

        $reflectionMethod = new ReflectionMethod($komponent, $method);

        return $komponent->{$method}(...static::resolveMethodDependencies($specialParams, $reflectionMethod));
    }

    /*
     * FROM LARAVEL: RouteDependencyResolverTrait
     */

    /**
     * Resolve the given method's type-hinted dependencies.
     *
     * @param array                       $parameters
     * @param \ReflectionFunctionAbstract $reflector
     *
     * @return array
     */
    protected static function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector)
    {
        $instanceCount = 0;

        $values = array_values($parameters);

        foreach ($reflector->getParameters() as $key => $parameter) {
            $instance = static::transformDependency(
                $parameter,
                $parameters
            );

            if (!is_null($instance)) {
                $instanceCount++;

                static::spliceIntoParameters($parameters, $key, $instance);
            } elseif (!isset($values[$key - $instanceCount]) &&
                      $parameter->isDefaultValueAvailable()) {
                static::spliceIntoParameters($parameters, $key, $parameter->getDefaultValue());
            }
        }

        return array_values($parameters);
    }

    /**
     * Attempt to transform the given parameter into a class instance.
     *
     * @param \ReflectionParameter $parameter
     * @param array                $parameters
     *
     * @return mixed
     */
    protected static function transformDependency(ReflectionParameter $parameter, $parameters)
    {
        $class = $parameter->getType() && !$parameter->getType()->isBuiltin()
           ? new ReflectionClass($parameter->getType()->getName())
           : null;

        // If the parameter has a type-hinted class, we will check to see if it is already in
        // the list of parameters. If it is we will just skip it as it is probably a model
        // binding and we do not want to mess with those; otherwise, we resolve it here.
        if ($class && !static::alreadyInParameters($class->name, $parameters)) {
            return $parameter->isDefaultValueAvailable()
                ? $parameter->getDefaultValue()
                : app()->make($class->name);
        }
    }

    /**
     * Determine if an object of the given class is in a list of parameters.
     *
     * @param string $class
     * @param array  $parameters
     *
     * @return bool
     */
    protected static function alreadyInParameters($class, array $parameters)
    {
        return !is_null(Arr::first($parameters, function ($value) use ($class) {
            return $value instanceof $class;
        }));
    }

    /**
     * Splice the given value into the parameter list.
     *
     * @param array  $parameters
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    protected static function spliceIntoParameters(array &$parameters, $offset, $value)
    {
        array_splice(
            $parameters,
            $offset,
            0,
            [$value]
        );
    }
}
