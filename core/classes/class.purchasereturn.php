<?php
class PurchaseReturn extends Connection
{
    private $table = 'tbl_purchase_return';
    public $pk = 'pr_id';
    public $name = 'reference_number';

    private $table_detail = 'tbl_purchase_return_details';
    public $pk2 = 'pr_detail_id';
    public $fk_det = 'product_id';

    public $module = 'PO-RET-';

    public function add()
    {
        $PurchaseOrder = new PurchaseOrder;
        $po_id = $PurchaseOrder->pk_by_name($this->inputs['po_reference_number']);
        $form = array(
            $this->name     => $this->clean($this->inputs[$this->name]),
            'po_id'      => $po_id,
            'remarks'       => $this->inputs['remarks'],
            'return_date'   => $this->inputs['return_date'],
        );
        $pr_id = $this->insertIfNotExist($this->table, $form, "po_id = '$po_id'", 'Y');

        $form_detail = array(
            'pr_id' => "$pr_id AS pr_id",
            'product_id' => 'product_id',
            'qty' => 'qty',
            'supplier_price' => 'supplier_price'
        );

        $this->insert_select($this->table_detail, 'tbl_purchase_order_details', $form_detail, "po_id = '$po_id'");
        return $pr_id;
    }

    public function edit()
    {
        $form = array(
            'supplier_id'   => $this->inputs['supplier_id'],
            'po_type'    => $this->inputs['po_type'],
            'remarks'       => $this->inputs['remarks'],
            'po_date'    => $this->inputs['po_date'],
        );
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $PurchaseOrder = new PurchaseOrder;
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['po_reference_number'] = $PurchaseOrder->name($row['po_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $PurchaseOrder = new PurchaseOrder;
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['po_reference_number'] = $PurchaseOrder->name($row['po_id']);
        return $row;
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function generate()
    {
        return $this->module . date('YmdHis');
    }

    public function finish()
    {
        $primary_id = $this->inputs['id'];
        $form = array(
            'status' => 'F',
        );
        return $this->update($this->table, $form, "$this->pk = '$primary_id'");
    }

    public function add_detail()
    {
        $primary_id_2   = $this->inputs[$this->pk2];
        $qty_return     = $this->inputs['qty_return'];

        $result = $this->select($this->table_detail, 'qty,qty_return', "$this->pk2 = '$primary_id_2'");
        $row = $result->fetch_assoc();

        $new_qty_return = $row['qty_return'] + $qty_return;
        if ($new_qty_return > $row['qty']) {
            return 2;
        }else {
            $form = array(
                'qty_return' => $new_qty_return,
            );
            return $this->update($this->table_detail, $form, "$this->pk2 = '$primary_id_2'");
        }
    }

    public function show_detail()
    {
        $Products = new Products();
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table_detail, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['product'] = $Products->name($row['product_id']);
            $row['amount'] = $row['qty'] * $row['supplier_price'];
            $rows[] = $row;
        }
        return $rows;
    }

    public function remove_detail()
    {
        $form = array(
            'qty_return' => 0,
        );
        $ids = implode(",", $this->inputs['ids']);
        return $this->update($this->table_detail, $form, "$this->pk2 IN($ids)");
    }

    private function delete_PurchaseOrder_details()
    {
        $query = "CREATE TRIGGER `delete_PurchaseOrder_details` AFTER DELETE ON `tbl_PurchaseOrder` FOR EACH ROW DELETE FROM tbl_purchase_order_details WHERE po_id = OLD.po_id";
    }

    private function finish_transaction()
    {
        $query = "CREATE TRIGGER `finish_transaction` AFTER UPDATE ON `tbl_PurchaseOrder` FOR EACH ROW UPDATE tbl_product_transactions SET status = IF (NEW.status = 'F', 1, 0) WHERE header_id = NEW.po_id AND module = 'SLS'";
    }

    private function add_transaction_in()
    {
        $query = "CREATE TRIGGER `add_transaction_in` AFTER INSERT ON `tbl_purchase_order_details` FOR EACH ROW INSERT INTO tbl_product_transactions (product_id,qty,cost,supplier_price,header_id,detail_id,module,type) VALUES (NEW.product_id,NEW.qty,NEW.cost,NEW.supplier_price,NEW.po_id,NEW.PurchaseOrder_detail_id,'SLS','OUT')";
    }
}
