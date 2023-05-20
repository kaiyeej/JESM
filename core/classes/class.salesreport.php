<?php

class SalesReport extends Connection
{
  
    public function generate_report()
    {
        $start_date = $_REQUEST['start_date'];
        $end_date = $_REQUEST['end_date'];
        $result = $this->select("tbl_job_order AS s, tbl_job_order_details AS sd", "sd.product_id, s.customer_id, ((sd.qty * sd.price)) as total, SUM(sd.qty) as qty, SUM(sd.price) as price", "s.jo_id=sd.jo_id AND s.status='F' AND s.jo_date BETWEEN '$start_date' AND '$end_date' GROUP BY sd.product_id");
        $rows = array();

        $Customer = new Customers;
        $Product = new Products;
        while ($row = $result->fetch_assoc()) {
            $row['customer'] = $Customer->name($row['customer_id']);
            $row['product'] = $Product->name($row['product_id']);
            $row['total'] = number_format($row['total'], 2);
            $row['qty'] = number_format($row['qty'], 2);
            $rows[] = $row;
        }
        return $rows;
    }
}
