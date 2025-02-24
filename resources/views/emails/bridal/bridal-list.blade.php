<!DOCTYPE html>
<html>
<head>
    <title>{{__('Wedding List')}} - {{$user->name}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;1,200;1,300;1,400;1,500&display=swap" rel="stylesheet">
</head>
<body>
<table cellspacing="0" cellpadding="10" align="center" style="font-family: 'Montserrat', sans-serif; width: 600px; background: #fff; border: 2px solid #F1E7DA;">
    <tr align="center">
        <th style="height: 60px; width: 500px; background: #fff; border-bottom: 2px solid #D39C5F;">
            <img width="140" src="{{ asset('assets/images/theme15/logo.jpg') }}" alt="">
        </th>
    </tr>
    <tr align="center">
        <th><p style="font-family: 'Cormorant Garamond', serif; font-size: 30px; color: #555;">¡Tu <span style="color: #D39C5F;">lista de bodas</span> ha sido creada!</p></th>
    </tr>
    <tr align="center">
        <th><p style="font-family: 'Cormorant Garamond', serif; font-size: 25px; color: #555;">Estimado/{{$user->name}},</p></th>
    </tr>
    <tr align="center">
        <th><p style="font-size: 16px; color: #555; font-weight: 400; letter-spacing: 1px; line-height: 35px;">¡Nos complace informarle que su lista de bodas se creó con éxito! Ahora puede compartir los detalles con amigos y familiares para que puedan contribuir con obsequios que serán parte de los recuerdos de su gran día. <br><br>Recuerde, estamos aquí para ayudarle en cada paso del camino.</p></th>
    </tr>
    <tr align="center">
        <th><p style="font-size: 16px; color: #D39C5F; font-weight: 400; letter-spacing: 1px; line-height: 35px;">Atentamente</p></th>
    </tr>
    <tr align="center">
        <th><p style="font-size: 16px; color: #D39C5F; letter-spacing: 1px; line-height: 35px;">El equipo del SAX Bridal. <a style="background:#D39C5F; padding: 10px 30px; border-radius: 4px; color: #fff; text-decoration: none;" href="{{route('user.wedding.show', $user->id)}}">Ver lista</a></p></th>
    </tr>
    <tr align="center">
        <th align="center" style="background:#F1E7DA; height: 70px;">
            <a href="{{route('front.index')}}" target="_blank"><img src="{{ asset('assets/images/theme15/logopng.png') }}" alt=""></a>
            <a href="https://saxdepartment.com/sax-palace" target="_blank"><img src="{{ asset('assets/images/theme15/palace.png') }}" alt=""></a>
            <a href="https://saxdepartment.com/" target="_blank"><img src="{{ asset('assets/images/theme15/depart.png') }}" alt=""></a>
            <a href="https://api.whatsapp.com/send?1=pt_BR&phone={!! $number !!}" target="_blank"><img src="{{ asset('assets/images/theme15/zappng.png') }}" alt=""></a>
        </th>
    </tr>
</table>
</body>
</html>
