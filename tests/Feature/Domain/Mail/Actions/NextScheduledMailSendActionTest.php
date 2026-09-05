<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Carbon\Carbon;
use src\Domain\Mail\Actions\NextScheduledMailSendAction;
use Tests\TestCase;

class NextScheduledMailSendActionTest extends TestCase
{
    public function test_it_returns_current_time_on_five_minute_boundary(): void
    {
        $now = Carbon::create(2026, 9, 5, 10, 5, 0);

        $this->assertSame('10:05', NextScheduledMailSendAction::scheduledAtFormatted($now));
    }

    public function test_it_returns_next_slot_one_second_after_boundary(): void
    {
        $now = Carbon::create(2026, 9, 5, 10, 5, 1);

        $this->assertSame('10:10', NextScheduledMailSendAction::scheduledAtFormatted($now));
    }

    public function test_it_returns_next_slot_mid_interval(): void
    {
        $now = Carbon::create(2026, 9, 5, 10, 7, 20);

        $this->assertSame('10:10', NextScheduledMailSendAction::scheduledAtFormatted($now));
    }

    public function test_it_returns_next_slot_at_end_of_interval(): void
    {
        $now = Carbon::create(2026, 9, 5, 10, 9, 59);

        $this->assertSame('10:10', NextScheduledMailSendAction::scheduledAtFormatted($now));
    }

    public function test_it_wraps_to_midnight(): void
    {
        $now = Carbon::create(2026, 9, 5, 23, 57, 10);

        $this->assertSame('00:00', NextScheduledMailSendAction::scheduledAtFormatted($now));
    }
}
