document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.getAttribute('data-tab');

            // すべてのボタンからactiveクラスを外す
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // クリックしたボタンにactive付与
            button.classList.add('active');

            // すべてのコンテンツ非表示にする
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });

            // 対象のタブだけ表示
            const activeTab = document.getElementById(target);
            if (activeTab) {
                activeTab.classList.add('active');
                activeTab.style.display = 'block';
            }
        });
    });

    // 初期状態の表示設定
    tabContents.forEach(content => {
        if (!content.classList.contains('active')) {
            content.style.display = 'none';
        }
    });
});
