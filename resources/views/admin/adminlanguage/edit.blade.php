@extends('layouts.admin')

@section('styles')
<style>textarea.input-field { padding: 20px 20px 0; border-radius: 0; }</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Edit Language') }}
                    <a class="add-btn" href="{{ route('admin-tlang-index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                </h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-tlang-index') }}">{{ __('Admin Panel Language') }}</a></li>
                    <li><a href="#">{{ __('Edit') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover" style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat center center rgba(45, 45, 45, 0.5);"></div>
                        <form id="geniusform" action="{{ route('admin-tlang-update', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <p><small>* {{ __('indicates a required field') }}</small></p>
                            @include('includes.admin.form-both')

                            <div class="row">
                                <div class="col-xl-4">
                                    <input type="text" class="input-field" name="language" placeholder="{{ __('Language') }}" required value="{{ $data->language }}">
                                </div>
                                <div class="col-xl-4">
                                    <input type="text" class="input-field" name="locale" placeholder="{{ __('en') }}" required value="{{ $data->name }}">
                                </div>
                                <div class="col-xl-4">
                                    <select name="rtl" class="input-field" required>
                                        <option value="0" {{ $data->rtl == '0' ? 'selected' : '' }}>{{ __('Left To Right') }}</option>
                                        <option value="1" {{ $data->rtl == '1' ? 'selected' : '' }}>{{ __('Right To Left') }}</option>
                                    </select>
                                </div>
                            </div>

                            <h4 class="text-center">{{ __('FIELDS') }}</h4><hr>

                            <div class="table-responsive">
                                <table id="geniustable" class="table table-hover dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Original') }}</th>
                                            <th>{{ __('Translation') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($langEdit as $key => $val)
                                            @continue(is_numeric($key))
                                            <tr>
                                                <td>{{ $key }}<input type="hidden" name="keys[]" value="{{ $key }}"></td>
                                                <td><input type="text" class="input-field" name="values[]" value="{{ !is_numeric($val) ? $val : '' }}"></td>
                                                <td>{{ !is_numeric($val) ? $val : '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row justify-content-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        columnDefs: [
            { targets: [1], searchable: false },
            { targets: [2], visible: false, searchable: true }
        ],
        language: {
            url: '{{ $datatable_translation }}',
            processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
        },
        initComplete: function() {
            $(".btn-area").append(`
                <div class="col-sm-4 table-contents">
                    <a class="add-btn" id="btn-empty-search">{{ __('Show Empty') }}</a>
                    <a class="add-btn" id="btn-all-search" style="display: none;">{{ __('Show All') }}</a>
                </div>`);
            
            $('#btn-empty-search').click(function() {
                table.column(2).search("^$", true, false).draw();
                $(this).hide(); $('#btn-all-search').show();
            });

            $('#btn-all-search').click(function() {
                table.column(2).search("").draw();
                $(this).hide(); $('#btn-empty-search').show();
            });
        }
    });
</script>
@endsection
