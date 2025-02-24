<?php

namespace App\View\Components\Header;

use App\Models\Category;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class Theme15 extends Component
{
    public $nav_categories;

    public function __construct()
    {
        // Verifica se já existe um cache para as categorias de navegação
        $this->nav_categories = Cache::remember('nav_categories', now()->addMinutes(30), function () {
            return Category::with(['products' => function ($query) {
                $query->where('show_in_navbar', 1);
            }])->get();
        });
    }

    public function render()
    {
        return view('front.themes.theme-15.components.header');
    }
}
