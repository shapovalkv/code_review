    @php
        $candidate = $row->candidate;
    @endphp
    <h3 class="panel-body-title">{{__('Education')}}</h3>
    <div class="form-group-item">
        <div class="g-items-header">
            <div class="row">
                <div class="col-md-2">{{__("Start Date")}}</div>
                <div class="col-md-2 text-nowrap" title="If you are still working at this place, just put current date">{{__("End Date")}}<span class="ml-2"><i class="la la-eye"></i></span></div>
                <div class="col-md-2">{{__('Name of School/Institution')}}</div>
                <div class="col-md-2">{{__('Certificate or Diploma obtained')}}</div>
{{--                <div class="col-md-2">{{__('Training and specialties')}}</div>--}}
{{--                <div class="col-md-2">{{__('More Information')}}</div>--}}
                <div class="col-md-1"></div>
            </div>
        </div>
        <div class="g-items">
            <?php $educations = @$candidate->education;?>
            @if(!empty($educations))
                @foreach($educations as $key=>$item)
                    <div class="item" data-number="{{$key}}">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="education[{{$key}}][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.'.$key.'.from', @$item['from'])}}" placeholder="{{__('MM/YYYY')}}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="education[{{$key}}][to]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.'.$key.'.to', @$item['to'])}}" placeholder="{{__('MM/YYYY')}}">
                            </div>
{{--                            <div class="col-md-2">--}}
{{--                                <input type="text" name="education[{{$key}}][location]" class="form-control" value="{{@$item['location']}}">--}}
{{--                            </div>--}}
                            <div class="col-md-2">
                                <input type="text" name="education[{{$key}}][reward]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.'.$key.'.reward', @$item['reward'])}}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="education[{{$key}}][diploma]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.'.$key.'.diploma', @$item['diploma'])}}">
                            </div>
{{--                            <div class="col-md-3">--}}
{{--                                <textarea name="education[{{$key}}][information]" class="form-control" >{{@$item['information']}}</textarea>--}}
{{--                            </div>--}}
                            @if($key !== 0)
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="item" data-number="0">
                    <div class="row">
                        <div class="col-md-2">
                            <input required type="text" name="education[0][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.0.from')}}" placeholder="{{__('MM/YYYY')}}">
                        </div>
                        <div class="col-md-2">
                            <input required type="text" name="education[0][to]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.0.to')}}" placeholder="{{__('MM/YYYY')}}">
                        </div>
                        {{--                            <div class="col-md-2">--}}
                        {{--                                <input type="text" name="education[{{$key}}][location]" class="form-control" value="{{@$item['location']}}">--}}
                        {{--                            </div>--}}
                        <div class="col-md-2">
                            <input required type="text" name="education[0][reward]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.0.reward')}}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="education[0][diploma]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('education.0.diploma')}}">
                        </div>
                        {{--                            <div class="col-md-3">--}}
                        {{--                                <textarea name="education[{{$key}}][information]" class="form-control" >{{@$item['information']}}</textarea>--}}
                        {{--                            </div>--}}
                    </div>
                </div>
            @endif
        </div>
        <div class="text-right">
            <span class="btn btn-style-four btn-sm btn-add-item"><i class="fa fa-plus"></i> {{__('Add item')}}</span>
        </div>
        <div class="g-more hide">
            <div class="item" data-number="__number__">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" __name__="education[__number__][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="" placeholder="{{__('MM/YYYY')}}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" __name__="education[__number__][to]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="" placeholder="{{__('MM/YYYY')}}">
                    </div>
{{--                    <div class="col-md-2">--}}
{{--                        <input type="text" __name__="education[__number__][location]" class="form-control" value="">--}}
{{--                    </div>--}}
                    <div class="col-md-2">
                        <input type="text" __name__="education[__number__][reward]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="">
                    </div>
                    <div class="col-md-2">
                        <input type="text" __name__="education[__number__][diploma]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="">
                    </div>
<!--                    <div class="col-md-2">
                        <textarea __name__="education[__number__][information]" class="form-control" ></textarea>
                    </div>-->
                    <div class="col-md-1">
                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <h3 class="panel-body-title">{{__('Related Work & Experience')}}</h3>
    <div class="form-group-item">
        <div class="g-items-header">
            <div class="row">
                <div class="col-md-2">{{__("Start Date")}}</div>
                <div class="col-md-2 text-nowrap" title="If you are still working at this place, just put current date">{{__("End Date")}}<span class="ml-2"><i class="la la-eye"></i></span></div>
                <div class="col-md-2">{{__('Location')}}</div>
                <div class="col-md-2">{{__('Name of Position')}}</div>
{{--                <div class="col-md-2">{{__('More Information')}}</div>--}}
                <div class="col-md-1"></div>
            </div>
        </div>
        <div class="g-items">
            <?php $experiences = @$candidate->experience; ?>
            @if(!empty($experiences))
                @foreach($experiences as $key=>$item)
                    <div class="item" data-number="{{$key}}">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="experience[{{$key}}][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.'.$key.'.from', @$item['from'])}}" placeholder="{{__('MM/YYYY')}}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="experience[{{$key}}][to]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.'.$key.'.to', @$item['to'])}}" placeholder="{{__('MM/YYYY')}}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="experience[{{$key}}][location]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.'.$key.'.location', @$item['location'])}}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="experience[{{$key}}][position]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.'.$key.'.position', @$item['position'])}}">
                            </div>
