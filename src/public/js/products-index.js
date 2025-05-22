document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('search-form');
    let debounceTimer;
    const keywordInput = document.getElementById('keyword-input');
    keywordInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 500);
    });

    ['parent-category', 'child-category', 'brand-select', 'is-listed-select'].forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            select.addEventListener('change', () => {
                form.submit();
            });
        }
    });

    const parentSelect = document.getElementById('parent-category');
    const childSelect = document.getElementById('child-category');

    const selectedParentId = parentSelect.value;
    const selectedChildId = window.selectedCategoryId || null;

    function loadChildCategories(parentId, selectedChildId = null) {
        childSelect.innerHTML = '<option value="">子カテゴリを選択</option>';
        childSelect.disabled = true;

        if (!parentId) return;

        fetch(`/api/categories/${parentId}/children`)
            .then(response => response.json())
            .then(data => {
                if (data.length) {
                    data.forEach(child => {
                        const option = document.createElement('option');
                        option.value = child.id;
                        option.textContent = child.name;
                        if (selectedChildId && selectedChildId == child.id) {
                            option.selected = true;
                            childSelect.disabled = false;
                        }
                        childSelect.appendChild(option);
                    });
                    childSelect.disabled = false;
                }
            })
            .catch(error => console.error('子カテゴリ取得エラー:', error));
    }

    if (selectedParentId) {
        loadChildCategories(selectedParentId, selectedChildId);
    }

    parentSelect.addEventListener('change', function () {
        loadChildCategories(this.value);
        childSelect.value = '';
    });
});
