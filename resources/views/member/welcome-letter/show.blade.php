<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Welcome Letter</title>
</head>
<body>
<div style="margin: 0;background: #fff;font-family:'Helvetica', 'Arial', sans-serif;font-size: 14px">
    <table
        style="width: 100%; border-collapse: collapse; display: table; border-spacing: 2px; margin-bottom: 0;line-height: 20px">
        <tbody>
        <tr>
            <td colspan="2" rowspan="2">
                <img src="{{ $logo }}" alt="logo"
                     style="vertical-align: middle; border-style: none;max-width: 180px;max-height: 100px">
            </td>
            <td colspan="4">
                @if(settings('address_enabled'))
                    <h4>{{ settings('company_name') }}</h4>
                    {{ settings('address_line_1') }},
                    {{ settings('address_line_2') }},
                    {{ settings('city') }}, {{ settings('state') }}

                    -{{ settings('pincode') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">
                GST NO : {{ settings('gst_no') ?: 'NA' }}
            </td>
            <td colspan="2">
                Mobile : {{ settings('mobile') ?: 'NA' }}
                Email : {{ settings('email') ?: 'NA' }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 10px"></td>
        </tr>
        <tr>
            <td colspan="6" class="text-center">
                <h4>Welcome Letter</h4>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 10px"></td>
        </tr>
        <tr>
            <td colspan="6">
                Dear, {{ $member->user->name }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="6">
                Welcome to family of {{ settings('company_name') }}. !
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td>Member Code :</td>
            <td> {{ $member->code }}</td>
        </tr>
        <tr>
            <td>Name :</td>
            <td> {{ $member->user->name }}</td>
        </tr>
        <tr>
            <td>Address :</td>
            <td> {{ $member->user->address }}
                @if($member->state)
                    ,{{ optional($member->state)->name }},{{ optional($member->city)->city }}-{{ $member->pincode }}
                @endif
            </td>
        </tr>
        <tr>
            <td>Joining Date :</td>
            <td> {{ $member->activated_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="6">
                {{ $member->package->name }} Amount Including GST :- <b>Rs. {{ $member->package->amount }}</b>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="6">
                It gives us great pleasure to welcome you as a Global Business Associate
                of {{ settings('company_name') }}. We wish
                you great success in your new endeavour. You are starting a journey, which can make your dreams come
                true.
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="6">
                Once again welcome to {{ settings('company_name') }} family and congratulations on having taken the
                first step
                towards a very prosperous and promising future. We are looking forward to have better business
                prospective in
                your association!
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="6">
                <h4>ALL THE BEST, SEE YOU AT TOP !! </h4>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="6">
                <b>Winning Regards,</b> <br>
                <b>Chief Admin Officer</b> <br><br>
                <b>{{ settings('company_name') }}</b> <br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
