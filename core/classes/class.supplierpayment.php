<?php
class SupplierPayment extends Connection
{
    private $table = 'tbl_supplier_payment';
    public $pk = 'sp_id';
    public $name = 'reference_number';

    private $table_detail = 'tbl_supplier_payment_details';
    public $pk2 = 'spd_id';
    public $fk_det = 'po_id';

    public function add()
    {
        $form = array(
            $this->name     => $this->clean($this->inputs[$this->name]),
            'supplier_id'   => $this->inputs['supplier_id'],
            'payment_date'    => $this->inputs['payment_date'],
            'remarks'       => $this->inputs['remarks'],
        );
        return $this->insertIfNotExist($this->table, $form, '', 'Y');
    }

    public function edit()
    {
        $form = array(
            'supplier_id'   => $this->inputs['supplier_id'],
            'payment_date'    => $this->inputs['payment_date'],
            'remarks'       => $this->inputs['remarks'],
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $Suppliers = new Suppliers;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['supplier'] = $Suppliers->name($row['supplier_id']);
            $row['total'] = $this->total($row['sp_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $Suppliers = new Suppliers;
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['supplier'] = $Suppliers->name($row['supplier_id']);
        return $row;
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function generate()
    {
        return 'SP-' . date('YmdHis');
    }

    public function finish()
    {
        $primary_id = $this->inputs['id'];

        $fetch_po = $this->select($this->table_detail, "po_id, sum(amount) as total", "$this->pk = '$primary_id' GROUP BY po_id");
        while($row = $fetch_po->fetch_array()){
            $PurchaseOrder = new PurchaseOrder;
            $po_paid = $PurchaseOrder->po_balance($row['po_id']) - ($row['total']);
            if($po_paid <= 0){
                $form_ = array(
                    'paid_status'   => 1,
                );
                $this->update('tbl_purchase_order', $form_, 'po_id='.$row['po_id'].'');
            }
        }

        $form = array(
            'status' => 'F',
        );
        return  $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    public function pk_by_name($name = null)
    {
        $name = $name == null ? $this->inputs[$this->name] : $name;
        $result = $this->select($this->table, $this->pk, "$this->name = '$name'");
        $row = $result->fetch_assoc();
        return $row[$this->pk] * 1;
    }

    public function get_row($primary_id, $field){
        $result = $this->select($this->table, $field, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$field];
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
        $PurchaseOrder = new PurchaseOrder;
        $balance = ($PurchaseOrder->total($fk_det)) - (($this->inputs['amount'])+($this->total_per_po($primary_id,$fk_det)));

        if($balance < 0){
            return 3;
        }else{
            $form = array(
                $this->pk => $this->inputs[$this->pk],
                $this->fk_det => $fk_det,
                'amount' => $this->inputs['amount'],
            );

            return $this->insert($this->table_detail, $form);
        }

    }

    public function show_detail()
    {
        $PurchaseOrder = new PurchaseOrder;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table_detail, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['po_id'] = $PurchaseOrder->name($row['po_id']);
            $row['amount'] = number_format($row['amount'],2);
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
        $result = $this->select($this->table_detail, 'sum(amount)', "$this->pk = '$primary_id'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_per_po($primary_id,$po_id){
        $result = $this->select($this->table_detail, 'sum(amount)', "$this->pk = '$primary_id' AND po_id = '$po_id'");
        $row = $result->fetch_array();
        return $row[0];
    }

}
