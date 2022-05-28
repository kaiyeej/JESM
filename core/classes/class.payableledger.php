<?php

class PayableLedger extends Connection
{


    public function view()
    {
        $supplier_id = $this->inputs['supplier_id'];
        $start_date = $this->inputs['start_date'];
        $end_date = $this->inputs['end_date'];
        
        $rows = array();

        $result = $this->select("tbl_purchase_order","reference_number","supplier_id='$supplier_id' AND (po_date >= '$start_date' AND po_date <= '$end_date') AND status='F' UNION ALL SELECT reference_number FROM tbl_supplier_payment where supplier_id='$supplier_id' AND (payment_date >= '$start_date' AND payment_date <= '$end_date') AND status='F'");
        
        $PurchaseOrder = new PurchaseOrder;
        $SupplierPayment = new SupplierPayment;
        $bf = $this->total();
        $balance = (float) $bf[0];
        while ($row = $result->fetch_assoc()) {

            $trans = substr($row['reference_number'], 0, 2);

            if($trans == "PO"){
                $trans = "Purchase Order";
                $debit = $PurchaseOrder->total($PurchaseOrder->pk_by_name($row['reference_number']));
                $credit = 0;
                $balance += $debit;
                $date = $PurchaseOrder->get_row($PurchaseOrder->pk_by_name($row['reference_number']), 'po_date');
            }else if($trans == "SP"){
                $trans = "Supplier Payment";
                $debit = 0;
                $credit = $SupplierPayment->total($SupplierPayment->pk_by_name($row['reference_number']));
                $balance -= $credit;
                $date = $SupplierPayment->get_row($SupplierPayment->pk_by_name($row['reference_number']), 'payment_date');
            }else{
                $trans = "Purchase Return";
                $debit = 0;
                $credit = 0;
                $balance -= $credit;
                $date = "";
            }

            $row['date'] = $date;
            $row['reference_number'] = $row['reference_number'];
            $row['transaction'] = $trans;
            $row['debit'] = number_format($debit,2);
            $row['credit'] = number_format($credit,2);
            $row['balance'] = number_format($balance,2);
            $rows[] = $row;
        }
        return $rows;
    }

    public function total()
    {
        $supplier_id = $this->inputs['supplier_id'];
        $start_date = $this->inputs['start_date'];
        $end_date = $this->inputs['end_date'];

        $get_po = $this->select("tbl_purchase_order as h, tbl_purchase_order_details as d","sum(d.supplier_price*d.qty)","h.supplier_id='$supplier_id' AND h.po_date < '$start_date' AND h.status='F' AND h.po_id=d.po_id");
        $total_po = $get_po->fetch_array();

        $get_payment = $this->select("tbl_supplier_payment as h, tbl_supplier_payment_details as d","sum(d.amount)","h.supplier_id='$supplier_id' AND h.payment_date < '$start_date' AND h.status='F' AND h.sp_id=d.sp_id");
        $total_payment = $get_payment->fetch_array();

        $bf = $total_po[0]-$total_payment[0];
        $total = "";
        
        return [$bf,$total];
        
    }
    
    public function balance($supplier_id)
    {
       
    }
}
