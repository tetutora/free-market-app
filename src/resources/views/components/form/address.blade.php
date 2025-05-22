<div>
    <!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
</div>

@props([
    'prefix' => 'addresses[0]', // name属性の接頭語
    'address' => null,          // 既存住所データ（nullable）
    'errors' => null,           // エラーコレクション（$errors を渡す）
    'label' => null             // 住所タイトル（例: 住所1）
])

<div class="address-form mt-4">
    @if ($label)
        <h4>{{ $label }}</h4>
    @endif

    <x-form.input 
        label="郵便番号" 
        name="{{ $prefix }}[postal_code]" 
        :value="old($prefix . '.postal_code', optional($address)->postal_code)" 
        :error="$errors->first($prefix . '.postal_code')" 
    />

    <x-form.input 
        label="都道府県" 
        name="{{ $prefix }}[prefecture]" 
        :value="old($prefix . '.prefecture', optional($address)->prefecture)" 
        :error="$errors->first($prefix . '.prefecture')" 
    />

    <x-form.input 
        label="市区町村" 
        name="{{ $prefix }}[city]" 
        :value="old($prefix . '.city', optional($address)->city)" 
        :error="$errors->first($prefix . '.city')" 
    />

    <x-form.input 
        label="番地" 
        name="{{ $prefix }}[street]" 
        :value="old($prefix . '.street', optional($address)->street)" 
        :error="$errors->first($prefix . '.street')" 
    />

    <x-form.input 
        label="建物名" 
        name="{{ $prefix }}[building]" 
        :value="old($prefix . '.building', optional($address)->building)" 
        :error="$errors->first($prefix . '.building')" 
    />
</div>
