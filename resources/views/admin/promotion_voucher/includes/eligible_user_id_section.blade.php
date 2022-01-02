<!-- TO BE MADE dynamically dependent to user type dropdown: For now all users are shown -->

<select name="eligible_user_ids[]" id="eligible_user_ids" class="form-control select2 w-100" multiple @error('eligible_user_ids')
is-invalid @enderror>

@foreach($users as $user)
<option value="{{$user->id}}"
    @if (isset($promotion_voucher->eligible_user_ids))
            @if (in_array($user->id, $promotion_voucher->eligible_user_ids) )) selected @endif
    @endif
    @if (old('eligible_user_ids'))
        @if (in_array($user->id, old('eligible_user_ids'))) selected @endif
    @endif
    >  {{ ucwords($user->name) }} (  {{ ucwords($user->phone) }} ) (  {{  hasRole($user->id, 'rider') ? 'rider' : 'customer' }} ) 
    </option>
@endforeach 
</select>

<p class="text-muted" style="font-size:.6rem">
Only these users may use the voucher cards!
</p>

@error('eligible_user_ids')
<span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
</span>
@enderror