<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $owner->name }} - {{ __('Wedding List') }}</title>
    <style>table, th, td {border: 1px solid black;border-collapse: collapse;width: 100%;}th {padding: 1rem 0;}td {padding: 0.5rem;text-align: center;}a {text-decoration: none;}img {width: 100px;}</style>
</head>
<body>
    {{ now()->format('d/m/Y h:i:s') }}
    <table>
        <thead><tr><th>{{ __('Photo') }}</th><th>{{ __('Name') }}</th><th>{{ __('Brand') }}</th></tr></thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><img src="{{ $product->getAttributes()['photo']  ? public_path('storage/images/products/' . $product->getAttributes()['photo'])  : public_path('assets/images/noimage.png') }}"></td><td><a href="{{ route('front.product', $product->slug) }}" target="_blank">{{ $product->name }}</a></td>
                    <td><h4>{{ $product->brand->name }}</h4></td>
                </tr>
            @empty
                <tr><td colspan="3"><h1>{{ __('No products added') }}</h1></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
