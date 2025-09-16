<?php

namespace App\Mail;

use App\Models\Customers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PropertiesForCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public Customers $customer;

    public Collection $properties;

    /**
     * Create a new message instance.
     */
    public function __construct(Customers $customer, Collection $properties)
    {
        $this->customer = $customer;
        $this->properties = $properties;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Ajánlott ingatlanok')
            ->view('emails.properties_for_customer')
            ->with([
                'customer' => $this->customer,
                'properties' => $this->properties,
            ]);
    }
}
