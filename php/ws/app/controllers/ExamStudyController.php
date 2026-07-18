<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/ExamStudy.php';

class ExamStudyController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $pdo = Database::getInstance();
        $examList = ExamStudy::getExamList($pdo);
        $requestedExamId = isset($_GET['exam_id']) ? trim((string)$_GET['exam_id']) : '';
        if ($requestedExamId === '' && !empty($examList)) {
            $requestedExamId = (string)($examList[0]['試験ID'] ?? '');
        }
        $examId = $requestedExamId;
        $exam = null;
        if ($examId !== '') {
            $exam = ExamStudy::getExamById($pdo, $examId);
        }
        if ($exam === null && !empty($examList)) {
            $examId = (string)($examList[0]['試験ID'] ?? '');
            if ($examId !== '') {
                $exam = ExamStudy::getExamById($pdo, $examId);
            }
        }

        $statsSessionKey = 'exam_study_stats_' . $examId;
        if (!isset($_SESSION[$statsSessionKey]) || !is_array($_SESSION[$statsSessionKey])) {
            $_SESSION[$statsSessionKey] = [
                'correct_count' => 0,
                'incorrect_count' => 0,
                'last_counted_answer' => null,
            ];
        }

        if (isset($_GET['reset_stats']) && $_GET['reset_stats'] === '1') {
            $_SESSION[$statsSessionKey] = [
                'correct_count' => 0,
                'incorrect_count' => 0,
                'last_counted_answer' => null,
            ];
            header('Location: ?action=exam_study&exam_id=' . urlencode($examId));
            exit;
        }

        $question = null;
        $choices = [];

        $selectedChoiceNo = null;
        $correctChoiceNo = null;
        $correctChoiceText = '';
        $isAnswered = false;
        $isCorrect = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postedQuestionId = isset($_POST['question_id']) ? trim((string)$_POST['question_id']) : '';
            if ($postedQuestionId !== '') {
                $questionSql = "
                    select
                        連番,
                        試験ID,
                        問題ID,
                        問題種別,
                        問題文,
                        問題文_ファイル名,
                        解答,
                        解答文_ファイル名,
                        解説,
                        解説文_ファイル名,
                        表示順
                    from 試験問題
                    where 削除F = '0'
                      and 試験ID = ?
                      and 問題ID = ?
                    limit 1
                ";
                $questionStmt = $pdo->prepare($questionSql);
                $questionStmt->execute([$examId, $postedQuestionId]);
                $fetched = $questionStmt->fetch();
                $question = $fetched ?: null;
            }
        } elseif ($examId !== '') {
            $question = ExamStudy::getRandomTfQuestion($pdo, $examId);
        }

        if ($question === null && $examId !== '') {
            $question = ExamStudy::getFirstTfQuestion($pdo, $examId);
        }

        if ($question !== null) {
            $questionId = (string)($question['問題ID'] ?? '');
            if ($questionId !== '') {
                $choices = ExamStudy::getChoices($pdo, $examId, $questionId);
            }
        }

        foreach ($choices as $choice) {
            if (($choice['正解F'] ?? '') === '1') {
                $correctChoiceNo = (int)$choice['選択肢番号'];
                $correctChoiceText = (string)($choice['選択肢文'] ?? '');
                break;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedChoiceNo = isset($_POST['selected_choice']) ? (int)$_POST['selected_choice'] : null;
            if ($selectedChoiceNo !== null && $correctChoiceNo !== null) {
                $isAnswered = true;
                $isCorrect = ($selectedChoiceNo === $correctChoiceNo);
                $countSignature = $examId . '|' . (string)($question['問題ID'] ?? '') . '|' . (string)$selectedChoiceNo;
                if (($_SESSION[$statsSessionKey]['last_counted_answer'] ?? null) !== $countSignature) {
                    if ($isCorrect) {
                        $_SESSION[$statsSessionKey]['correct_count']++;
                    } else {
                        $_SESSION[$statsSessionKey]['incorrect_count']++;
                    }
                    $_SESSION[$statsSessionKey]['last_counted_answer'] = $countSignature;
                }
            }
        } else {
            $_SESSION[$statsSessionKey]['last_counted_answer'] = null;
        }

        $correctCount = (int)($_SESSION[$statsSessionKey]['correct_count'] ?? 0);
        $incorrectCount = (int)($_SESSION[$statsSessionKey]['incorrect_count'] ?? 0);
        $totalAnswered = $correctCount + $incorrectCount;
        $accuracyRate = $totalAnswered > 0 ? round(($correctCount / $totalAnswered) * 100, 1) : 0;

        include __DIR__ . '/../views/exam_study.php';
    }
}
