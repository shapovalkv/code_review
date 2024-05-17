<div class="form-group row">
    <div class="col-md-3 col-form-label text-right"><label>{{__("Title")}} <span class="text-danger">*</span></label></div>
    <div class="col-md-9">
        <input type="text" value="{{old('title',$translation->title)}}" required placeholder="{{__("Name of the equipment")}}" name="title" class="form-control">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3 col-form-label text-right"><label>{{__("Price")}} <span class="text-danger">*</span></label></div>
    <div class="col-md-9">
        <input type="number" name="price" class="form-control" value="{{old('price',$row->price)}}" required value="{{$row->price}}" placeholder="{{__('Price')}}">
    </div>
</div>
