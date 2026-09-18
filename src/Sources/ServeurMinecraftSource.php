<?php

namespace Azuriom\Plugin\Review\Sources;

class ServeurMinecraftSource extends ReviewSource
{
    public function domain(): string
    {
        return 'serveur-minecraft.com';
    }

    public function name(): string
    {
        return 'Serveur-Minecraft';
    }

    public function isSupported(): bool
    {
        return false;
    }

    public function unsupportedReason(): string
    {
        return trans('review::admin.sources.unsupported.serveur-minecraft');
    }
}
