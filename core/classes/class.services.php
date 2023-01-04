<?php
class Services extends Connection
{
    private $table = 'tbl_services';
    public $pk = 'service_id';
    public $name = 'service_name';

    public function add()
    {
        $form = array(
            $this->name         => $this->clean($this->inputs[$this->name]),
            'service_fee'       => $this->inputs['service_fee'],
            'service_remarks'   => $this->inputs['service_remarks'],
            'date_added'        => $this->getCurrentDate(),
        );

        $this->insert_logs('Added new Service (Name:'. $this->clean($this->inputs[$this->name]).')','');
        return $this->insertIfNotExist($this->table, $form);
    }

    public function edit()
    {
        $form = array(
            $this->name         => $this->clean($this->inputs[$this->name]),
            'service_fee'       => $this->inputs['service_fee'],
            'service_remarks'   => $this->inputs['service_remarks']
        );

        $this->insert_logs('Updated Service (Name:'. $this->clean($this->inputs[$this->name]).')','');
        return $this->updateIfNotExist($this->table, $form);
    }

    public function show()
    {
        $rows = array();
        $result = $this->select($this->table);
        while ($row = $result->fetch_assoc()) {
            $row['service_amount'] = number_format($row['service_fee'],2);
            $rows[] = $row;
        }
        return $rows;
    }


    public function view()
    {
        $primary_id = $this->inputs['id'];
        $result = $this->select($this->table, "*", "$this->pk = '$primary_id'");
        return $result->fetch_assoc();
    }
    
    public function remove()
    {
        $ids = implode(",", $this->inputs['ids']);
        $result = $this->select($this->table, $this->name, "$this->pk IN ($ids)");
        $reference = "";
        while($row = $result->fetch_array()){
            $reference .= $row[0].", ";
        }
        $this->insert_logs('Deleted Service (Name: '.substr($reference, 0, -2).')');
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return $row[$this->name];
        }else{
            return "";
        }
    }

    public function service_fee($primary_id)
    {
        $result = $this->select($this->table, 'service_fee', "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row['service_fee'];
    }

    public function monthly_total($id)
    {
        $date = $this->getCurrentDate();
        $result = $this->select('tbl_job_order_details as d, tbl_job_order as h', "sum(amount) as total", "h.jo_id=d.jo_id AND h.status='F' AND MONTH(h.jo_date) = MONTH('$date') AND h.service_id='$id'");
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $total += $row['total'];
        }

        return $total;
    }
}
