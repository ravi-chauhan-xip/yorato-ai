<html lang="en">

<head>
    <title>{{ settings('company_name') }} ID Card - {{ $member->code }}</title>
    <style>
        html {
            margin: 0;
            padding: 0;
            width: {{ $width }}px;
            height: {{ $height }}px;
        }

        body {
            margin: 0;
            padding: 0;
            width: {{ $width }}px;
            height: {{ $height }}px;
            position: relative;
            font-family: 'Helvetica', sans-serif;
        }

        @page {
            size: {{ $width }}px {{ $height }}px;
            margin: 0;
        }

        #companyLogo {
            position: absolute;
            padding: 0;
            z-index: 3;
            left: 50%;
            margin: 0 0 0 -{{ ($profileSize / 2) + 10 }}px;
            top: 12%;
            border-radius: 50%;
            max-width: {{ $profileSize }}px;
            max-height: {{ $profileSize }}px;
        }

        #backgroundImage {
            margin: 0;
            padding: 0;
            width: {{ $width }}px;
            height: {{ $height }}px;
            z-index: 1;
        }

        #profileImageBox {
            position: absolute;
            padding: 0;
            z-index: 3;
            left: 52%;
            margin: 0 0 0 -{{ ($profileSize / 2) + 10 }}px;
            top: 30%;
            border-radius: 50%;
            width: {{ $profileSize }}px;
            height: {{ $profileSize }}px;
        }

        #profileImage {
            overflow: hidden;
            z-index: 2;
            border-radius: 50%;
        }

        #memberName {
            position: absolute;
            z-index: 2;
            left: 9%;
            top: 59%;
            font-size: 20px;
            text-transform: capitalize;
            color: #fff;
        }

        #memberMobile {
            position: absolute;
            z-index: 2;
            /*left: 19.5%;*/
            left: 9%;
            top: 66%;
            font-size: 20px;
            text-transform: capitalize;
            color: #fff;
        }

        #memberCode {
            position: absolute;
            z-index: 2;
            /*left: 17%;*/
            left: 9%;
            top: 73%;
            font-size: 20px;
            text-transform: capitalize;
            color: #fff;
        }

        #memberDOB {
            position: absolute;
            z-index: 2;
            /*left: 17%;*/
            left: 9%;
            top: 80%;
            font-size: 20px;
            text-transform: capitalize;
            color: #fff;
        }
        #memberAddress {
            position: absolute;
            z-index: 2;
            /*left: 17%;*/
            left: 9%;
            right: 9%;
            top: 86%;
            font-size: 20px;
            text-transform: capitalize;
            color: #fff;
            line-height: 30px;
        }
    </style>
</head>
<body>
<img src="{{ $logo }}" alt="Company logo" id="companyLogo">
<img src="{{ $backgroundImage }}" alt="Background Image" id="backgroundImage">
<span id="profileImageBox">
<img src="{{ $profileImage }}" alt="Profile Image" id="profileImage">
</span>
<span id="memberName"><b>NAME : </b>&nbsp; {{ Str::limit($member->user->name, 26) }}</span>
<span id="memberMobile"><b>MO : </b> {{ $member->user->mobile }}</span>
<span id="memberCode"><b>ID : &nbsp;</b> {{ $member->code }}</span>
<span id="memberDOB"><b>DOB : </b> {{ $member->user->dob ? \Carbon\Carbon::parse($member->user->dob)->format('d-m-Y') : 'N/A' }}</span>
<span id="memberAddress"><b>ADDRESS :</b>
    {{ $member->user->address }} {{ $member->user->pincode }}
</span>
</body>
</html>
