<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Top Up Invoice</title>
</head>
<body>
<div style="margin: 0;background: #fff;padding: 15px;font-family:'Helvetica', 'Arial', sans-serif">
    <table
        style="width: 100%; border-collapse: collapse; display: table; border-spacing: 2px; margin-bottom: 0;line-height: 16px">
        <tbody>
        <tr>
            <td colspan="4">
                <img src="{{ $logo }}" alt="logo"
                     style="vertical-align: middle; border-style: none;max-width: 180px;max-height: 100px">
            </td>
            <td colspan="4" style="text-align: right; font-size: 14px; line-height: 24px;">
                <h1 style="font-size: 1vw;margin-bottom: 20px"><b>Tax Invoice </b></h1>
                <b>Invoice No :</b><span>#{{ $topUp->invoice_no }}</span> <br>
                <b>Invoice Date :</b><span> {{ $topUp->created_at->dateTimeFormat() }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="4"
                style="padding: .85rem 0; vertical-align: top;font-size: 14px;white-space:normal;line-height: 24px">
                <h6 style="font-size: 14px;margin-bottom: 5px">Company Address</h6>
                <b>{{ settings('company_name') }}</b><br>
                {{ settings('address_line_1') }},
                {{ settings('address_line_2') }},
                <br>   {{ settings('city') }}, {{ settings('state') }}
                -{{ settings('pincode') }} <br>
                <p>
                    <b>Mobile No :</b> {{ $topUp->invoiceAddress->admin_mobile }}
                </p>
                @if(settings('gst_no'))
                    <p>
                        <b>GST No :</b> {{ settings('gst_no') }}
                    </p>
                @endif
            </td>
6            <td colspan="4"
                style="padding: .85rem 0; vertical-align: top;font-size: 14px;white-space:normal;line-height: 24px">
                <h6 style="font-size: 14px;margin-bottom: 5px">Billing Address</h6>
                <b>
                    {{ $topUp->invoiceAddress->name }}
                </b>
                <br>
                {{ $topUp->invoiceAddress->member_address }}<br>
                @if($topUp->invoiceAddress->state || $topUp->invoiceAddress->city)
                    {{ optional($topUp->invoiceAddress->state)->name }}
                    ,{{ optional($topUp->invoiceAddress->city)->name }} -
                @endif
                {{ $topUp->invoiceAddress->member_pincode }}
                <p>
                    <b>Mobile No :</b> {{ $topUp->invoiceAddress->member_mobile }}
                </p>
            </td>
        </tr>
        <tr>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                #
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                DESCRIPTION
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                HSN
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                PRICE
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                IGST
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                SGST
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                CGST
            </th>
            <th style="vertical-align: bottom; border: 1px solid #dee2e6; font-size: 13px;white-space:nowrap;padding: .85rem;">
                TOTAL
            </th>
        </tr>
        @foreach($products as $index => $product)
            <tr>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">{{ $index + 1 }}</td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">{{ $product['name'] }}</td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">{{ $product['hsn_code'] }}</td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">
                    Rs.{{ $product['price'] }}
                </td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">
                    Rs.{{ $product['igst_amount'] }} ({{ $product['igst_percentage'] }}%)
                </td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">
                    Rs.{{ $product['sgst_amount'] }} ({{ $product['sgst_percentage'] }}%)
                </td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">
                    Rs.{{ $product['cgst_amount'] }} ({{ $product['cgst_percentage'] }}%)
                </td>
                <td style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;text-align: center">
                    Rs.{{ round($product['total'],2) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="8"
                style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: top;font-size: 14px;white-space:normal;line-height: 24px">
                <b>CUSTOMER ACKNOWLEDGEMENT: </b> Certified that I am at-least 18 years of age and have completed
                at-least 10th grade of schooling. I have received complete {{ $topUp->package->name }}
                online immediately after registration. I have carefully read terms &
                conditions as given on website {{ env('APP_URL') }} and agree to them.
                Currently I am not working with any other similar Business
                Operation. I am signing this DECLARATION with complete understanding
                and with my own WILL, without any PRESSURE / UNDUE INFLUENCE and
                INDUCEMENT. I am aware that any dispute arising out of this purchase
                would first be solved as per Terms and Conditions of the company,
                failing which could be addressed exclusively in competent courts in
                 {{ settings('city') }}, {{ settings('state') }} only.
            </td>
        </tr>
        <tr>
            <td colspan="8"
                style="border: 1px solid #dee2e6; padding: .85rem; vertical-align: middle;font-size: 12px;white-space:normal;line-height: 24px;text-align: center">
                Invoice was created on a computer and is valid without the signature and seal.
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