<!--                            <div class="col-md-2">
                                <textarea name="experience[0][information]" class="form-control" >{{@$item['information']}}</textarea>
                            </div>-->
                            @if($key !== 0)
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>{{__('Description of Job Responsibilities')}}</strong>
                            </div>
                            <div class="col-md-12">
                                <textarea class="form-control" rows="5" name="experience[{{$key}}][description]" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}">{{old('experience.'.$key.'.description', @$item['description'])}}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="item" data-number="0">
                    <div class="row">
                        <div class="col-md-2">
                            <input required type="text" name="experience[0][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.0.from')}}" placeholder="{{__('MM/YYYY')}}">
                        </div>
                        <div class="col-md-2">
                            <input required type="text" name="experience[0][to]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.0.to')}}" placeholder="{{__('MM/YYYY')}}">
                        </div>
                        <div class="col-md-2">
                            <input required type="text" name="experience[0][location]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.0.location')}}">
                        </div>
                        <div class="col-md-2">
                            <input required type="text" name="experience[0][position]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('experience.0.position')}}">
                        </div>
                        <!--                            <div class="col-md-2">
                                <textarea name="experience[0][information]" class="form-control" >{{@$item['information']}}</textarea>
                            </div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>{{__('Description of Job Responsibilities')}}</strong>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" rows="5" required name="experience[0][description]" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}">{{old('experience.0.description')}}</textarea>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="text-right">
            <span class="btn btn-style-four btn-sm btn-add-item"><i class="fa fa-plus"></i> {{__('Add item')}}</span>
        </div>
        <div class="g-more hide">
            <div class="item" data-number="__number__">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" __name__="experience[__number__][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="" placeholder="{{__('MM/YYYY')}}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" __name__="experience[__number__][to]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="" placeholder="{{__('MM/YYYY')}}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" __name__="experience[__number__][location]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="">
                    </div>
                    <div class="col-md-2">
                        <input type="text" __name__="experience[__number__][position]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="">
                    </div>
<!--                    <div class="col-md-2">
                        <textarea __name__="experience[__number__][information]" class="form-control" value=""></textarea>
                    </div>-->
                    <div class="col-md-1">
                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>{{__('Description of Job Responsibilities')}}</strong>
                    </div>
                    <div class="col-md-12">
                        <textarea class="form-control" rows="5" __name__="experience[__number__][description]" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <h3 class="panel-body-title">{{__('Training and specialties')}}</h3>
    <div class="form-group-item">
        <div class="g-items-header">
            <div class="row">
                <div class="col-md-2">{{__("Date of Certificate Earned")}}</div>
{{--                <div class="col-md-2">{{__("Time to")}}</div>--}}
{{--                <div class="col-md-2">{{__('Location')}}</div>--}}
                <div class="col-md-2">{{__('Name of Training/Certification')}}</div>
{{--                <div class="col-md-2">{{__('More Information')}}</div>--}}
                <div class="col-md-1"></div>
            </div>
        </div>
        <div class="g-items">
            <?php $educations = @$candidate->award; ?>
            @if(!empty($educations))
                @foreach($educations as $key=>$item)
                    <div class="item" data-number="{{$key}}">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="award[{{$key}}][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('award.'.$key.'.from', @$item['from'])}}" placeholder="{{__('MM/YYYY')}}">
                            </div>
{{--                            <div class="col-md-2">--}}
{{--                                <input type="text" name="award[{{$key}}][to]" class="form-control" value="{{@$item['to']}}" placeholder="{{__('MM/YYYY')}}">--}}
{{--                            </div>--}}
{{--                            <div class="col-md-2">--}}
{{--                                <input type="text" name="award[{{$key}}][location]" class="form-control" value="{{@$item['location']}}">--}}
{{--                            </div>--}}
                            <div class="col-md-2">
                                <input type="text" name="award[{{$key}}][reward]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('award.'.$key.'.reward', @$item['reward'])}}">
                            </div>
{{--                            <div class="col-md-2">--}}
{{--                                <textarea name="award[{{$key}}][information]" class="form-control" >{{@$item['information']}}</textarea>--}}
{{--                            </div>--}}
                            @if($key !== 0)
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="item" data-number="0">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="award[0][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('award.0.from')}}" placeholder="{{__('MM/YYYY')}}">
                        </div>
                        {{--                            <div class="col-md-2">--}}
                        {{--                                <input type="text" name="award[{{$key}}][to]" class="form-control" value="{{@$item['to']}}" placeholder="{{__('MM/YYYY')}}">--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-md-2">--}}
                        {{--                                <input type="text" name="award[{{$key}}][location]" class="form-control" value="{{@$item['location']}}">--}}
                        {{--                            </div>--}}
                        <div class="col-md-2">
                            <input type="text" name="award[0][reward]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="{{old('award.0.reward')}}">
                        </div>
                        {{--                            <div class="col-md-2">--}}
                        {{--                                <textarea name="award[{{$key}}][information]" class="form-control" >{{@$item['information']}}</textarea>--}}
                        {{--                            </div>--}}
                    </div>
                </div>
            @endif
        </div>
        <div class="text-right">
            <span class="btn btn-style-four btn-sm btn-add-item"><i class="fa fa-plus"></i> {{__('Add item')}}</span>
        </div>
        <div class="g-more hide">
            <div class="item" data-number="__number__">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" __name__="award[__number__][from]" class="form-control mask-mm-yyyy" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="" placeholder="{{__('MM/YYYY')}}">
                    </div>
{{--                    <div class="col-md-2">--}}
{{--                        <input type="text" __name__="award[__number__][to]" class="form-control" value="" placeholder="{{__('MM/YYYY')}}">--}}
{{--                    </div>--}}
{{--                    <div class="col-md-2">--}}
{{--                        <input type="text" __name__="award[__number__][location]" class="form-control" value="">--}}
{{--                    </div>--}}
                    <div class="col-md-2">
                        <input type="text" __name__="award[__number__][reward]" class="form-control" onchange="if(typeof onChangeGeneratedAutoSave === 'function') {onChangeGeneratedAutoSave($(this))}" value="">
                    </div>
{{--                    <div class="col-md-2">--}}
{{--                        <textarea __name__="award[__number__][information]" class="form-control" ></textarea>--}}
{{--                    </div>--}}
                    <div class="col-md-1">
                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>



