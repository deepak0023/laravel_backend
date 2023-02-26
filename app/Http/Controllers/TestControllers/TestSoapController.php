<?php

namespace App\Http\Controllers\TestControllers;

use Illuminate\Http\Request\TestControllers;
use Validator;
use App\Models\TestModel\Comment;
use App\Models\TestModel\Todo;
use App\Http\Controllers\Controller;

class TestSoapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function processSoapRequest(Request $request)
    {


        // recieving xml request

        // dd($request->getContent());   [get SOAP xml value in request]


        // $your_xml_response = $request->getContent();
        // $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $your_xml_response);
        // $xml = simplexml_load_string($clean_xml);

        // dd($xml);


        // calling xml request

        try {

        $soapclient = new \SoapClient('https://apps.learnwebservices.com/services/hello?WSDL');

        $response = $soapclient->HelloRequest(['Name' => 'Deepak']);

        // print_r($response->message);

        } catch(\Exception $e) {

            echo $e->getMessage();

        }

    }

}
