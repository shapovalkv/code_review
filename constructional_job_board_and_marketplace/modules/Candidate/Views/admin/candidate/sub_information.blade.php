    @php
        $candidate = $row->candidate;
    @endphp
    <h3 class="panel-body-title">{{__('Education')}}</h3>
    <div class="form-group-item">
        <div class="g-items">
            <?php $educations = @$candidate->education;?>
            @if(!empty($educations))
                @foreach($educations as $key=>$item)
                    <div class="candidate-inform-item" data-number="{{$key}}">
                        <div class="candidate-inform-item__main">
                            <div class="candidate-inform-item__info">
                                <div class="candidate-inform-item__time">
                                    <div class="form-group mb-md-0">
                                        <label>{{__("Time")}}</label>
                                        <input
                                            type="text"
                                            name="education[{{$key}}][from]"
                                            value="{{@$item['from']}}"
                                            placeholder="{{__("From")}}"
                                            class="form-control has-easepick js-required-input"
                                            required
                                        >
                                    </div>

                                    <div class="candidate-inform-item__divider">-</div>

                                    <div class="form-group mb-md-0">
                                        <input
                                            type="text"
                                            name="education[{{$key}}][to]"
                                            value="{{@$item['to']}}"
                                            placeholder="{{__("To")}}"
                                            class="form-control has-easepick js-required-input"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="form-group mb-md-0">
                                    <label>{{__("Name")}}</label>
                                    <input
                                        type="text"
                                        name="education[{{$key}}][location]"
                                        value="{{@$item['location']}}"
                                        placeholder="{{__("Name")}}"
                                        class="form-control js-required-input"
                                        required
                                    >
                                </div>

                                <div class="form-group mb-0">
                                    <label>{{__("Degree")}}</label>
                                    <input
                                        type="text"
                                        name="education[{{$key}}][information]"
                                        value="{{@$item['information']}}"
                                        placeholder="{{__("Degree")}}"
                                        class="form-control js-required-input"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="candidate-inform-item__remove btn-remove-item"></div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="text-left">
            <a href="#" class="f-btn secondary-btn btn-add-item d-inline-block">
                {{__('Add item')}}
                <i class="ri-add-line"></i>
            </a>
        </div>

        <div class="g-more hide">
            <div class="candidate-inform-item" data-number="__number__">
                <div class="candidate-inform-item__main">
                    <div class="candidate-inform-item__info">
                        <div class="candidate-inform-item__time">
                            <div class="form-group mb-md-0">
                                <label>{{__("Time")}}</label>
                                <input
                                    type="text"
                                    __name__="education[__number__][from]"
                                    value=""
                                    placeholder="{{__("From")}}"
                                    class="form-control __has-easepick__ __js-required-input__"
                                    minlength="10"
                                    __required__
                                >
                            </div>

                            <div class="candidate-inform-item__divider">-</div>

                            <div class="form-group mb-md-0">
                                <input
                                    type="text"
                                    __name__="education[__number__][to]"
                                    value=""
                                    placeholder="{{__("To")}}"
                                    class="form-control __has-easepick__ __js-required-input__"
                                    __required__
                                >
                            </div>
                        </div>

                        <div class="form-group mb-md-0">
                            <label>{{__("Name")}}</label>
                            <input
                                type="text"
                                __name__="education[__number__][location]"
                                value=""
                                placeholder="{{__("Name")}}"
                                class="form-control __js-required-input__"
                                __required__
                            >
                        </div>

                        <div class="form-group mb-0">
                            <label>{{__("Degree")}}</label>
                            <input
                                type="text"
                                __name__="education[__number__][information]"
                                value=""
                                placeholder="{{__("Degree")}}"
                                class="form-control __js-required-input__"
                                __required__
                            >
                        </div>
                    </div>
                </div>

                <div class="candidate-inform-item__remove btn-remove-item"></div>
            </div>
        </div>
    </div>

    <hr>
    <h3 class="panel-body-title">{{__('Work History')}}</h3>
    <div class="form-group-item">
        <div class="g-items">
            <?php $experiences = @$candidate->experience; ?>
            @if(!empty($experiences))
                @foreach($experiences as $key=>$item)
                    <div class="candidate-inform-item" data-number="{{$key}}">
                        <div class="candidate-inform-item__main">
                            <div class="candidate-inform-item__info">
                                <div class="candidate-inform-item__time">
                                    <div class="form-group mb-md-0">
                                        <label>{{__("Time")}}</label>
                                        <input
                                            type="text"
                                            name="experience[{{$key}}][from]"
                                            value="{{@$item['from']}}"
                                            placeholder="{{__("From")}}"
                                            class="form-control has-easepick js-required-input"
                                            required
                                        >
                                    </div>

                                    <div class="candidate-inform-item__divider">-</div>

                                    <div class="form-group mb-md-0">
                                        <input
                                            type="text"
                                            name="experience[{{$key}}][to]"
                                            value="{{@$item['to']}}"
                                            placeholder="{{__("To")}}"
                                            class="form-control has-easepick js-required-input"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="form-group mb-md-0">
                                    <label>{{__("Name")}}</label>
                                    <input
                                        type="text"
                                        name="experience[{{$key}}][location]"
                                        value="{{@$item['location']}}"
                                        placeholder="{{__("Name")}}"
                                        class="form-control js-required-input"
                                        required
                                    >
                                </div>

                                <div class="form-group mb-0">
                                    <label>{{__("Position")}}</label>
                                    <input
                                        type="text"
                                        name="experience[{{$key}}][position]"
                                        value="{{@$item['position']}}"
                                        placeholder="{{__("Position")}}"
                                        class="form-control js-required-input"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="candidate-inform-item__description">
                                <div class="form-group mb-0">
                                    <label>{{__("More info")}}</label>
                                    <textarea
                                        name="experience[{{$key}}][information]"
                                        rows="1"
                                        class="form-control experience-textarea js-required-input"
                                        placeholder="{{__("More info")}}"
                                        required
                                    >
                                        {{@$item['information']}}
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="candidate-inform-item__remove btn-remove-item"></div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="text-left">
            <a href="#" class="f-btn secondary-btn btn-add-item d-inline-block">
                {{__('Add item')}}
                <i class="ri-add-line"></i>
            </a>
        </div>

        <div class="g-more hide">
            <div class="candidate-inform-item" data-number="__number__">
                <div class="candidate-inform-item__main">
                    <div class="candidate-inform-item__info">
                        <div class="candidate-inform-item__time">
                            <div class="form-group mb-md-0">
                                <label>{{__("Time")}}</label>
                                <input
                                    type="text"
                                    __name__="experience[__number__][from]"
                                    value=""
                                    placeholder="{{__("From")}}"
                                    class="form-control __has-easepick__ __js-required-input__"
                                    minlength="10"
                                    __required__
                                >
                            </div>

                            <div class="candidate-inform-item__divider">-</div>

                            <div class="form-group mb-md-0">
                                <input
                                    type="text"
                                    __name__="experience[__number__][to]"
                                    value=""
                                    placeholder="{{__("To")}}"
                                    class="form-control __has-easepick__ __js-required-input__"
                                    __required__
                                >
                            </div>
                        </div>

                        <div class="form-group mb-md-0">
                            <label>{{__("Name")}}</label>
                            <input
                                type="text"
                                __name__="experience[__number__][location]"
                                value=""
                                placeholder="{{__("Name")}}"
                                class="form-control __js-required-input__"
                                __required__
                            >
                        </div>

                        <div class="form-group mb-0">
                            <label>{{__("Position")}}</label>
                            <input
                                type="text"
                                __name__="experience[__number__][position]"
                                value=""
                                placeholder="{{__("Position")}}"
                                class="form-control __js-required-input__"
                                __required__
                            >
                        </div>
                    </div>

                    <div class="candidate-inform-item__description">
                        <div class="form-group mb-0">
                            <label>{{__("More info")}}</label>
                            <textarea
                                __name__="experience[__number__][information]"
                                rows="1"
                                class="form-control experience-textarea __js-required-input__"
                                __required__
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="candidate-inform-item__remove btn-remove-item"></div>
            </div>
        </div>
    </div>

    <hr>

    <h3 class="panel-body-title">{{__('Certificate')}}</h3>

    <div class="form-group-item">
        <div class="g-items">
            <?php $educations = @$candidate->award; ?>
            @if(!empty($educations))
                @foreach($educations as $key=>$item)
                    <div class="candidate-inform-item" data-number="{{$key}}">
                        <div class="candidate-inform-item__main">
                            <div class="candidate-inform-item__info">
                                <div class="candidate-inform-item__time">
                                    <div class="form-group mb-md-0">
                                        <label>{{__("Time")}}</label>
                                        <input
                                            type="text"
                                            name="award[{{$key}}][from]"
                                            value="{{@$item['from']}}"
                                            placeholder="{{__("From")}}"
                                            class="form-control has-easepick js-required-input"
                                            required
                                        >
                                    </div>

                                    <div class="candidate-inform-item__divider">-</div>

                                    <div class="form-group mb-md-0">
                                        <input
                                            type="text"
                                            name="award[{{$key}}][to]"
                                            value="{{@$item['to']}}"
                                            placeholder="{{__("To")}}"
                                            class="form-control has-easepick js-required-input"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="form-group mb-md-0">
                                    <label>{{__("Name")}}</label>
                                    <input
                                        type="text"
                                        name="award[{{$key}}][location]"
                                        value="{{@$item['location']}}"
                                        placeholder="{{__("Name")}}"
                                        class="form-control js-required-input"
                                        required
                                    >
                                </div>

                                <div class="form-group mb-0">
                                    <label>{{__("Position")}}</label>
                                    <input
                                        type="text"
                                        name="award[{{$key}}][information]"
                                        value="{{@$item['information']}}"
                                        placeholder="{{__("Position")}}"
                                        class="form-control js-required-input"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="candidate-inform-item__remove btn-remove-item"></div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="text-left">
            <a href="#" class="f-btn secondary-btn btn-add-item d-inline-block">
                {{__('Add item')}}
                <i class="ri-add-line"></i>
            </a>
        </div>

        <div class="g-more hide">
            <div class="candidate-inform-item" data-number="__number__">
                <div class="candidate-inform-item__main">
                    <div class="candidate-inform-item__info">
                        <div class="candidate-inform-item__time">
                            <div class="form-group mb-md-0">
                                <label>{{__("Time")}}</label>
                                <input
                                    type="text"
                                    __name__="award[__number__][from]"
                                    value=""
                                    placeholder="{{__("From")}}"
                                    minlength="10"
                                    class="form-control __has-easepick__ __js-required-input__"
                                    __required__
                                >
                            </div>

                            <div class="candidate-inform-item__divider">-</div>

                            <div class="form-group mb-md-0">
                                <input
                                    type="text"
                                    __name__="award[__number__][to]"
                                    value=""
                                    placeholder="{{__("To")}}"
                                    class="form-control __has-easepick__ __js-required-input__"
                                    __required__
                                >
                            </div>
                        </div>

                        <div class="form-group mb-md-0">
                            <label>{{__("Name")}}</label>
                            <input
                                type="text"
                                __name__="award[__number__][location]"
                                value=""
                                placeholder="{{__("Name")}}"
                                class="form-control __js-required-input__"
                                __required__
                            >
                        </div>

                        <div class="form-group mb-0">
                            <label>{{__("Position")}}</label>
                            <input
                                type="text"
                                __name__="award[__number__][information]"
                                value=""
                                placeholder="{{__("Position")}}"
                                class="form-control __js-required-input__"
                                __required__
                            >
                        </div>
                    </div>
                </div>

                <div class="candidate-inform-item__remove btn-remove-item"></div>
            </div>
        </div>
    </div>

    <input id="assetsPath" type="hidden" value='{{ asset("libs/easepick/easepick.css") }}'>



