@extends('layouts.load')
@section('content')
<div class="content-area">
	<div class="add-product-content">
		<div class="product-description">
			<div class="body-area">
				@include('includes.admin.form-error')
				<form id="geniusformdata" action="{{ route('admin-cblog-update', $data->id) }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="input-form mb-3">
						<p><small>* {{ __("indicates a required field") }}</small></p>
					</div>
					<div class="input-form mb-4">
						@component('admin.components.input-localized', ['required' => true, 'from' => $data])
							@slot('name') name @endslot
							@slot('placeholder') {{ __('Name') }} @endslot
							@slot('value') name @endslot
							{{ __('Name') }} *
						@endcomponent
					</div>
					<div class="d-flex justify-content-end">
						<button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
