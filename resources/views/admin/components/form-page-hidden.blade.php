@if(request()->filled('page'))
    <input type="hidden" name="page" value="{{ request('page') }}">
@endif
