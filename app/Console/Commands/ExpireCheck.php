<?php

namespace App\Console\Commands;

use App\Events\SendMail;
use App\Models\CustomerPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check expiry date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emailTemplate = get_email_template('plan_expired');
        if (!$emailTemplate) {
            return Command::FAILURE;
        }
        $customerPlans = CustomerPlan::where('status','accepted')->where('is_current','yes')->whereNotNull('renew_date')->where('expiry_notified','no')->where('renew_date','<=',Carbon::now()->addDay(5))->limit(100)->get();
        foreach ($customerPlans as $customerPlan){
            if (isset($customerPlan->renew_date) && $customerPlan->renew_date) {
                $customerPlanPending = CustomerPlan::where('customer_id',$customerPlan->customer->id)->where('status','pending')->first();
                $date = Carbon::now();
                $renewDate = $customerPlan->renew_date;
                $diffDate = $renewDate->diff($date);
                $days = $diffDate->format('%a');
                if (isset($customerPlan->recurring_type) && $customerPlan->recurring_type == 'monthly' || $customerPlan->recurring_type == 'yearly') {
                    if ($days <= 5) {
                        if (!$customerPlanPending){
                            $customerPlan->customer->plans()->create(['plan_id' => $customerPlan->plan->id, 'sms_limit' => $customerPlan->plan->sms_limit, 'price' => $customerPlan->plan->price,'contact_limit' => $customerPlan->plan->contact_limit,'device_limit' => $customerPlan->plan->device_limit,'daily_receive_limit' => $customerPlan->plan->daily_receive_limit,'daily_send_limit' => $customerPlan->plan->daily_send_limit,'is_current' => 'no','payment_status' => 'unpaid','status' => 'pending','renew_date'=>null,'recurring_type'=>$customerPlan->plan->recurring_type]);
                        }
                        try {
                            $regTemp = str_replace('{customer_name}', $customerPlan->customer->first_name . ' ' . $customerPlan->customer->last_name, $emailTemplate->body);
                            $route = route('paymentgateway::email.payment.process', ['id' => $customerPlan->plan_id]);
                            $regTemp = str_replace('{click_here}', "<a href=" . $route . ">" . trans('pay now') . "</a>", $regTemp);
                            $regTemp = str_replace('{plan_expired_date}', $renewDate, $regTemp);
                            SendMail::dispatch($customerPlan->customer->email, $emailTemplate->subject, $regTemp);
                            $customerPlan->update(['expiry_notified' => 'yes']);
                        } catch (\Exception $ex) {
                            Log::info($ex);
                            return Command::FAILURE;
                        }
                    }
                }else{
                    if ($days < 2) {
                        if (!$customerPlanPending){
                            $customerPlan->customer->plans()->create(['plan_id' => $customerPlan->plan->id, 'sms_limit' => $customerPlan->plan->sms_limit, 'price' => $customerPlan->plan->price,'contact_limit' => $customerPlan->plan->contact_limit,'device_limit' => $customerPlan->plan->device_limit,'daily_receive_limit' => $customerPlan->plan->daily_receive_limit,'daily_send_limit' => $customerPlan->plan->daily_send_limit,'is_current' => 'no','payment_status' => 'unpaid','status' => 'pending','renew_date'=>null,'recurring_type'=>$customerPlan->plan->recurring_type]);
                        }

                        try {
                            $regTemp = str_replace('{customer_name}', $customerPlan->customer->first_name . ' ' . $customerPlan->customer->last_name, $emailTemplate->body);
                            $route = route('paymentgateway::email.payment.process', ['id' => $customerPlan->plan_id]);
                            $regTemp = str_replace('{click_here}', "<a href=" . $route . ">" . trans('pay now') . "</a>", $regTemp);
                            $regTemp = str_replace('{plan_expired_date}', $renewDate, $regTemp);
                            SendMail::dispatch($customerPlan->customer->email, $emailTemplate->subject, $regTemp);
                            $customerPlan->update(['expiry_notified' => 'yes']);
                        } catch (\Exception $ex) {
                            Log::info($ex);
                            return Command::FAILURE;
                        }
                    }
                }
            }

        }
        return Command::SUCCESS;
    }
}
