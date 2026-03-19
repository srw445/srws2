// ID→PASS→ログイン（ID指定）
// javascript:(function(){document.getElementById('xxx').value='xxx';document.getElementById('yyy').value='yyy';document.getElementById('zzz').click();})();

// ID→PASS→ログイン（class指定）
// javascript:(function(){document.getElementById('xxx').value='xxx';document.getElementById('yyy').value='yyy';document.getElementsByClassName('zzz')[0].click();})();

// ローカルのjsファイルを参照
// javascript:(function(){var s=document.createElement('script');s.src='http://localhost:441/ws/bookmarkret.js';s.onload=function(){if(typeof func==='function'){func();}};document.body.appendChild(s);})();

function func() {
    alert("func() が実行されました！");
}
