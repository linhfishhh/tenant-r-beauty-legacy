<?php

namespace Modules\ModHairWorld\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class APITokenCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(AccessTokenCreated $event)
    {
        $name = Token::getQuery()->where('id', $event->tokenId)->get(['name']);
        \Log::info($event->tokenId);
    }
}
