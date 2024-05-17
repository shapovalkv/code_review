<div class="form-group row">
    <div class="col-md-3 col-form-label text-right"><label>{{__("Title")}} <span class="text-danger">*</span></label></div>
    <div class="col-md-9">
        <input type="text" value="{{old('title',$translation->title)}}" required placeholder="{{__("Name of the Announcement")}}" name="title" class="form-control">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3 col-form-label text-right"><label>{{__("Choose Date (Applies for trainings)")}}</label></div>
    <div class="col-md-9">
        <input type="text" required value="{{ old( 'announcement_date', $row->announcement_date ? date(get_date_format(), strtotime($row->announcement_date)) : '') }}" placeholder="MM/DD/YYYY" name="announcement_date" autocomplete="false" class="form-control has-datepicker bg-white">
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3 col-form-label text-right"><label>{{__("Expiration date")}}<span class="text-danger">*</span></label></div>
    <div class="col-md-9">
        <input type="text" required value="{{ old( 'expiration_date', $row->expiration_date ? date(get_date_format(), strtotime($row->expiration_date)) : '') }}" placeholder="MM/DD/YYYY" name="expiration_date" autocomplete="false" class="form-control has-datepicker bg-white">
    </div>
</div>
