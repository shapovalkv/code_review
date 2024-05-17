<aside class="sidebar">
    <div class="sidebar-widget company-widget">
        <div class="widget-content">
            <ul class="company-info mt-0">
                {{--                @if($row->category)--}}
                {{--                    @php $t = $row->category->translateOrOrigin(app()->getLocale()); @endphp--}}
                {{--                    <li>{{__("Primary industry")}}: <span>{{ $t->name }}</span></li>--}}
                {{--                @endif--}}
                {{--                @if($row->companyTerm)--}}
                {{--                        @foreach ($attributes as $attribute)--}}
                {{--                            @php $attribute_trans = $attribute->translateOrOrigin(app()->getLocale()); @endphp--}}
                {{--                            @if(isset($attribute->company_term))--}}
                {{--                            <li>{{ $attribute_trans->name }}:--}}
                {{--                                <div>--}}
                {{--                                    @foreach($attribute->company_term as $term)--}}
                {{--                                        <span>{{ $term }}</span></br>--}}
                {{--                                    @endforeach--}}
                {{--                                </div>--}}
                {{--                            </li>--}}
                {{--                            @endif--}}
                {{--                        @endforeach--}}
                {{--                @endif--}}
                {{--                @if(!empty($row->founded_in))--}}
                {{--                    <li>{{__("Founded in")}}: <span>{{ \Carbon\Carbon::parse($row->founded_in)->year }}</span></li>--}}
                {{--                @endif--}}
                @if(!empty($row->phone) && \Illuminate\Support\Facades\Auth::user())
                    <li>{{__("Phone")}}: <span>{{ $row->phone }}</span></li>
                @endif
                @if(!empty($row->email) && \Illuminate\Support\Facades\Auth::user())
                    <li>{{__("Email")}}: <span>{{ $row->email }}</span></li>
                @endif
                @if($row->offices()->exists())
                    <li>{{__("Location(s)")}}: <span>
                           @foreach($row->offices()->orderBy('is_main', 'desc')->get() as $office)
                               @if(!$office || !$office->location)
                                   @continue
                                @endif
                                {!! $office->location->name. '<br>' !!}
                            @endforeach
                        </span></li>
                @endif
                @php
                    $Social_media = !empty($row->social_media) ? $row->social_media : [];
                @endphp
                @if(isset($Social_media['facebook']) || isset($Social_media['instagram']) || isset($Social_media['twitter']) || isset($Social_media['linkedin']))
                    <li>{{__("Social media")}}:
                        <div class="social-links">
                            @if(!empty($Social_media['skype']))
                                <a href="{{ $Social_media['skype'] }}"><i class="fab fa-skype"></i></a>
                            @endif
                            @if(!empty($Social_media['facebook']))
                                <a href="{{ $Social_media['facebook'] }}"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if(!empty($Social_media['twitter']))
                                <a href="{{ $Social_media['twitter'] }}"><i class="fa-brands fa-x-twitter"></i></a>
                            @endif
                            @if(!empty($Social_media['instagram']))
                                <a href="{{ $Social_media['instagram'] }}"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if(!empty($Social_media['linkedin']))
                                <a href="{{ $Social_media['linkedin'] }}"><i class="fab fa-linkedin-in"></i></a>
                            @endif
                            @if(!empty($Social_media['google']))
                                <a href="{{ $Social_media['google'] }}"><i class="fab fa-google"></i></a>
                            @endif
                        </div>
                    </li>
                @endif
            </ul>
            @if(!empty($row->website))
                <div class="btn-box"><a rel="nofollow" target="_blank" href="{{ $row->website }}"
                                        class="theme-btn btn-style-three">{{__('Company Website')}}</a></div>
            @endif
        </div>
    </div>
    @if(!empty($offices))
        <div class="sidebar-widget">
            <!-- Map Widget -->
            <h4 class="widget-title">{{__("Company Location(s)")}}</h4>
            <div data-offices="{{ $offices ?? '' }}"></div>
            <div class="widget-content">
                <div class="map-outer mb-0">
                    <div class="map-canvas" id="map-canvas"></div>
                </div>
            </div>
        </div>
    @endif
</aside>
