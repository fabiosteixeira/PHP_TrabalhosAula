<?php

namespace App\Engine;

class Registry
{
    private $engines = [];

    /**
     * Registry constructor.
     * @param array $engines
     */
    public function __construct(array $engines)
    {
        foreach ($engines as $engine) {
            $this->add($engine);
        }
    }

    public function add(EngineInterface $engine): void
    {
        $this->engines[$engine::getName()] = $engine;
    }

    public function get(string $name): EngineInterface
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException('There is no engine named with "' . $name . '"');
        }
        return $this->engines[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->engines[$name]);
    }

    public function all(): array
    {
        return $this->engines;
    }
}