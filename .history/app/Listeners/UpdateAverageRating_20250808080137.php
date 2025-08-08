<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateAverageRating
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReviewCreated $event): void
    {

        // // Adicione esta linha temporariamente para depuração
        // dd('Listener Reached!');

        $reviewable = $event->review->reviewable;

        // Adicione esta linha temporariamente para depuração
        dd($reviewable);

        $reviewable->update([
            'average_rating' => $reviewable->reviews->avg('rating'),
        ]);
    }
}
