<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateShipmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $accountId;
    public $loadingCityId;
    public $unloadingCityId;
    public $vehicleTypeId;
    public $goodsId;
    public $serial_number;
   

    public function __construct($data)
    {
        // تعيين البيانات المرسلة
        $this->accountId = $data['account_id'];
        $this->loadingCityId = $data['loadingCity'];
        $this->unloadingCityId = $data['unloadingCity'];
        $this->vehicleTypeId = $data['vehicleType'];
        $this->goodsId = $data['goods'];
        $this->serial_number = $data['serial_number'];
       
        
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'تم انشاء طلب جديد'
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.create_shipment',
            with: [
                'accountId' => $this->accountId,
                'loadingCityId' => $this->loadingCityId,
                'unloadingCityId' => $this->unloadingCityId,
                'vehicleTypeId' => $this->vehicleTypeId,
                'goodsId' => $this->goodsId,
                'serial_number' => $this->serial_number
               
            ]
        );

       
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
