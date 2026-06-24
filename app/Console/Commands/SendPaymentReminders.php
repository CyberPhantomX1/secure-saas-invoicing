<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminderMail;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPaymentReminders extends Command
{
    protected $signature = 'invoices:send-reminders';
    protected $description = 'Send payment reminder emails for invoices due in 3 days';

    public function handle(): int
    {
        $reminderDate = Carbon::now()->addDays(3)->toDateString();

        $invoices = Invoice::with(['customer', 'user'])
            ->where('status', '!=', 'paid')
            ->where('due_date', $reminderDate)
            ->get();

        $count = 0;

        foreach ($invoices as $invoice) {
            if ($invoice->customer && $invoice->customer->email) {
                try {
                    Mail::to($invoice->customer->email)
                        ->send(new PaymentReminderMail($invoice));

                    $count++;
                    $this->info("Reminder sent for Invoice #{$invoice->invoice_number} to {$invoice->customer->email}");
                } catch (\Exception $e) {
                    $this->error("Failed to send reminder for Invoice #{$invoice->invoice_number}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Total reminders sent: {$count}");

        return self::SUCCESS;
    }
}