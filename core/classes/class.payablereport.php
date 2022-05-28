<?php

class PayableReport extends Connection
{
    public $table = "tbl_suppliers";

    public function view()
    {
        $supplier_id = $this->inputs['supplier_id'];
        $rows = array();

        if($supplier_id == -1){
            $query = "";
        }else{
            $query = "supplier_id='$supplier_id'";
        }

        $result = $this->select($this->table, "*", $query);

        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $row['balance'] = number_format($this->balance($row['supplier_id']),2);
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
    }

    public function balance($supplier_id)
    {
        $get_payment = $this->select("tbl_purchase_order AS po, tbl_purchase_order_details AS pd, tbl_supplier_payment AS sh, tbl_supplier_payment_details AS sd", "SUM(sd.amount) as total", "po.supplier_id='$supplier_id' AND sh.supplier_id='$supplier_id' AND sh.sp_id=sd.sp_id AND sd.po_id=po.po_id AND sh.status='F' AND po.paid_status=0 AND pd.po_id=po.po_id AND sh.status='F' AND po.status='F' AND po.po_type='H'");
        $payment_row = $get_payment->fetch_assoc();

        $get_po = $this->select("tbl_purchase_order as po, tbl_purchase_order_details AS pd", "SUM(pd.supplier_price*pd.qty) as total", "po.supplier_id='$supplier_id' AND po.paid_status=0 AND pd.po_id=po.po_id AND po.status='F' AND po.po_type='H'");
        $po_row = $get_po->fetch_assoc();

        
        return $po_row['total']-$payment_row['total'];
    }
}
