<?php


class Balance extends Report
{
    function __construct($db)
    {
        parent::__construct($db);
    }
    public function last_five_days(){
        $dates = array();
        for($i = 0; $i < 12; $i++){
            $dates[] = date('Y-m-d',strtotime("-$i days"));
        }
        return json_encode($dates);
    }
    public function last_ten_years(){
        $years = array();
        for($i = 0; $i < 10; $i++){
            $years[] = date('Y',strtotime("-$i years"));
        }
        return json_encode($years);
    }
    public function all_month(){
        $month = array();
        for ($m=1; $m<=12; $m++) {
            $month[] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }
        return json_encode($month);
    }
    public function all_day(){
        $list = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        return json_encode($list);
    }

}