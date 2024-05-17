<div class="form-group">
    <label> {{ __('Name')}}</label>
    <input type="text" value="{{$translation->name}}" placeholder="Position name" name="name" class="form-control">
</div>
@if(is_default_lang())
    <label> {{ __('description')}}</label>
    <input type="text" value="{{$row->description}}" placeholder="Description" name="description" class="form-control">
    <div class="form-group">
        <label> {{ __('Slug')}}</label>
        <input type="text" value="{{$row->slug}}" placeholder="Position slug" name="slug" class="form-control">
    </div>
@endif
