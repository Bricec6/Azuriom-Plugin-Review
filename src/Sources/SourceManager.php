<?php

namespace Azuriom\Plugin\Review\Sources;

class SourceManager
{
    /**
     * The listings which can be configured as a review source.
     *
     * @var array<string, \Azuriom\Plugin\Review\Sources\ReviewSource>
     */
    private array $sources = [];

    public function __construct()
    {
        $this->register(new ServeurListeSource());
        $this->register(new TopServeursSource());
        $this->register(new ServeurPriveSource());
        $this->register(new ServeurMinecraftSource());
    }

    public function register(ReviewSource $source): void
    {
        $this->sources[$source->domain()] = $source;
    }

    /**
     * @return array<string, \Azuriom\Plugin\Review\Sources\ReviewSource>
     */
    public function all(): array
    {
        return $this->sources;
    }

    public function get(string $domain): ?ReviewSource
    {
        return $this->sources[$domain] ?? null;
    }

    /**
     * Get the sources which are configured to be imported.
     *
     * @return array<string, \Azuriom\Plugin\Review\Sources\ReviewSource>
     */
    public function enabled(): array
    {
        return array_filter($this->sources, fn (ReviewSource $source) => $source->isEnabled());
    }
}
