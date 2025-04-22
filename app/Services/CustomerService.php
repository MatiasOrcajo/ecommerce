<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{


    /**
     * Crea un registro Customer (cliente)
     *  Se crea con los datos que el usuario ingresó en el checkout
     *
     *
     * @param Object $customerData
     * @return Customer
     */
    public function create(Object $customerData)
    {
        $customer = Customer::where('dni', $customerData->dni)->first();

        if ($customer == null) {

            $customer               = new Customer();
            $customer->name         = $customerData->name;
            $customer->surname      = $customerData->surname;
            $customer->email        = $customerData->email;
            $customer->phone        = $customerData->phone;
            $customer->dni  = $customerData->dni;
            $customer->save();
        }

        return $customer;
    }

}
