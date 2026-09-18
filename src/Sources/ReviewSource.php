<?php

namespace Azuriom\Plugin\Review\Sources;

use Azuriom\Plugin\Vote\Models\Site as VoteSite;
use Carbon\Carbon;

abstract class ReviewSource
{
    /**
     * The domain of the listing, without www.
     */
    abstract public function domain(): string;

    /**
     * The display name of the listing.
     */
    abstract public function name(): string;

    /**
     * Determine whether this listing exposes an API to retrieve its reviews.
     */
    public function isSupported(): bool
    {
        return true;
    }

    /**
     * The reason why the reviews of this listing can't be imported.
     */
    public function unsupportedReason(): ?string
    {
        return null;
    }

    /**
     * Retrieve all the reviews currently published on this listing.
     *
     * @return array<int, \Azuriom\Plugin\Review\Sources\ExternalReview>
     *
     * @throws \Exception
     */
    public function fetch(string $token): array
    {
        return [];
    }

    /**
     * Get the API token of this listing, either the one configured in this
     * plugin or, when empty, the one already configured in the Vote plugin.
     */
    public function token(): ?string
    {
        return setting($this->settingKey('token')) ?: $this->voteToken();
    }

    /**
     * Get the API token configured for this listing in the Vote plugin.
     */
    public function voteToken(): ?string
    {
        return $this->voteSite()?->verification_key;
    }

    /**
     * Get the vote site of this listing when the Vote plugin is installed.
     */
    public function voteSite(): ?VoteSite
    {
        if (! plugins()->isEnabled('vote') || ! class_exists(VoteSite::class)) {
            return null;
        }

        return once(fn () => VoteSite::where('url', 'like', '%'.$this->domain().'%')
            ->whereNotNull('verification_key')
            ->where('verification_key', '<>', '')
            ->orderByDesc('is_enabled')
            ->first());
    }

    /**
     * Determine whether reviews should be imported from this listing.
     */
    public function isEnabled(): bool
    {
        return $this->isSupported()
            && setting($this->settingKey('enabled'))
            && $this->token() !== null;
    }

    /**
     * The url of the server page on this listing, displayed on each imported review.
     */
    public function listingUrl(): string
    {
        $url = $this->voteSite()?->url;

        if ($url === null) {
            return 'https://'.$this->domain();
        }

        $parts = parse_url($url);

        return ($parts['scheme'] ?? 'https').'://'.$parts['host'].($parts['path'] ?? '');
    }

    public function lastSyncAt(): ?Carbon
    {
        $date = setting($this->settingKey('synced_at'));

        return $date !== null ? Carbon::parse($date) : null;
    }

    public function lastError(): ?string
    {
        return setting($this->settingKey('error'));
    }

    public function settingKey(string $key): string
    {
        return "review.sources.{$this->domain()}.{$key}";
    }
}
