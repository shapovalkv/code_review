<div class="job-overview-two">
    <h4>{{ __("Job Overview") }}</h4>
    <ul>
        @if($row->created_at)
            <li>
                <i class="icon icon-calendar"></i>
                <h5>{{ __("Date Posted:") }}</h5>
                <span>{{ __("Posted :time_ago", ['time_ago' => $row->timeAgo()]) }}</span>
            </li>
        @endif
        @if($row->expiration_date)
            <li>
                <i class="icon icon-expiry"></i>
                <h5>{{ __("Expiration date:") }}</h5>
                <span>{{ display_date($row->expiration_date) }}</span>
            </li>
        @endif
        @if($row->location)
            @php $location_translation = $row->location->translateOrOrigin(app()->getLocale()) @endphp
            <li>
                <i class="icon icon-location"></i>
                <h5>{{ __("Location:") }}</h5>
                <span>{{ $location_translation->name }}</span>
            </li>
        @endif
        @if($row->hours)
            <li>
                <i class="icon icon-clock"></i>
                <h5>{{ __("Hours:") }}</h5>
                <span>{{ $row->hours }} @if($row->hours_type)/ {{ $row->hours_type }} @endif</span>
            </li>
        @endif
        @if($row->salary_min && $row->salary_max)
            <li>
                <i class="icon icon-salary"></i>
                <h5>{{ __("Salary:") }}</h5>
                <span>{{ $row->getSalary() }}</span>
            </li>
        @endif
        <li>
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="22" height="22" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                <g>
                <g xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <g>
                            <path d="M510.702,438.722c-2.251-10.813-12.844-17.753-23.656-15.503c-10.815,2.252-17.756,12.843-15.504,23.657     c1.297,6.228-0.247,12.613-4.236,17.518c-2.311,2.84-7.461,7.606-15.999,7.606H361c-11.046,0-20,8.954-20,20     c0,11.046,8.954,20,20,20h90.307c18.329,0,35.471-8.153,47.032-22.369C509.957,475.344,514.464,456.789,510.702,438.722z" fill="#1967d2" data-original="#000000" style="" class=""/>
                            <path d="M276.306,272.769c65.707,6.052,125.477,41.269,162.703,96.788c6.15,9.174,18.576,11.626,27.749,5.474     c9.175-6.152,11.625-18.576,5.474-27.75c-32.818-48.946-80.475-84.53-134.812-102.412C370.535,220.04,392,180.48,392,136     C392,61.009,330.99,0,256,0S120,61.009,120,136c0,44.509,21.492,84.092,54.643,108.917     c-30.371,9.998-58.871,25.547-83.813,46.062c-45.732,37.617-77.529,90.087-89.532,147.743     c-3.762,18.067,0.745,36.622,12.363,50.909C25.221,503.847,42.364,512,60.693,512H148c11.046,0,20-8.954,20-20     c0-11.046-8.954-20-20-20H60.691c-8.538,0-13.688-4.765-15.999-7.606c-3.989-4.906-5.533-11.29-4.236-17.519     c19.584-94.068,98.98-164.202,193.187-173.885l-38.181,173.709c-1.463,6.663,0.569,13.612,5.392,18.435l40.002,40.007     c3.75,3.752,8.838,5.859,14.144,5.859c5.305,0,10.392-2.108,14.143-5.859l39.998-40.007c4.823-4.824,6.855-11.772,5.391-18.434     L276.306,272.769z M160,136c0-52.935,43.065-96,96-96s96,43.065,96,96c0,51.337-40.505,93.389-91.235,95.882     c-1.586-0.029-3.174-0.048-4.765-0.048c-1.561,0-3.12,0.023-4.679,0.051C200.551,229.436,160,187.366,160,136z M254.999,462.713     l-18.117-18.12l18.117-82.425l18.115,82.426L254.999,462.713z" fill="#1967d2" data-original="#000000" style="" class=""/>
                        </g>
                    </g>
                </g>
                </g>
            </svg>
            </span>
            <h5>{{ __("Experience:") }}</h5>
            <span>
                @if(empty($row->experience) || (float)$row->experience < 1)
                    {{ __("Fresh") }}
                @else
                    {{ $row->experience }} {{ $row->experience > 1 ? __("years") : __("year") }}
                @endif
            </span>
        </li>
        @if($row->number_recruitments)
        <li>
            <span class="icon">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                     viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                    <g id="Layer_1">
                        <path fill="#1967d2" d="M9,49h32v-4h8V33c0-5.834-3.863-10.781-9.165-12.421C42.335,18.978,44,16.182,44,13c0-4.962-4.037-9-9-9
                            c-0.855,0-1.703,0.128-2.531,0.373C30.636,2.31,27.971,1,25,1s-5.636,1.31-7.469,3.373C16.703,4.128,15.855,4,15,4
                            c-4.963,0-9,4.038-9,9c0,3.182,1.665,5.978,4.165,7.579C4.863,22.219,1,27.166,1,33v12h8V49z M39,47H11v-2V32
                            c0-0.334,0.021-0.664,0.05-0.991c0.01-0.107,0.023-0.213,0.035-0.32c0.027-0.226,0.062-0.449,0.103-0.67
                            c0.021-0.112,0.038-0.225,0.062-0.336c0.062-0.287,0.135-0.569,0.218-0.848c0.042-0.138,0.092-0.272,0.139-0.408
                            c0.054-0.158,0.111-0.314,0.172-0.469c0.059-0.149,0.121-0.297,0.186-0.443c0.064-0.143,0.134-0.283,0.204-0.423
                            c0.102-0.204,0.21-0.404,0.324-0.601c0.1-0.171,0.2-0.342,0.308-0.507c0.084-0.128,0.174-0.252,0.263-0.377
                            c0.094-0.132,0.191-0.262,0.291-0.389c0.092-0.117,0.184-0.233,0.281-0.346c0.126-0.147,0.257-0.289,0.39-0.429
                            c0.123-0.13,0.249-0.258,0.378-0.382c0.159-0.152,0.32-0.301,0.488-0.444c0.11-0.093,0.224-0.182,0.338-0.271
                            c0.131-0.102,0.263-0.202,0.399-0.298c0.126-0.09,0.252-0.179,0.383-0.264c0.132-0.086,0.268-0.165,0.403-0.245
                            c0.255-0.151,0.517-0.292,0.786-0.423c0.135-0.066,0.268-0.135,0.406-0.196l0.161-0.072C19.072,21.303,20.501,21,22,21h6
                            c1.499,0,2.928,0.303,4.231,0.849l0.161,0.072c0.138,0.06,0.271,0.13,0.406,0.196c0.269,0.131,0.531,0.272,0.786,0.423
                            c0.136,0.08,0.272,0.159,0.403,0.245c0.13,0.085,0.257,0.174,0.383,0.264c0.135,0.096,0.268,0.196,0.399,0.298
                            c0.114,0.089,0.228,0.178,0.338,0.271c0.168,0.142,0.329,0.292,0.488,0.444c0.13,0.124,0.255,0.252,0.378,0.382
                            c0.133,0.14,0.265,0.282,0.39,0.429c0.096,0.113,0.189,0.229,0.281,0.346c0.1,0.127,0.197,0.257,0.291,0.389
                            c0.089,0.125,0.18,0.248,0.263,0.377c0.108,0.165,0.209,0.336,0.308,0.507c0.114,0.197,0.222,0.398,0.324,0.601
                            c0.07,0.14,0.14,0.28,0.204,0.423c0.066,0.146,0.127,0.294,0.186,0.443c0.061,0.154,0.117,0.311,0.172,0.469
                            c0.047,0.136,0.097,0.271,0.139,0.408c0.084,0.278,0.157,0.561,0.218,0.848c0.024,0.111,0.042,0.224,0.062,0.336
                            c0.04,0.221,0.076,0.445,0.103,0.67c0.013,0.106,0.026,0.213,0.035,0.32C38.979,31.336,39,31.666,39,32v13V47z M47,33v10h-6V32
                            c0-0.475-0.029-0.943-0.079-1.405c-0.012-0.109-0.035-0.215-0.049-0.323c-0.048-0.357-0.104-0.712-0.18-1.059
                            c-0.025-0.114-0.059-0.224-0.087-0.337c-0.084-0.339-0.177-0.674-0.287-1.002c-0.037-0.111-0.08-0.219-0.12-0.328
                            c-0.118-0.323-0.247-0.641-0.389-0.951c-0.05-0.109-0.103-0.217-0.157-0.324c-0.149-0.303-0.309-0.598-0.481-0.887
                            c-0.063-0.106-0.126-0.212-0.193-0.317c-0.18-0.283-0.371-0.557-0.571-0.825c-0.073-0.098-0.143-0.197-0.219-0.293
                            c-0.219-0.277-0.452-0.542-0.693-0.8c-0.068-0.073-0.131-0.15-0.201-0.221c-0.316-0.324-0.646-0.634-0.994-0.923
                            C42.227,22.164,47,27.035,47,33z M33.723,6.121C34.145,6.041,34.571,6,35,6c3.859,0,7,3.14,7,7s-3.141,7-7,7
                            c-1.373,0-2.694-0.4-3.826-1.144c0.015-0.011,0.027-0.025,0.041-0.037c0.861-0.685,1.603-1.509,2.199-2.438
                            c0.019-0.03,0.036-0.06,0.055-0.09c0.173-0.276,0.332-0.56,0.478-0.853c0.024-0.048,0.046-0.096,0.069-0.144
                            c0.133-0.277,0.252-0.561,0.359-0.852c0.022-0.06,0.045-0.12,0.066-0.18c0.097-0.279,0.18-0.564,0.252-0.854
                            c0.018-0.072,0.039-0.143,0.055-0.215c0.064-0.283,0.112-0.571,0.151-0.863c0.011-0.077,0.027-0.153,0.035-0.231
                            C34.976,11.739,35,11.372,35,11c0-0.396-0.029-0.784-0.074-1.168c-0.014-0.122-0.037-0.242-0.056-0.364
                            c-0.042-0.268-0.092-0.533-0.154-0.794c-0.03-0.127-0.062-0.253-0.097-0.378c-0.081-0.289-0.176-0.572-0.282-0.849
                            c-0.03-0.077-0.054-0.156-0.085-0.232c-0.152-0.37-0.325-0.728-0.519-1.075C33.728,6.133,33.726,6.127,33.723,6.121z M31.163,5.905
                            l0.061,0.079c0.049,0.061,0.093,0.125,0.14,0.187c0.51,0.671,0.9,1.402,1.174,2.173c0.018,0.05,0.038,0.099,0.055,0.15
                            c0.119,0.359,0.209,0.726,0.276,1.1c0.014,0.078,0.025,0.158,0.036,0.237C32.961,10.216,33,10.605,33,11
                            c0,0.328-0.025,0.652-0.065,0.973c-0.009,0.075-0.023,0.149-0.034,0.224c-0.041,0.265-0.092,0.527-0.159,0.784
                            c-0.014,0.054-0.027,0.109-0.042,0.163c-0.181,0.644-0.439,1.262-0.771,1.838c-0.006,0.01-0.012,0.02-0.018,0.03
                            c-0.161,0.277-0.341,0.544-0.535,0.8c-0.018,0.023-0.035,0.047-0.053,0.071c-0.407,0.527-0.881,1.004-1.414,1.42
                            c-0.031,0.024-0.064,0.047-0.095,0.071c-0.247,0.187-0.505,0.361-0.775,0.52c-0.048,0.028-0.093,0.059-0.142,0.086l-0.114,0.066
                            C27.655,18.654,26.368,19,25,19s-2.655-0.346-3.782-0.954l-0.114-0.066c-0.048-0.027-0.094-0.058-0.142-0.086
                            c-0.27-0.159-0.528-0.333-0.775-0.52c-0.032-0.024-0.064-0.047-0.095-0.071c-0.532-0.416-1.006-0.893-1.414-1.42
                            c-0.018-0.023-0.035-0.047-0.053-0.071c-0.194-0.256-0.373-0.523-0.535-0.8c-0.006-0.01-0.012-0.02-0.018-0.03
                            c-0.333-0.577-0.591-1.195-0.772-1.839c-0.015-0.053-0.028-0.108-0.042-0.161c-0.067-0.258-0.118-0.52-0.159-0.785
                            c-0.011-0.075-0.025-0.148-0.034-0.224C17.025,11.652,17,11.328,17,11c0-0.395,0.039-0.784,0.095-1.168
                            c0.012-0.079,0.022-0.158,0.036-0.237c0.067-0.373,0.157-0.741,0.276-1.1c0.017-0.051,0.037-0.1,0.055-0.15
                            c0.274-0.771,0.664-1.503,1.174-2.173c0.047-0.062,0.091-0.127,0.14-0.187l0.061-0.079C20.306,4.132,22.523,3,25,3
                            S29.694,4.132,31.163,5.905z M8,13c0-3.86,3.141-7,7-7c0.429,0,0.855,0.041,1.277,0.121c-0.003,0.006-0.006,0.013-0.009,0.019
                            c-0.193,0.346-0.367,0.704-0.519,1.074c-0.032,0.077-0.056,0.156-0.086,0.234c-0.106,0.277-0.201,0.56-0.282,0.848
                            c-0.035,0.125-0.067,0.251-0.097,0.378C15.222,8.935,15.172,9.2,15.13,9.468c-0.019,0.121-0.041,0.241-0.056,0.364
                            C15.029,10.216,15,10.604,15,11c0,0.372,0.024,0.739,0.064,1.1c0.009,0.078,0.025,0.153,0.035,0.23
                            c0.039,0.292,0.088,0.581,0.151,0.864c0.016,0.072,0.037,0.142,0.055,0.213c0.072,0.29,0.155,0.576,0.252,0.856
                            c0.021,0.061,0.044,0.12,0.066,0.181c0.107,0.29,0.227,0.574,0.359,0.851c0.023,0.048,0.046,0.097,0.07,0.145
                            c0.146,0.293,0.305,0.577,0.478,0.853c0.019,0.03,0.036,0.06,0.055,0.09c0.596,0.929,1.339,1.752,2.199,2.438
                            c0.014,0.012,0.027,0.025,0.041,0.037C17.694,19.6,16.373,20,15,20C11.141,20,8,16.86,8,13z M3,43V33
                            c0-5.965,4.773-10.836,10.701-10.996c-0.348,0.289-0.678,0.6-0.994,0.923c-0.07,0.071-0.133,0.148-0.201,0.221
                            c-0.241,0.258-0.474,0.523-0.694,0.801c-0.075,0.095-0.145,0.194-0.218,0.292c-0.201,0.268-0.392,0.542-0.572,0.826
                            c-0.066,0.105-0.129,0.21-0.193,0.317c-0.172,0.289-0.332,0.584-0.481,0.887c-0.053,0.108-0.106,0.215-0.157,0.324
                            c-0.143,0.31-0.271,0.628-0.389,0.951c-0.04,0.11-0.083,0.217-0.12,0.328c-0.11,0.329-0.203,0.664-0.288,1.004
                            c-0.028,0.112-0.061,0.222-0.086,0.335c-0.076,0.348-0.132,0.703-0.18,1.061c-0.015,0.108-0.037,0.213-0.049,0.322
                            C9.029,31.057,9,31.525,9,32v11H3z"/>
                    </g>
                    <g>
                    </g>
                    </svg>
            </span>
            <h5>{{ __("Number Of Recruitments:") }}</h5>
            <span>{{ $row->number_recruitments  }}</span>
        </li>
        @endif
        @if($row->gender)
            <li>
                <i class="icon icon-user-2"></i>
                <h5>{{ __("Gender:") }}</h5>
                <span>{{ $row->gender_text  }}</span>
            </li>
        @endif
    </ul>
</div>