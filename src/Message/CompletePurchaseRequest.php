<?php

namespace Omnipay\Payeer\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        if ($this->httpRequest->request->get('m_status') != 'success') {
            throw new InvalidResponseException("Invalid m_status:".$this->httpRequest->request->get('m_status'));
        }

        $arHash = [
            $this->httpRequest->request->get('m_operation_id'),
            $this->httpRequest->request->get('m_operation_ps'),
            $this->httpRequest->request->get('m_operation_date'),
            $this->httpRequest->request->get('m_operation_pay_date'),
            $this->httpRequest->request->get('m_shop'),
            $this->httpRequest->request->get('m_orderid'),
            $this->httpRequest->request->get('m_amount'),
            $this->httpRequest->request->get('m_curr'),
            $this->httpRequest->request->get('m_desc'),
            $this->httpRequest->request->get('m_status'),
        ];
		if ($this->httpRequest->request->get('m_params')) {
			$arHash[] = $this->httpRequest->request->get('m_params');
		}
		$arHash[] = $this->getShopSecret();
        $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

        if ($this->httpRequest->request->get('m_sign') != $sign_hash) {
            throw new InvalidResponseException("Invalid m_sign");
        }

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
