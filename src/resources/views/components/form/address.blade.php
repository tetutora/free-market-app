<div>
    @props([
        'prefix' => 'addresses[0]',
        'address' => null,
        'errors' => null,
        'label' => null
    ])

    <div class="address-form mt-4">
        @if ($label)
            <h4>{{ $label }}</h4>
        @endif

        <x-form.input 
            label="郵便番号" 
            name="postal_code" 
            :value="old('postal_code', optional($address)->postal_code)" 
            :error="$errors->first('postal_code')" 
        />

        <x-form.input 
            label="都道府県" 
            name="prefecture" 
            :value="old('prefecture', optional($address)->prefecture)" 
            :error="$errors->first('prefecture')" 
        />

        <x-form.input 
            label="市区町村" 
            name="city" 
            :value="old('city', optional($address)->city)" 
            :error="$errors->first('city')" 
        />

        <x-form.input 
            label="番地" 
            name="street" 
            :value="old('street', optional($address)->street)" 
            :error="$errors->first('street')" 
        />

        <x-form.input 
            label="建物名" 
            name="building" 
            :value="old('building', optional($address)->building)" 
            :error="$errors->first('building')" 
        />
    </div>
</div>
