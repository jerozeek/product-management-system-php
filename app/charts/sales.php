<?php



?>


<div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <a style="float: left; margin-top: 10px; border-radius: 0" href="#" class="btn btn-primary">
                        <span class="fa fa-dollar"></span>  <strong>Sales Statistice</strong>
                    </a>
                </header>
    <div class="panel-body">
        <div class="col-md-12 w3ls-graph">
            <!--agileinfo-grap-->
            <div class="agileinfo-grap" style="background-color: white">
                <div class="agileits-box">
                    <div class="agileits-box-body clearfix" >
                        <div id="hero-area"></div>
                    </div>
                </div>
            </div>
            <!--//agileinfo-grap-->

        </div>
    </div>
</div>
</div>


<script>
    //CHARTS
    function gd(year, day, month) {
        return new Date(year, month - 1, day).getTime();
    }

    graphArea2 = Morris.Area({
        element: 'hero-area',
        padding: 10,
        behaveLikeLine: true,
        gridEnabled: false,
        gridLineColor: '#dddddd',
        axes: true,
        resize: true,
        smooth:true,
        pointSize: 0,
        lineWidth: 0,
        fillOpacity:0.85,
        data: [
            {period: '2015 Q1', iphone: 2668, ipad: null, itouch: 2649},
            {period: '2015 Q2', iphone: 15780, ipad: 13799, itouch: 12051},
            {period: '2015 Q3', iphone: 12920, ipad: 10975, itouch: 9910},
            {period: '2015 Q4', iphone: 8770, ipad: 6600, itouch: 6695},
            {period: '2016 Q1', iphone: 10820, ipad: 10924, itouch: 12300},
            {period: '2016 Q2', iphone: 9680, ipad: 9010, itouch: 7891},
            {period: '2016 Q3', iphone: 4830, ipad: 3805, itouch: 1598},
            {period: '2016 Q4', iphone: 15083, ipad: 8977, itouch: 5185},
            {period: '2017 Q1', iphone: 10697, ipad: 4470, itouch: 2038},

        ],
        lineColors:['#eb6f6f','#926383','#eb6f6f'],
        xkey: 'period',
        redraw: true,
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['All Visitors', 'Returning Visitors', 'Unique Visitors'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });

</script>
