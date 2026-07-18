<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <title>試験学習</title>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <h2>試験学習</h2>
    <?php
        $examList = $examList ?? [];
        $exam = $exam ?? null;
        $examId = (string)($exam['試験ID'] ?? ($_GET['exam_id'] ?? ''));
        $question = $question ?? null;
        $choices = $choices ?? [];
        $selectedChoiceNo = $selectedChoiceNo ?? null;
        $isAnswered = $isAnswered ?? false;
        $isCorrect = $isCorrect ?? false;
        $correctChoiceText = $correctChoiceText ?? '';
        $correctCount = $correctCount ?? 0;
        $incorrectCount = $incorrectCount ?? 0;
        $accuracyRate = $accuracyRate ?? 0;
        $questionTypeLabelMap = [
            'TF' => '〇×問題',
            'MC3' => '3択問題',
            'MC4' => '4択問題',
        ];
        $questionType = (string)($question['問題種別'] ?? '');
        $questionTypeLabel = $questionTypeLabelMap[$questionType] ?? '問題';
    ?>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
    </div>

    <div class="card mb-3" style="max-width: 1100px;">
        <div class="card-body py-2">
            <form method="get" action="" class="row g-2 align-items-center">
                <input type="hidden" name="action" value="exam_study">
                <div class="col-auto">
                    <label for="exam_id" class="col-form-label">試験選択</label>
                </div>
                <div class="col-auto">
                    <select id="exam_id" name="exam_id" class="form-select" onchange="this.form.submit()">
                        <?php foreach ($examList as $item): ?>
                            <?php
                                $itemExamId = (string)($item['試験ID'] ?? '');
                                $itemExamName = (string)($item['試験名'] ?? '');
                                $isSelected = ($itemExamId !== '' && $itemExamId === $examId);
                            ?>
                            <option value="<?= htmlspecialchars($itemExamId) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                <?= htmlspecialchars($itemExamId . ': ' . $itemExamName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <noscript><button type="submit" class="btn btn-primary btn-sm">選択</button></noscript>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-2 mb-3" style="max-width: 1100px;">
        <div class="col-auto">
            <div class="card">
                <div class="card-body py-2 px-3"><strong>正解数:</strong> <?= htmlspecialchars((string)$correctCount) ?></div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card">
                <div class="card-body py-2 px-3"><strong>不正解数:</strong> <?= htmlspecialchars((string)$incorrectCount) ?></div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card">
                <div class="card-body py-2 px-3"><strong>正答率:</strong> <?= htmlspecialchars(number_format((float)$accuracyRate, 1)) ?>%</div>
            </div>
        </div>
        <div class="col-auto d-flex align-items-center">
            <a class="btn btn-outline-secondary" href="?action=exam_study&amp;exam_id=<?= urlencode($examId) ?>&amp;reset_stats=1">リセット</a>
        </div>
    </div>

    <?php if ($exam): ?>
        <div class="mb-3">
            <div class="fw-bold"><?= htmlspecialchars($exam['試験名'] ?? '') ?></div>
            <div class="text-muted small"><?= htmlspecialchars($exam['試験説明'] ?? '') ?></div>
        </div>
    <?php endif; ?>

    <?php if ($question): ?>
        <div class="card" style="max-width: 1100px;">
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge text-bg-secondary me-2"><?= htmlspecialchars($questionTypeLabel) ?></span>
                    <?php if (!empty($question['表示順'])): ?>
                        <span class="text-muted">No.<?= htmlspecialchars((string)$question['表示順']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($question['問題ID'])): ?>
                        <span class="text-muted ms-2">問題ID: <?= htmlspecialchars((string)$question['問題ID']) ?></span>
                    <?php endif; ?>
                </div>
                <p class="mb-2" style="white-space: pre-wrap;"><?= htmlspecialchars($question['問題文'] ?? '') ?></p>
                <?php if (!empty($question['問題文_ファイル名'])): ?>
                    <div class="small text-muted mb-3">問題画像: <?= htmlspecialchars($question['問題文_ファイル名']) ?></div>
                <?php endif; ?>

                <form method="post" action="?action=exam_study&amp;exam_id=<?= urlencode($examId) ?>">
                    <input type="hidden" name="question_id" value="<?= htmlspecialchars((string)($question['問題ID'] ?? '')) ?>">
                    <div class="mb-3">
                        <?php foreach ($choices as $choice): ?>
                            <?php $choiceNo = (int)($choice['選択肢番号'] ?? 0); ?>
                            <div class="form-check mb-1">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="selected_choice"
                                    id="choice_<?= htmlspecialchars((string)$choiceNo) ?>"
                                    value="<?= htmlspecialchars((string)$choiceNo) ?>"
                                    <?= ($selectedChoiceNo !== null && (int)$selectedChoiceNo === $choiceNo) ? 'checked' : '' ?>
                                    required
                                >
                                <label class="form-check-label" for="choice_<?= htmlspecialchars((string)$choiceNo) ?>">
                                    <?= htmlspecialchars(($choice['選択肢番号'] ?? '') . '. ' . ($choice['選択肢文'] ?? '')) ?>
                                </label>
                            </div>
                            <?php if (!empty($choice['選択肢_ファイル名'])): ?>
                                <div class="small text-muted ms-4 mb-1">選択肢画像: <?= htmlspecialchars($choice['選択肢_ファイル名']) ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">回答する</button>
                </form>
            </div>
        </div>

        <?php if ($isAnswered): ?>
            <div class="card mt-3" style="max-width: 1100px;">
                <div class="card-body">
                    <div class="alert <?= $isCorrect ? 'alert-success' : 'alert-danger' ?> mb-3" role="alert">
                        <?= $isCorrect ? '正解です。' : '不正解です。' ?>
                    </div>
                    <div class="mb-2"><strong>正解:</strong> <?= htmlspecialchars($correctChoiceText) ?></div>
                    <?php if (!empty($question['解答'])): ?>
                        <div class="mb-2"><strong>解答:</strong> <?= htmlspecialchars($question['解答']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($question['解答文_ファイル名'])): ?>
                        <div class="small text-muted mb-2">解答画像: <?= htmlspecialchars($question['解答文_ファイル名']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($question['解説'])): ?>
                        <div class="mt-2"><strong>解説:</strong></div>
                        <div style="white-space: pre-wrap;"><?= htmlspecialchars($question['解説']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($question['解説文_ファイル名'])): ?>
                        <div class="small text-muted mt-2">解説画像: <?= htmlspecialchars($question['解説文_ファイル名']) ?></div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <a class="btn btn-outline-primary" href="?action=exam_study&amp;exam_id=<?= urlencode($examId) ?>&amp;next=1">次へ</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning" role="alert" style="max-width: 1100px;">
            試験問題が見つかりませんでした。
        </div>
    <?php endif; ?>
</body>
</html>
