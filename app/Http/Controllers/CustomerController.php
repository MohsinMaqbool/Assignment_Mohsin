<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Exports\CsvExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Customer;
use DataTables;
use PDF;

class CustomerController extends Controller
{
    public function index()
    {
    	return view('list');
    }

    public function customers()
    {
    	$customers = Customer::select('id','name','age','address','phone')->orderBy('id', 'desc');
    	return DataTables::of($customers)
	    	   ->addColumn('action', function($customer){
	                return '<a href="javascript:void(0)" class="btn btn-xs btn-primary edit" id="'.$customer->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a><a href="javascript:void(0)" class="btn btn-xs btn-danger delete" id="'.$customer->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a><a href="view-pdf/'.$customer->id.'" targer="_blank" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-file"></i> View-PDF</a>';
	            })
	    	   ->make(true);
    }


    public function addCustomer(Request $request)
    {
    	$validation = Validator::make($request->all(), [
            'name'    => 'required',
            'age'     => 'required',
            'address' => 'required',
            'phone'  => 'required'
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $customer = new Customer([
                    'name'    =>  $request->get('name'),
                    'age'     =>  $request->get('age'),
                    'address' =>  $request->get('address'),
                    'phone'   =>  $request->get('phone')

                ]);
                $customer->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }
            if($request->get('button_action') == 'update')
            {
                $customer = Customer::find($request->get('customer_id'));
                $customer->name    = $request->get('name');
                $customer->age     = $request->get('age');
                $customer->address = $request->get('address');
                $customer->phone   = $request->get('phone');
                $customer->save();
                $success_output = '<div class="alert alert-success">Data Updated</div>';
            }

        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    public function editCustomer(Request $request)
    {
    	$id = $request->input('id');
        $customer = Customer::find($id);
        $output = array(
            'name'    =>  $customer->name,
            'age'     =>  $customer->age,
            'address' =>  $customer->address,
            'phone'   =>  $customer->phone

        );
        echo json_encode($output);
    }

    public function deleteCustomer(Request $request)
    {
    	$customer = Customer::find($request->input('id'));
        if($customer->delete())
        {
            echo 'Data Deleted';
        }
    }

    public function exportCustomers()
    {
    	ini_set('max_execution_time', 180); //set exwcution time to 3 mins

    	$customers = Customer::take(1000);
    	

	    Excel::create('Customers', function($excel) use ($customers) {
	        $excel->sheet('report', function($sheet) use($customers) {
	            $sheet->appendRow(array(
	                'id', 'name','age', 'address','phone'
	            ));
	            $customers->chunk(300, function($rows) use ($sheet)
	            {
	                foreach ($rows as $row)
	                {
	                    $sheet->appendRow(array(
	                        $row->id, $row->name, $row->age, $row->address, $row->phone
	                    ));
	                }
	            });
	        });
	    })->download('xlsx');

    	// return Excel::download(new CsvExport, 'customers.csv');
	}

	public function viewPDF($id)
	{
		$customer = Customer::find($id);
		$pdf = PDF::loadView('pdf_view', compact('customer'));
		return $pdf->stream($customer->name.'.pdf');
	}
}
