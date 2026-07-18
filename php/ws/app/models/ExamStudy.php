<?php
require_once __DIR__ . '/Database.php';

class ExamStudy {
    public static function getExamList(PDO $pdo): array {
        $sql = "
            select 連番, 試験ID, 試験名, 試験説明
            from 試験マスタ
            where 削除F = '0'
            order by 連番 asc
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getExamById(PDO $pdo, string $examId): ?array {
        $sql = "
            select 連番, 試験ID, 試験名, 試験説明
            from 試験マスタ
            where 削除F = '0'
              and 試験ID = ?
            order by 連番 asc
            limit 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$examId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getFirstTfQuestion(PDO $pdo, string $examId): ?array {
        $sql = "
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
                            and 問題種別 in ('TF', 'MC3', 'MC4')
            order by ifnull(表示順, 999999), 連番
            limit 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$examId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getQuestionByIds(PDO $pdo, string $examId, string $questionId): ?array {
        $sql = "
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
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$examId, $questionId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getRandomTfQuestion(PDO $pdo, string $examId): ?array {
        $sql = "
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
                            and 問題種別 in ('TF', 'MC3', 'MC4')
            order by rand()
            limit 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$examId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getChoices(PDO $pdo, string $examId, string $questionId): array {
        $sql = "
            select
                選択肢番号,
                選択肢文,
                選択肢_ファイル名,
                正解F
            from 試験問題選択肢
            where 削除F = '0'
              and 試験ID = ?
              and 問題ID = ?
            order by 選択肢番号 asc, 連番 asc
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$examId, $questionId]);
        return $stmt->fetchAll();
    }
}
