<?php

return [
    'voting_days' => env('PETITION_VOTING_DAYS', 90) * 86400,
    'votes_count_for_success' => env('PETITION_VOTES_COUNT_FOR_SUCCESS', 3000), // 90 days

    'minutes_for_edit' => env('PETITION_MINUTES_FOR_EDIT', 60),
    'comment_minutes_for_edit' => env('PETITION_COMMENT_MINUTES_FOR_EDIT', 15),

];
