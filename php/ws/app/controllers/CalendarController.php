<?php

class CalendarController {
    private function loadCalendarConfig(): array {
        $configPath = __DIR__ . '/../../config/google_calendar.php';
        $config = [];
        if (file_exists($configPath)) {
            $loaded = require $configPath;
            if (is_array($loaded)) {
                $config = $loaded;
            }
        }

        $config['api_key'] = $config['api_key'] ?? getenv('GOOGLE_CALENDAR_API_KEY') ?: '';
        $config['calendar_id'] = $config['calendar_id'] ?? getenv('GOOGLE_CALENDAR_ID') ?: '';
        $config['max_results'] = (int)($config['max_results'] ?? 20);

        if ($config['max_results'] <= 0) {
            $config['max_results'] = 20;
        }

        return $config;
    }

    private function callApi(string $url): array {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
            ]);
            $body = curl_exec($ch);
            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($body === false) {
                throw new RuntimeException('Google Calendar API呼び出しに失敗しました: ' . $err);
            }

            if ($httpCode >= 400) {
                throw new RuntimeException('Google Calendar APIエラー: HTTP ' . $httpCode);
            }

            $decoded = json_decode($body, true);
            if (!is_array($decoded)) {
                throw new RuntimeException('Google Calendar APIのレスポンスを解析できませんでした。');
            }

            return $decoded;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
            ],
        ]);
        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            throw new RuntimeException('Google Calendar API呼び出しに失敗しました。');
        }

        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Google Calendar APIのレスポンスを解析できませんでした。');
        }

        if (!empty($decoded['error'])) {
            $message = $decoded['error']['message'] ?? '不明なエラー';
            throw new RuntimeException('Google Calendar APIエラー: ' . $message);
        }

        return $decoded;
    }

    private function mapEventDate(array $event, string $key): string {
        $dateTime = $event[$key]['dateTime'] ?? null;
        if (!empty($dateTime)) {
            $dt = new DateTime($dateTime);
            $dt->setTimezone(new DateTimeZone('Asia/Tokyo'));
            return $dt->format('Y-m-d H:i');
        }

        $date = $event[$key]['date'] ?? '';
        if ($date !== '') {
            return $date . ' 終日';
        }

        return '';
    }

    public function index() {
        $calendarEvents = [];
        $calendarError = '';

        try {
            $config = $this->loadCalendarConfig();
            $apiKey = trim((string)$config['api_key']);
            $calendarId = trim((string)$config['calendar_id']);
            $maxResults = (int)$config['max_results'];

            if ($apiKey === '' || $calendarId === '') {
                $calendarError = 'Googleカレンダー設定が未登録です。config/google_calendar.php に api_key と calendar_id を設定してください。';
            } else {
                $query = [
                    'key' => $apiKey,
                    'singleEvents' => 'true',
                    'orderBy' => 'startTime',
                    'maxResults' => $maxResults,
                    'timeMin' => gmdate('c'),
                ];

                $url = 'https://www.googleapis.com/calendar/v3/calendars/'
                    . rawurlencode($calendarId)
                    . '/events?'
                    . http_build_query($query);

                $response = $this->callApi($url);
                $items = $response['items'] ?? [];
                if (!is_array($items)) {
                    $items = [];
                }

                foreach ($items as $event) {
                    $calendarEvents[] = [
                        'summary' => (string)($event['summary'] ?? '(タイトルなし)'),
                        'start' => $this->mapEventDate($event, 'start'),
                        'end' => $this->mapEventDate($event, 'end'),
                        'location' => (string)($event['location'] ?? ''),
                        'description' => (string)($event['description'] ?? ''),
                        'link' => (string)($event['htmlLink'] ?? ''),
                    ];
                }
            }
        } catch (Throwable $e) {
            $calendarError = $e->getMessage();
            $calendarEvents = [];
        }

        include __DIR__ . '/../views/calendar.php';
    }
}
