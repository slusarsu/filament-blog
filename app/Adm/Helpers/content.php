<?php

use App\Adm\Services\PageService;
use App\Adm\Services\PostService;

function admLocales(): array
{
    return config('adm.locales');
}
function admLanguages(): array
{
    $languages = [];

    foreach (admLocales() as $key => $locale) {
        $languages[$key] = $locale['native'];
    }

    return $languages;
}

function admPageBySlug(string $slug = '') {

    if(empty($slug)) {
        $slug = request()->path();
    }

    return resolve(PageService::class)->oneBySlug($slug);
}

function admPageById($id) {
    return resolve(PageService::class)->oneById($id);
}

function admAllPosts(?int $paginateCount = 0, ?array $params = []) {
    return resolve(PostService::class)->getAll($paginateCount, $params);
}

function admPostBySlug($slug) {
    return resolve(PostService::class)->oneBySlug($slug);
}

function admPostById($id) {
    return resolve(PostService::class)->oneById($id);
}


