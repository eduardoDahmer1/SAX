@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
@php
    $excludedCategories = [
        'Sector Rh', 'Patrimonio', 'Material Operacional', 'Servicos', 'Audio Phone Consignado',
        'Aurora Consignado', 'Repuestos', 'Zzelectronicos', 'Zzperfumeria', 'Zzvacio2', 
        'Zzropas Masculinas', 'Propaganda', 'Electronicos', 'Cavalo', 'Maquinarias', 'Boss Green',
        'Boss Orange', 'Boss Accesories', 'Boss Smart Casual(Spw)', 'Boss Cdf(Clothing&Dress Furnis',
        'Boss Athleisure','Boss Men Camel', 'Chocolate', 'Cavalheiro', 'Hugo', 'Hugo Men Blue',
        'Hugowomen', 'Relojes', 'Boss Hugo Men', 'Boss Men Business', 'Boss Women Orange', 'Hugo Women Blue',
        'Hugo Women Red','Boss Women', 'Gift Cards'
    ];
@endphp
<div class="category-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-white">
                    @foreach($allCategories as $category)
                        @if(!in_array($category->name, $excludedCategories))
                        <div class="sub-category-menu">
                            <h3 class="category-name">
                                <a href="{{ $category->link ?? route('front.category',$category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </h3>
                            @if(count($category->subs) > 0)
                            <ul class="parent-category">
                                @foreach($category->subs as $subcat)
                                    <li>
                                        <a class="p-c-title"
                                            href="{{ route('front.subcat',['slug1' => $subcat->category->slug, 'slug2' => $subcat->slug]) }}">
                                            {{$subcat->name}}
                                        </a>
                                        @if(count($subcat->childs) > 0)
                                        <ul>
                                            @foreach($subcat->childs as $childcat)
                                            <li>
                                                <a class="sub-filha"
                                                    href="{{ route('front.childcat',['slug1' => $childcat->subcategory->category->slug, 'slug2' => $childcat->subcategory->slug, 'slug3' => $childcat->slug]) }}">
                                                    <i class="fas fa-angle-double-right"></i>{{$childcat->name}}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
