<?php

class Homepage extends Connection
{
    public function service_graph()
    {
        $rows = array();
        $result = $this->select('tbl_services');
        $count = 0;
        while($row = $result->fetch_assoc()){
            $Services = new Services();
            $list['label'] = $row['service_name'];
            $list['total'] =  $Services->monthly_total($row['service_id']);
            $rows[] = $list;
        }

        return $rows;
    }

    public function monthly_graph()
    {
        $date = $this->getCurrentDate();
        $months = array();
        $m = 1;
        for ($i = 0; $i < 12; $i++) {
           
            $result = $this->select('tbl_job_order_details as d, tbl_job_order as h', "count(h.jo_id) as count, sum(qty*price) as total", "d.jo_id=h.jo_id AND h.status = 'F' AND MONTH(h.jo_date) = '$m' AND YEAR(h.jo_date) = YEAR('$date')");

            $total_sales = $result->fetch_array();
            
            if($total_sales['count'] == 0){
                $total = 0;
            }else{
                $total = $total_sales['total'];
            }

            $months[] = $total;
            $m++;
        }
        return $months;
    }

    public function total_user(){
        $result = $this->select("tbl_users", "count(user_id)");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_jo(){
        $result = $this->select('tbl_job_order_details as d, tbl_job_order as h', "sum(qty*price)", "d.jo_id=h.jo_id AND h.status = 'F'");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_customer(){
        $result = $this->select("tbl_customers", "count(customer_id)");
        $row = $result->fetch_array();
        return $row[0];
    }

    public function total_product(){
        $result = $this->select("tbl_products", "count(product_id)");
        $row = $result->fetch_array();
        return $row[0];
    }
}

?>
