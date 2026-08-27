<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\CarouselSlide;
use App\Models\Department;
use App\Models\Event;
use App\Models\LeaderAssignment;
use App\Models\Post;
use App\Models\SiteSetting;

final class HomeController extends Controller
{
    public function index(Request $request): void
    {
        try {
            $slides = CarouselSlide::active();
        } catch (\Throwable) {
            $slides = [];
        }

        if ($slides === []) {
            $slides = [[
                'title'      => 'Union du Congo Ouest',
                'subtitle'   => SiteSetting::get('site_tagline', 'Adventistes du 7e Jour — Kinshasa'),
                'image_path' => '/assets/img/adventist-church-hero.svg',
                'link_url'   => '/pages/mission',
                'link_label' => 'Notre mission',
            ]];
        }

        $this->view('home/index', [
            'title'       => SiteSetting::get('site_name', 'UCO'),
            'tagline'     => SiteSetting::get('site_tagline', ''),
            'slides'      => $slides,
            'posts'       => Post::published(3),
            'events'      => Event::upcoming(3),
            'departments' => Department::active(),
            'leaders'     => LeaderAssignment::currentForScope('union'),
        ]);
    }
}
