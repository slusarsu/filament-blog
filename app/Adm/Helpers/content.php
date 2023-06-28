<?php

use App\Adm\Services\PageService;
use App\Adm\Services\PostService;

function admPageBySlug(string $slug = '') {

    if(empty($slug)) {
        $slug = request()->path();
    }

    $page = resolve(PageService::class)->oneBySlug($slug);

    if(!$page) {
        return redirect()->route('home', admLocale());
    }

    return $page;
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

function admPostTypes() {
    $types = config('adm.post_types');
    $templateSettings = templateSettings();

    if(!empty($templateSettings['post_types'])) {
        $types = [...$types, ...$templateSettings['post_types']];
    }

    return $types;
}

function test($data) {
    dd($data);
}

