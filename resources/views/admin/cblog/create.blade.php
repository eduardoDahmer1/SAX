@extends('layouts.load')
@section('content')
<div class="content-area">
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area">
						@include('includes.admin.form-error')
						<form id="geniusformdata" action="{{ route('admin-cblog-create') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<div class="input-form mb-3">
								<p><small>* {{ __("indicates a required field") }}</small></p>
							</div>
							<div class="input-form mb-4">
								@component('admin.components.input-localized', ['required' => true])
									@slot('name') name @endslot
									@slot('placeholder') {{ __('Name') }} @endslot
									{{ __('Name') }} *
								@endcomponent
							</div>
							<div class="text-center">
								<button class="addProductSubmit-btn" type="submit">{{ __('Create Category') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
