<select @isset($id) id="{!! $id !!}" @endisset name="{!! $name !!}" class="{!! isset($classes)?$classes:'' !!}">
    @isset($has_none)
        <option value="00">{{__('Tất cả ngôn ngữ')}}</option>
    @endisset
    @foreach(getLanguagesInfo() as $lang_code=> $lang_title)
            <option value="{{$lang_code}}">{{$lang_title}}</option>
    @endforeach
    @isset($has_unsupported)
        <option value="11">{{__('Không hổ trợ')}}</option>
    @endisset
</select>