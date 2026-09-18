<?php

namespace Azuriom\Plugin\Review\Sources;

class ServeurPriveSource extends ReviewSource
{
    public function domain(): string
    {
        return 'serveur-prive.net';
    }

    public function name(): string
    {
        return 'Serveur-Privé';
    }

    public function isSupported(): bool
    {
        return false;
    }

    public function unsupportedReason(): string
    {
        return trans('review::admin.sources.unsupported.serveur-prive');
    }
}
