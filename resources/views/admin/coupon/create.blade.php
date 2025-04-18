@extends('layouts.load')
@section('styles')
<link href="{{asset('assets/admin/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="content-area">
  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-coupon-create')}}" method="POST" enctype="multipart/form-data">
              {{csrf_field()}}
              <div class="row"><div class="col-xl-12"><div class="input-form"><p><small>* {{ __("indicates a required field") }}</small></p></div></div></div>
              <div class="row">
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('Code') }} *</h4><input type="text" class="input-field" name="code" placeholder="{{ __('Enter Code') }}" required></div></div>
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('Type') }} *</h4><select id="type" name="type" required><option value="">{{ __('Choose a type') }}</option><option value="0">{{ __('Discount By Percentage') }}</option><option value="1">{{ __('Discount By Amount') }}</option></select></div></div>
                <div class="col-xl-4 hidden"><div class="input-form"><h4 class="heading"></h4><input type="text" class="input-field less-width" name="price" placeholder="" required></div></div>
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('Quantity') }} *</h4><select id="times" required><option value="0">{{ __('Unlimited') }}</option><option value="1">{{ __('Limited') }}</option></select></div></div>
                <div class="col-xl-4 hidden"><div class="input-form"><h4 class="heading">{{ __('Value') }} *</h4><input type="text" class="input-field less-width" name="times" placeholder="{{ __('Enter Value') }}"></div></div>
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('Minimum Value') }}</h4><input type="text" class="input-field less-width" name="minimum_value" placeholder="{{ __('Enter minimum value') }}"></div></div>
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('Maximum Value') }}</h4><input type="text" class="input-field less-width" name="maximum_value" placeholder="{{ __('Enter minimum value') }}"></div></div>
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('Start Date') }} *</h4><input type="text" class="input-field" name="start_date" id="from" placeholder="{{ __('Select a date') }}" required></div></div>
                <div class="col-xl-4"><div class="input-form"><h4 class="heading">{{ __('End Date') }} *</h4><input type="text" class="input-field" name="end_date" id="to" placeholder="{{ __('Select a date') }}" required></div></div>
                <div class="col-xl-4 select-div"><div class="input-form"><h4 class="heading">{{ __('Modelos de desconto') }} *</h4><select id="discount_type" name="discount_type" required><option value="" disabled selected hidden>{{ __('Selecione uma opção') }}</option><option value="1">{{ __('Categoria') }}</option><option value="2">{{ __('Marca') }}</option></select></div></div>
                <div class="col-xl-4" style="display:none;"><div class="input-form"><h4 class="heading">{{ __('Select Category') }} *</h4><select id="cat" name="category_id"><option value="">{{ __('Selecione uma opção') }}</option>@foreach ($cats as $cat)<option data-href="{{ route('admin-subcat-load', $cat->id) }}" value="{{ $cat->id }}" {{ $cat->id == $data->category_id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div></div>
                <div class="col-xl-4" style="display:none;"><div class="input-form"><h4 class="heading">{{ __('Brand') }} *</h4><select id="brand" name="brand_id"><option value="">{{ __('Selecione uma opção') }}</option>@foreach ($brands as $brand)<option data-href="{{ route('admin-brand-load', $brand->id) }}" value="{{ $brand->id }}" {{ $brand->id == $data->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>@endforeach</select></div></div>
              </div>
              <div class="row justify-content-center">
                <button class="addProductSubmit-btn" type="submit">{{ __('Create Coupon') }}</button>
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
  $('#type').on('change', function() {
    let val = $(this).val(), sel = $(this).parent().parent().next();
    if (val === "") sel.hide();
    else {
      sel.find('.heading').html(val == 0 ? '{{ __("Percentage") }} *' : '{{ __("Amount") }} *');
      sel.find('input').attr("placeholder", val == 0 ? "{{ __('Enter Percentage') }}" : "{{ __('Enter Amount') }}").next().html(val == 0 ? '%' : '');
      sel.css('display', 'flex');
    }
  });

  $(document).on("change", "#times", function() {
    let val = $(this).val(), sel = $(this).parent().parent().next();
    if (val == 1) sel.css('display', 'flex');
    else sel.find('input').val("").end().hide();
  });

  $(document).ready(function() {
    $('#discount_type').on('change', function() {
      let val = $(this).val(), sel1 = $(this).closest('.select-div').next(), sel2 = sel1.next();
      sel1.hide(); sel2.hide();
      if (val == 1) { sel2.find('select').val(''); sel1.find('.heading').html('{{ __("Select Category") }} *'); sel1.css('display', 'flex'); }
      else if (val == 2) { sel1.find('select').val(''); sel2.find('.heading').html('{{ __("Select Brand") }} *'); sel2.css('display', 'flex'); }
    });
  });

  var dateToday = new Date();
  var dates = $("#from,#to").datepicker({
    defaultDate: "+1w", changeMonth: true, changeYear: true, minDate: dateToday,
    onSelect: function(selectedDate) {
      var opt = this.id == "from" ? "minDate" : "maxDate",
          inst = $(this).data("datepicker"),
          date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, inst.settings);
      dates.not(this).datepicker("option", opt, date);
    }
  });
</script>
@endsection
