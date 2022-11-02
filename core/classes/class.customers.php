<?php

class Customers extends Connection
{
    private $table = 'tbl_customers';
    public $pk = 'customer_id';
    public $name = 'customer_name';

    public function add()
    {
        $form = array(
            $this->name                 => $this->clean($this->inputs[$this->name]),
            'customer_address'          => $this->inputs['customer_address'],
            'customer_contact_number'   => $this->inputs['customer_contact_number'],
            'remarks'                   => $this->inputs['remarks'],
            'date_added'                => $this->getCurrentDate(),
        );

        $this->insert_logs('Added New Customer (Name:'. $this->clean($this->inputs[$this->name]).')','');

        return $this->insertIfNotExist($this->table, $form);
    }

    public function edit()
    {
        $primary_id = $this->inputs[$this->pk];
        $customer_name = $this->clean($this->inputs['customer_name']);
        $is_exist = $this->select($this->table, $this->pk, "customer_name = '$customer_name' AND $this->pk != '$primary_id'");
        if ($is_exist->num_rows > 0) {
            return 2;
        } else {
            $form = array(
                'customer_name' => $this->inputs['customer_name'],
                'customer_address' => $this->inputs['customer_address'],
                'customer_contact_number' => $this->inputs['customer_contact_number'],
                'remarks' => $this->inputs['remarks'],
                'date_last_modified' => $this->getCurrentDate()
            );

            $this->insert_logs('Updated Customer (Name:'. $this->clean($this->inputs[$this->name]).')','');
            return $this->update($this->table, $form, "$this->pk = '$primary_id'");
        }
    }

    public function show()
    {
        $rows = array();
        $result = $this->select($this->table);
        while ($row = $result->fetch_assoc()) {
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
        $this->insert_logs('Deleted Customer (Customer: '.substr($reference, 0, -2).')');
        return $this->delete($this->table, "$this->pk IN($ids)");
    }

    public function name($primary_id)
    {
        $result = $this->select($this->table, $this->name, "$this->pk = '$primary_id'");
        $row = $result->fetch_assoc();
        return $row[$this->name];
    }
}
