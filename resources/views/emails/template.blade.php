<div style="text-align: center;padding: 5rem;display: block;
        border: 5px solid #fa8239;font-family: sans-serif;">
    <img style="width: 250px;margin-bottom: 20px;" src="{{ asset('img/logo.png') }}"/>
    <h3 style="font-size: 2em;margin: 0;">{{ $details['title'] }}</h3>
    <p>@php echo  $details['body'] @endphp</p>
    @if(!empty($details['show_btns']))
        <div style=" margin: 10px 0;padding: 2rem;">
            <a style="text-decoration: none;text-transform: uppercase;font-weight: 600;background: #fa8239;color: #fff;padding: 10px 30px;border: 1px solid #ff6d14;border-radius: 5px;" href="{{ $details['link'] }}">Click Here</a>
        </div>
    @endif
    <div style="color: #727272;padding: 10px 0;border-bottom: 1px solid #727272;border-top: 1px solid #727272;margin-top: 20px;">
        copyrights@2023 schoolmanagement
    </div>
</div>
