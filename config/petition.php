<?php

return [
    'voting_days' => (int) env('PETITION_VOTING_DAYS', 90) * 86400, // 90 days
    'votes_count_for_success' => (int) env('PETITION_VOTES_COUNT_FOR_SUCCESS', 300), // 300 votes

    'minutes_for_edit' => (int) env('PETITION_MINUTES_FOR_EDIT', 60),
    'comment_minutes_for_edit' => (int) env('PETITION_COMMENT_MINUTES_FOR_EDIT', 15),

];
