<?php

class InventoryReport extends Connection
{
    public $table = "tbl_product_transactions";
    public function view()
    {
        $product_category_id = $this->inputs['product_category_id'];
        $rows = array();

        $result = $this->table('tbl_products AS p')
            ->join('tbl_product_transactions AS c', 'p.product_id', '=', 'c.product_id')
            ->selectRaw('p.product_id', 'p.product_name', 'p.product_price', "SUM(IF(type='IN',quantity,-quantity)) AS product_qty")
            ->where('product_category_id', $product_category_id)
            ->where('status', 1)
            ->groupBy('p.product_id')
            ->get();

        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $row['count'] = $count++;
            $row['amount'] = $row['product_qty'] * $row['product_price'];
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
