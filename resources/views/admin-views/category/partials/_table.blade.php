@foreach($categories as $key=>$category)
<tr>
    <td>{{$key+1}}</td>
    <td>{{$category->id}}</td>
    <td>
    <span class="d-block font-size-sm text-body">
        {{$category['name']}}
    </span>
    </td>
    <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$category->id}}">
        <input type="checkbox" onclick="location.href='{{route('admin.category.status',[$category['id'],$category->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$category->id}}" {{$category->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
    </td>
</tr>
@endforeach
