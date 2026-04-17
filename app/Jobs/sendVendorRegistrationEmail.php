<?php

namespace App\Jobs;
use App;
use Mail;
use Config;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use App\Models\ClientPreference;
use App\Mail\VendorRegistrationMail;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class sendVendorRegistrationEmail implements ShouldQueue{
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details){
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $this->details = $details;
            $this->client_name = $client->name;
            $this->mail_from = $data->mail_from;
            $this->client_logo = $client->logo['original'];
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $email = new VendorRegistrationMail($this->details);
        try{
            Mail::to($this->details['email'])->send($email);
        }catch(Exception $e){

        }
    }
    public function setMailDetail($mail_driver, $mail_host, $mail_port, $mail_username, $mail_password, $mail_encryption){
        $config = array(
            'pretend' => false,
            'host' => $mail_host,
            'port' => $mail_port,
            'driver' => $mail_driver,
            'username' => $mail_username,
            'password' => $mail_password,
            'encryption' => $mail_encryption,
            'sendmail' => '/usr/sbin/sendmail -bs',
        );
        Config::set('mail', $config);
        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
        return true;
    }
}
