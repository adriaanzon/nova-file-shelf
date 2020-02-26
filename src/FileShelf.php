<?php

namespace NovaFileShelf;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Nova\Card;

class FileShelf extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    public string $title;

    public string $key;

    public $helpText;

    protected $manipulator;

    public function __construct($title, $key = null)
    {
        $this->title = $title;
        $this->key = $key ?? Str::kebab($title);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-file-shelf';
    }

    public function manipulateUsing(string $invokable): self
    {
        if (! class_exists($invokable) || ! is_callable(new $invokable)) {
            throw new InvalidArgumentException('Manipulator must be an invokable class');
        }

        $this->manipulator = $invokable;

        return $this;
    }

    public function help(?string $helpText): self
    {
        $this->helpText = $helpText;

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge([
            'manipulator' => $this->manipulator,
            'title' => $this->title,
            'key' => $this->key,
            'helpText' => $this->helpText,
        ], parent::jsonSerialize());
    }
}
