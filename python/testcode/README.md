# testcode  

## 実行方法  

`settings_sample.json` を基に MySQLのDB情報を記載した `settings.json` を作成する。  

基本  

```
pytest
```

print文も表示する  

```
pytest -s
```

テキストに書き出す  

```
pytest > result.txt
pytest -v > result.txt
```
