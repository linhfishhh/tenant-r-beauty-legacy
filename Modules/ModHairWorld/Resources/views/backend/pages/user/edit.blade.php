<fieldset>
    <legend class="text-semibold">
        <i class="icon-phone position-left"></i>
        {{__('Thông tin liên hệ')}}
    </legend>
    <div class="form-group">
        <label class="control-label col-lg-3">
            {{__('Số điện thoại')}}
            @if(!$model)
                <span class="text-danger">*</span>
            @endif
        </label>
        <div class="col-lg-9">
            <input value="{!! $model?$model->phone:'' !!}" name="phone" class="form-control" type="text" spellcheck="false"
                   placeholder="{{__('Số điện thoại liên hệ')}}">
        </div>
    </div>
</fieldset>