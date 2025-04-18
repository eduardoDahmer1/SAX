@extends('layouts.load')

@section('content')
<div class="content-area" id="app">
    <div class="add-product-content">
        <div class="product-description">
            <div class="body-area">
                @include('includes.admin.form-error')

                <form id="geniusformdata" action="{{ route('admin-attr-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="category_id" value="{{ $data->id }}">

                    <div class="input-form">
                        <p><small>* {{ __("indicates a required field") }}</small></p>
                        @component('admin.components.input-localized', ["required" => true])
                            @slot('name') name @endslot
                            @slot('placeholder') {{ __('Enter Name') }} @endslot
                            {{ __('Name') }} *
                        @endcomponent
                    </div>

                    <div class="input-form" v-if="counter > 0" id="optionarea">
                        <div class="mb-3 counterrow" v-for="n in counter" :id="'counterrow'+n">
                            <div class="panel panel-lang">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" :id="'{{$lang->locale}}-optionfield'+n">
                                            <input type="text" class="input-field" name="{{$lang->locale}}[options][]" placeholder="{{__('Option label')}}" required>
                                            <button type="button" class="btn btn-danger text-white" @click="removeOption(n)"><i class="fa fa-times"></i></button>
                                        </div>
                                        @foreach ($locales as $loc)
                                            @if ($loc->locale !== $lang->locale)
                                                <div class="tab-pane" :id="'{{$loc->locale}}-optionfield'+n">
                                                    <input type="text" class="input-field" name="{{$loc->locale}}[options][]" placeholder="{{__('Option label')}}">
                                                    <button type="button" class="btn btn-danger text-white" @click="removeOption(n)"><i class="fa fa-times"></i></button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <ul class="nav nav-pills">
                                        <li class="active"><a :href="'#{{$lang->locale}}-optionfield'+n">{{$lang->language}}</a></li>
                                        @foreach ($locales as $loc)
                                            @if ($loc->locale !== $lang->locale)
                                                <li><a :href="'#{{$loc->locale}}-optionfield'+n">{{$loc->language}}</a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            @if($gs->is_attr_cards)
                            <div class="panel panel-lang">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" :id="'{{$lang->locale}}-descriptionfield'+n">
                                            <input type="text" class="input-field" name="{{$lang->locale}}[description][]" placeholder="{{__('Description')}}">
                                        </div>
                                        @foreach ($locales as $loc)
                                            @if ($loc->locale !== $lang->locale)
                                                <div class="tab-pane" :id="'{{$loc->locale}}-descriptionfield'+n">
                                                    <input type="text" class="input-field" name="{{$loc->locale}}[description][]" placeholder="{{__('Description')}}">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <ul class="nav nav-pills">
                                        <li class="active"><a :href="'#{{$lang->locale}}-descriptionfield'+n">{{$lang->language}}</a></li>
                                        @foreach ($locales as $loc)
                                            @if ($loc->locale !== $lang->locale)
                                                <li><a :href="'#{{$loc->locale}}-descriptionfield'+n">{{$loc->language}}</a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-success text-white" @click="addOption()">
                                <i class="fa fa-plus"></i> {{ __('Add Option') }}
                            </button>
                        </div>
                    </div>

                    <div class="input-form">
                        <label><input type="checkbox" name="price_status" checked value="1"> {{ __('Allow Price Field') }}</label><br>
                        <label><input type="checkbox" name="show_price" checked value="1"> {{ __('Show Price') }}</label><br>
                        <label><input type="checkbox" name="details_status" checked value="1"> {{ __('Show on Details Page') }}</label>
                    </div>

                    <div class="text-center">
                        <button class="addProductSubmit-btn" type="submit">{{ __('Create Attribute') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
new Vue({
    el: '#app',
    data: { counter: 1 },
    methods: {
        addOption() {
            document.getElementById("optionarea").classList.add('d-block');
            this.counter++;
        },
        removeOption(n) {
            const row = document.getElementById('counterrow' + n);
            if (row) row.remove();
            if (document.querySelectorAll('.counterrow').length === 0) this.counter = 0;
        }
    }
});
</script>
@endsection
