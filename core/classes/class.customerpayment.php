<?php
class CustomerPayment extends Connection
{
    private $table = 'tbl_customer_payment';
    public $pk = 'cp_id';
    public $name = 'reference_number';

    private $table_detail = 'tbl_customer_payment_details';
    public $pk2 = 'cpd_id';
    public $fk_det = 'sales_id';

    public function add()
    {
        $form = array(
            $this->name     => $this->clean($this->inputs[$this->name]),
            'customer_id'   => $this->inputs['customer_id'],
            'payment_type'    => $this->inputs['payment_type'],
            'payment_date'    => $this->inputs['payment_date'],
            'remarks'       => $this->inputs['remarks'],
        );
        return $this->insertIfNotExist($this->table, $form, '', 'Y');
    }

    public function edit()
    {
        $form = array(
            'payment_type'   => $this->inputs['payment_type'],
            'payment_date'    => $this->inputs['payment_date'],
            'remarks'       => $this->inputs['remarks'],
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
            $row['customer'] = $Customers->name($row['customer_id']);
            $row['total'] = $this->total($row['cp_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function view()
    {
        $Customers = new Customers;
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        $row['customer'] = $Customers->name($row['customer_id']);
        $row['payment_type'] = ($row['payment_type'] == "C" ? "Cash" : ($row['payment_type'] == "H" ?"Check" : "Online Payment"));
        return $row;
    }

    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function generate()
    {
        return 'CP-' . date('YmdHis');
    }

    public function finish()
    {
        
        $primary_id = $this->inputs['id'];

        $fetch_dr = $this->select($this->table_detail, "sales_id, sum(amount) as total", "$this->pk = '$primary_id' GROUP BY sales_id");
        while($drRow = $fetch_dr->fetch_array()){
            $Sales = new Sales;
            $dr_paid = $Sales->dr_balance($drRow['sales_id']) - ($drRow['total']);
            if($dr_paid <= 0){
                $form_ = array(
                    'paid_status'   => 1,
                );
                $this->update('tbl_sales', $form_, 'sales_id='.$drRow['sales_id'].'');
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
        $Sales = new Sales;
        $balance = ($Sales->dr_balance($fk_det)) - (($this->inputs['amount'])+($this->total_per_sales($primary_id,$fk_det)));

        if($balance < 0){
            return 3;
        }else{
            $form = array(
                $this->pk => $this->inputs[$this->pk],
                $this->fk_det => $fk_det,
                'amount' => $this->inputs['amount'],
                'remarks' => $this->inputs['other_payment_reference'],
                'payment_option_id' => $this->inputs['payment_option_id']
            );

            return $this->insert($this->table_detail, $form);
        }

    }

    public function show_detail()
    {
        $Sales = new Sales();
        $param = isset($this->inputs['param']) ? $this->inputs['param'] : null;
        $rows = array();
        $result = $this->select($this->table_detail, '*', $param);
        while ($row = $result->fetch_assoc()) {
            $row['sales_id'] = $Sales->name($row['sales_id']);
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

    public function total_paid($primary_id)
    {
        $result = $this->select($this->table_detail, 'sum(amount)', "$this->pk = '$primary_id'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_per_sales($primary_id,$sales_id){
        $result = $this->select($this->table_detail, 'sum(amount)', "$this->pk = '$primary_id' AND sales_id = '$sales_id'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function addPaymentPOS(){
        $Sales = new Sales;
        $primary_id = $this->add();
        $this->inputs[$this->pk] = $primary_id;
        $sales_num = $this->inputs['sales_num'];
        $param = "reference_number = '$sales_num'";
        $this->inputs[$this->fk_det] = $Sales->getID($param);
        return $this->add_detail();
    }

}
