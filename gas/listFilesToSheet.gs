// フォルダ内のファイル一覧を取得
function listFilesToSheet() {
  const sheetName = 'ファイル一覧';
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  let sheet = ss.getSheetByName(sheetName);

  if (!sheet) {
    sheet = ss.insertSheet(sheetName);
  }

  // A2セルからフォルダIDを取得
  const folderId = sheet.getRange("A2").getValue();
  if (!folderId) {
    SpreadsheetApp.getUi().alert("A2セルにフォルダIDを入力してください。");
    return;
  }

  // フォルダ取得
  let folder;
  try {
    folder = DriveApp.getFolderById(folderId);
  } catch (e) {
    SpreadsheetApp.getUi().alert("フォルダIDが正しくありません。");
    return;
  }

  // フォルダ名をB2セルに表示
  sheet.getRange("B2").setValue(folder.getName());

  const files = folder.getFiles();

  // A3以降をクリア（A2とB2は残す）
  sheet.getRange("A3:Z9999").clear();

  // 3行目にヘッダー
  sheet.getRange(3, 1, 1, 6).setValues([
    ['ファイル名', 'URL', 'Excelダウンロード', '最終更新者', '更新日', 'サイズ']
  ]);

  const rows = [];

  while (files.hasNext()) {
    const file = files.next();
    const fileId = file.getId();

    // Excel形式でダウンロードできるURL
    const xlsxUrl = `https://docs.google.com/uc?export=download&id=${fileId}&format=xlsx`;

    // 最終更新者（Drive API）
    let lastUserName = '取得不可';
    try {
      const fileInfo = Drive.Files.get(fileId, { fields: "lastModifyingUser" });
      if (fileInfo.lastModifyingUser && fileInfo.lastModifyingUser.displayName) {
        lastUserName = fileInfo.lastModifyingUser.displayName;
      }
    } catch (e) {
      // API未設定や権限不足の場合は取得不可
    }

    rows.push([
      file.getName(),
      file.getUrl(),
      xlsxUrl,
      lastUserName,
      file.getLastUpdated(),
      file.getSize()
    ]);
  }

  if (rows.length > 0) {
    sheet.getRange(4, 1, rows.length, 6).setValues(rows);
  }
}
