<?php

namespace CrCms\Passport\Attributes;

use Illuminate\Support\Arr;

/**
 * Class AbstractAttribute
 * @package CrCms\Passport\Attributes
 */
abstract class AbstractAttribute
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var static
     */
    protected static $instance;

    /**
     * AbstractAttributeContract constructor.
     */
    protected function __construct()
    {
        $this->attributes = $this->attributes();
    }

    /**
     * @return array
     */
    abstract protected function attributes(): array;

    /**
     * @return array
     */
    public static function getAttributes(): array
    {
        return static::instance()->attributes;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public static function getTransform(string $key, $default = null)
    {
        return Arr::get(static::getAttributes(), $key, $default);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public static function getStaticTransform(string $key, $default = null)
    {
        return static::getTransform($key, $default);
    }

    /**
     * @return array
     */
    public static function getStaticAttributes(): array
    {
        return static::getAttributes();
    }

    /**
     * @return AbstractAttribute
     */
    protected static function instance(): self
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }

        static::$instance = new static;

        return static::$instance;
    }
}