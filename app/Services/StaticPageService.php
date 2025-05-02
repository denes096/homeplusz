<?php

namespace App\Services;

use App\Models\StaticPage;

class StaticPageService
{
    public function getStaticPageBySlug($slug): StaticPage
    {
        return StaticPage::where('slug', $slug)->firstOrFail();
    }
}
