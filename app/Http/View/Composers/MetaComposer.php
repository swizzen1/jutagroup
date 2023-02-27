<?php

namespace App\Http\View\Composers;

use App\Repositories\UserRepository;
use Illuminate\View\View;

class MetaComposer
{

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (request()->segment(2)) {
            $metaTitle = str_replace('-', ' ', request()->segment(2));
        } else {
            $metaTitle = 'index';
        }

        if (isset($metaTitle)) {
            $view->with('metaTitle', $metaTitle);
        }
    }
}
