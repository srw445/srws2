<?php
return [
    // 例: set GOOGLE_CALENDAR_API_KEY=xxxx (Windows)
    'api_key' => getenv('GOOGLE_CALENDAR_API_KEY') ?: '',

    // 例: your_calendar_id@group.calendar.google.com
    // 例: set GOOGLE_CALENDAR_ID=xxxx@group.calendar.google.com (Windows)
    'calendar_id' => getenv('GOOGLE_CALENDAR_ID') ?: '',

    // 例: set GOOGLE_CALENDAR_MAX_RESULTS=20 (Windows)
    'max_results' => (int)(getenv('GOOGLE_CALENDAR_MAX_RESULTS') ?: 20),
];
