<?php

class InventoryReport extends Connection
{
    private $table = 'tbl_products';
    public $pk = 'product_id';
    public $name = 'product_name';

    public function generate_report()
    {
        $inv_date = $_REQUEST['inv_date'];

        $result = $this->select($this->table);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $qty = $this->invQty($row['product_id'], $inv_date);

            $row['product_name'] = $row['product_name'];
            $row['cost'] = $row['product_cost'];
            $row['qty'] = $qty > 10 ? $qty : "<strong style='color:red;'>".$qty."</strong>" ;
            $rows[] = $row;
        }
        return $rows;

    }

    public function getJoFinished($product_id)
    {
        $result = $this->select("tbl_job_orders", "SUM(no_of_batches) as qty", "product_id='$product_id' AND STATUS='F' GROUP BY product_id");
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            $row = $result->fetch_assoc();
        } else {
            $row = array(
                "qty" => 0
            );
        }
        return $row;
    }
    public function getJoDetails($product_id)
    {
        $result = $this->select("tbl_job_orders jo, tbl_job_order_details AS jod", "SUM(jod.qty) AS qty ", "jo.`status`='F' AND jod.product_id='$product_id'  AND jod.job_order_id=jo.job_order_id  GROUP BY jod.product_id");
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            $row = $result->fetch_assoc();
        } else {
            $row = array(
                "qty" => 0
            );
        }
        return $row;
    }

    public function getSales($product_id)
    {

        $result = $this->select("tbl_sales AS s, tbl_sales_details AS sd", "SUM(sd.qty) AS qty", "sd.product_id='$product_id' AND s.`status`='F' AND sd.sales_id=s.sales_id GROUP BY sd.product_id");
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            $row = $result->fetch_assoc();
        } else {
            $row = array(
                "qty" => 0
            );
        }
        return $row;
    }

    public function currentQty($product_id){

        //in
        $fetch_po = $this->select("tbl_purchase_order po, tbl_purchase_order_details pod", "SUM(pod.qty)", "pod.product_id='$product_id' AND po.`status`='F' AND po.po_id=pod.po_id");
        $po_qty = $fetch_po->fetch_array();

        $in_qty = $po_qty[0];

        //out
        $fetch_jo_out = $this->select("tbl_job_order jo, tbl_job_order_details as jd", "SUM(jd.qty)", "jd.product_id='$product_id' AND jo.status='F' AND jo.jo_id=jd.jo_id");
        $jo_out_qty = $fetch_jo_out->fetch_array();
        
        $out_qty = $jo_out_qty[0];

        return $in_qty-$out_qty;
    }

    public function low_inventory(){
        $result = $this->select($this->table);
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $qty = $this->currentQty($row['product_id']);
            if($qty < 10){
                $total += 1;
            }else{
                $total += 0;
            }
            
        }
        return $total;
    }

    public function invQty($product_id, $inv_date){
        //in
        $fetch_po = $this->select("tbl_purchase_order po, tbl_purchase_order_details pod", "SUM(pod.qty)", "pod.product_id='$product_id' AND po.`status`='F' AND po.po_id=pod.po_id AND po.po_date <= '$inv_date'");
        $po_qty = $fetch_po->fetch_array();

        $in_qty = $po_qty[0];

        //out
        $fetch_jo_out = $this->select("tbl_job_order jo, tbl_job_order_details as jd", "SUM(jd.qty)", "jd.product_id='$product_id' AND jo.status='F' AND jo.jo_id=jd.jo_id AND jo.jo_date <= '$inv_date'");
        $jo_out_qty = $fetch_jo_out->fetch_array();
        
        $out_qty = $jo_out_qty[0];

        return $in_qty-$out_qty;
    }
    
}
