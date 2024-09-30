<head>
    <meta charset="utf-8">
    <title>{{ __("Products list") }}</title>
    <style>body {font-family: Arial, sans-serif;font-size: 10pt;}table {width: 100%;border-collapse: collapse;}th, td {border: 1px solid #ddd;padding: 8px;line-height: 1.428;}th {text-align: left;}.titulo {font-size: 10pt;}.float-left { float: left; }.float-right { float: right; }</style>
</head>
<body>
    <div>
        <div class="float-left titulo">{{ $admstore->title }} | {{ $admstore->domain }}</div>
        <div class="float-right titulo">{{ __("Products list") }} | {{ date('d-m-Y') }}</div>
    </div>
    <br><br><br>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __("SKU") }}</th>
                <th>{{ __("Name") }}</th>
                <th>Foto</th>
                <th>{{ __("Price") . ' ' . $currency->sign }}</th>
                <th>{{ __("Brand") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td><img width="100" src="{{ $product->photo ? asset('storage/images/products/'.$product->photo) : asset('assets/images/noimage.png') }}" alt=""></td>
                <td>{{ number_format($product->price * $currency->value, $currency->decimal_digits, $currency->decimal_separator, $currency->thousands_separator) }}</td>
                <td>{{ $product->brand->name != __("Deleted") ? $product->brand->name : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
