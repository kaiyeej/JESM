<?php
class Sales extends Connection
{
    private $table = 'tbl_sales';
    public $pk = 'sales_id';
    public $name = 'reference_number';

    private $table_detail = 'tbl_sales_details';
    public $pk2 = 'sales_detail_id';
    public $fk_det = 'product_id';

    public function add()
    {
        $form = array(
            $this->name     => $this->clean($this->inputs[$this->name]),
            'sales_invoice' => $this->inputs['sales_invoice'],
            'customer_id'   => $this->inputs['customer_id'],
            'sales_type'    => $this->inputs['sales_type'],
            'remarks'       => $this->inputs['remarks'],
            'paid_status'   => ($this->inputs['sales_type'] == "C"? 1: 0),
            'sales_date'    => $this->inputs['sales_date'],
        );
        return $this->insertIfNotExist($this->table, $form, '', 'Y');
    }

    public function edit()
    {
        $form = array(
            'customer_id'   => $this->inputs['customer_id'],
            'sales_type'    => $this->inputs['sales_type'],
            'remarks'       => $this->inputs['remarks'],
            'paid_status'   => ($this->inputs['sales_type'] == "C"? 1: 0),
            'sales_date'    => $this->inputs['sales_date'],
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $Customers = new Customers;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['customer'] = $row['customer_id'] > 0 ? $Customers->name($row['customer_id']) : 'walk-in';
            $row['total'] = number_format($this->total($row['sales_id']), 2);
            
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['customer_name'] = "Jerry";
        return $row;
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function generate()
    {
        return 'SLS-' . date('YmdHis');
    }

    public function finish()
    {
        $primary_id = $this->inputs['id'];
        $form = array(
            'status' => 'F',
        );
        return $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    public function pk_by_name($name = null)
    {
        $name = $name == null ? $this->inputs[$this->name] : $name;
        $result = $this->select($this->table, $this->pk, "$this->name = '$name'");
        $row = $result->fetch_assoc();
        return $row[$this->pk] * 1;
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->name];
    }

    public function add_detail()
    {
        $primary_id = $this->inputs[$this->pk];
        $fk_det     = $this->inputs[$this->fk_det];
        $Products = new Products;
        $product_price = $Products->productPrice($fk_det);

        $form = array(
            $this->pk               => $this->inputs[$this->pk],
            $this->fk_det           => $fk_det,
            //'product_category_id'   => $this->inputs['product_category_id'],
            'quantity'              => $this->inputs['quantity'],
            'price'                 => $product_price,
            'cost'                  => 0 // change this value
        );

        return $this->insertIfNotExist($this->table_detail, $form, "$this->pk = '$primary_id' AND $this->fk_det = '$fk_det'");
    }

    public function show_detail()
    {
        $Products = new Products;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $count = 1;
        $result = $this->select($this->table_detail, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['product'] = $Products->name($row['product_id']);
            $row['amount'] = number_format($row['qty'] * $row['price'], 2);
            $row['pos_qty'] = number_format($row['qty']);
            $row['pos_price'] = "@".$row['price'];
            $row['count'] = $count++;
            $rows[] = $row;
        }
        return $rows;
    }

    public function remove_detail()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table_detail, "$this->pk2 IN($ids)");
    }

    public function total($primary_id)
    {
        $result = $this->select($this->table_detail, 'sum(qty*price)', "$this->pk = '$primary_id'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function cancel_sales(){
        $reference_number = $this->inputs['reference_number'];
        $param = "reference_number = '$reference_number'";
        $primary_id = $this->getID($param);
        $form = array(
            'status' => 'C'
        );

        return $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    public function save_sales(){
        $reference_number = $this->inputs['reference_number'];
        $param = "reference_number = '$reference_number'";
        $primary_id = $this->getID($param);
        $form = array(
            'status' => 'P'
        );

        return $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    private function delete_sales_details()
    {
        $query = "CREATE TRIGGER `delete_sales_details` AFTER DELETE ON `tbl_sales` FOR EACH ROW DELETE FROM tbl_sales_details WHERE sales_id = OLD.sales_id";
    }

    public function cancel_sales_trigger(){
        $query = "DELIMITER $$
        CREATE TRIGGER `finish_sales` AFTER UPDATE ON `tbl_sales` FOR EACH ROW 
        BEGIN
            UPDATE tbl_product_transactions SET status = IF (NEW.status = 'F', 1, 0) WHERE header_id = NEW.sales_id AND module = 'SLS';
            DELETE from tbl_product_transactions WHERE header_id=NEW.sales_id and module='SLS' and status='C';
        END$$
        
        DELIMITER ;";
    }

    private function finish_transaction()
    {
        $query = "CREATE TRIGGER `finish_transaction` AFTER UPDATE ON `tbl_sales` FOR EACH ROW UPDATE tbl_product_transactions SET status = IF (NEW.status = 'F', 1, 0) WHERE header_id = NEW.sales_id AND module = 'SLS'";
    }

    private function add_transaction_in()
    {
        $query = "CREATE TRIGGER `add_transaction_in` AFTER INSERT ON `tbl_sales_details` FOR EACH ROW INSERT INTO tbl_product_transactions (product_id,quantity,cost,price,header_id,detail_id,module,type) VALUES (NEW.product_id,NEW.quantity,NEW.cost,NEW.price,NEW.sales_id,NEW.sales_detail_id,'SLS','OUT')";
    }

    public function getID($param){
        $fetch = $this->select($this->table, $this->pk, $param);
        $row = $fetch->fetch_array();
        return $row[0];
    }


}
