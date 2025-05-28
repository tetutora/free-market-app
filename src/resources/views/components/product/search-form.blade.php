<form method="GET" action="{{ route('products.index') }}" id="search-form">
    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワード" id="keyword-input">

    <select id="parent-category" name="parent_category_id">
        <option value="" {{ request('parent_category_id') == '' ? 'selected' : '' }}>ジャンルを選択</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('parent_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select id="child-category" name="category_id" {{ request('parent_category_id') ? '' : 'disabled' }}>
        <option value="">サブジャンルを選択</option>
    </select>

    <select name="brand_id" id="brand-select">
        <option value="">ブランドを選択</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>

    <select name="is_listed" id="is-listed-select">
        <option value="">販売状況</option>
        <option value="1" {{ request('is_listed') === '1' ? 'selected' : '' }}>販売中</option>
        <option value="0" {{ request('is_listed') === '0' ? 'selected' : '' }}>売り切れ</option>
    </select>
</form>
