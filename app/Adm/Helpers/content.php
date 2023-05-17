<?php

use App\Adm\Services\PageService;
use App\Adm\Services\PostService;

function admPageBySlug($slug) {
    return resolve(PageService::class)->getPageBySlug($slug);
}

function admPageById($id) {
    return resolve(PageService::class)->getPageById($id);
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


