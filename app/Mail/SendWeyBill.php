<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendWeyBill extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;
    public $vehicle;
    public $pdfFilePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$pdfFilePath,$vehicle)
    {
        // تعيين البيانات المرسلة
        $this->shipment = $data;
        // $this->loadingCityId = $data['loadingCity'];
        // $this->unloadingCityId = $data['unloadingCity'];
        // $this->vehicleTypeId = $data['vehicleType'];
        // $this->goodsId = $data['goods'];
        // $this->serial_number = $data['serial_number'];
        $this->pdfFilePath = $pdfFilePath;
        $this->vehicle = $vehicle;
       
        
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->shipment->serial_number
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
            view: 'emails.send_weybill',
            with: [
                'shipment' => $this->shipment,
                'vehicle' => $this->vehicle,
                'pdf_file_full_Path' => $this->pdfFilePath
               
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
