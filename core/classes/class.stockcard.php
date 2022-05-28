<?php

class StockCard extends Connection
{
    public $table = "tbl_product_transactions";
    public function view()
    {
        $product_id = $this->inputs['product_id'];
        $rows = array();

        $result = $this->select($this->table, "*,IF(type='IN',quantity,0) AS qty_in,IF(type='OUT',quantity,0) AS qty_out", "product_id = '$product_id' AND status = '1' ORDER BY date_modified ASC");

        $qty_balance = 0;
        while ($row = $result->fetch_assoc()) {
            $qty = $row['qty_in'] - $row['qty_out'];
            $qty_balance += $qty;

            $row['qty_balance'] = $qty_balance;
            $row['amount'] = $qty_balance * $row['price'];
            $row['date'] = date('M d,Y', strtotime($row['date_modified']));
            $rows[] = $row;
        }
        return $rows;
    }

    public function balance($product_id)
    {
        $result = $this->select($this->table, "SUM(IF(type='IN',quantity,-quantity)) AS qty", "product_id = '$product_id' AND status = 1");
        $row = $result->fetch_assoc();
        return (float) $row['qty'];
    }
}
